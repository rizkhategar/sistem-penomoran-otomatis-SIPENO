<?php

namespace App\Http\Controllers;

use App\Models\LetterSubmission;
use App\Models\LetterType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private function months(): array
    {
        return ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    }

    private function officialBidangs(): array
    {
        return [
            'PELAYANAN PENDAFTARAN PENDUDUK',
            'PELAYANAN PENCATATAN SIPIL',
            'PIAK',
            'SEKRETARIATAN',
        ];
    }

    private function applyReportFilters(Builder $query, int $bulan, int $tahun, ?string $bidang): Builder
    {
        $query->whereRaw('YEAR(COALESCE(submission_date, created_at)) = ?', [$tahun])
            ->whereRaw('MONTH(COALESCE(submission_date, created_at)) = ?', [$bulan]);

        if ($bidang) {
            $query->whereHas('letterType', fn ($q) => $q->where('bidang', $bidang));
        }

        return $query;
    }

    private function orderedReportQuery(Builder $query): Builder
    {
        return $query->orderByDesc(DB::raw('COALESCE(submission_date, created_at)'))
            ->orderByDesc('id');
    }

    private function reportBaseQuery(Request $request): array
    {
        $bulan = (int) ($request->bulan ?? now()->format('n'));
        $tahun = (int) ($request->tahun ?? now()->format('Y'));
        $bidang = $request->bidang;

        $baseQuery = LetterSubmission::with(['user', 'letterType']);
        $this->applyReportFilters($baseQuery, $bulan, $tahun, $bidang);

        $perJenisQuery = LetterSubmission::query();
        $this->applyReportFilters($perJenisQuery, $bulan, $tahun, $bidang);

        $perJenis = $perJenisQuery
            ->selectRaw('letter_type_id, COUNT(*) as total')
            ->groupBy('letter_type_id')
            ->with('letterType')
            ->get();

        $bidangs = collect($this->officialBidangs())
            ->merge(User::whereNotNull('bidang')->pluck('bidang'))
            ->merge(LetterType::whereNotNull('bidang')->pluck('bidang'))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return [$baseQuery, $perJenis, $bulan, $tahun, $bidang, $bidangs];
    }

    public function index(Request $request)
    {
        [$baseQuery, $perJenis, $bulan, $tahun, $bidang, $bidangs] = $this->reportBaseQuery($request);

        $total = (clone $baseQuery)->count();
        $submissions = $this->orderedReportQuery(clone $baseQuery)
            ->paginate(20)
            ->withQueryString();

        $months = $this->months();
        $tahuns = range(now()->year - 2, now()->year + 1);

        return view('report.index', compact(
            'submissions',
            'total',
            'perJenis',
            'bulan',
            'tahun',
            'bidang',
            'bidangs',
            'months',
            'tahuns'
        ));
    }

    public function pdf(Request $request)
    {
        [$baseQuery, $perJenis, $bulan, $tahun, $bidang] = $this->reportBaseQuery($request);

        $submissions = $this->orderedReportQuery(clone $baseQuery)->get();
        $months = $this->months();
        $pdf = $this->buildMonthlyReportPdf($submissions, $perJenis, $bulan, $tahun, $bidang, $months);
        $filename = 'laporan-surat-'.$tahun.'-'.str_pad((string) $bulan, 2, '0', STR_PAD_LEFT).'.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    private function buildMonthlyReportPdf($submissions, $perJenis, int $bulan, int $tahun, ?string $bidang, array $months): string
    {
        $rows = $submissions->map(function ($submission, int $index) {
            $date = $submission->submission_date ?: $submission->created_at;

            return [
                (string) ($index + 1),
                $date?->format('d/m/Y') ?? '-',
                $submission->letter_number ?: '-',
                $submission->user->name ?? '-',
                $submission->letterType->bidang ?? '-',
                $submission->letterType->name ?? '-',
                $submission->pengolah ?? '-',
                $submission->ditujukan_kepada ?? '-',
            ];
        })->values()->all();

        $rekapJenis = $perJenis->map(function ($item) {
            return ($item->letterType->name ?? 'Jenis tidak ditemukan').' = '.$item->total;
        })->values()->all();

        $context = [
            'periode' => $months[$bulan - 1].' '.$tahun,
            'bidang' => $bidang ?: 'Semua Bidang',
            'total' => $submissions->count(),
            'printed_at' => now()->format('d/m/Y H:i'),
            'rekap_jenis' => $rekapJenis,
        ];

        $pages = array_chunk($rows, 18);
        if ($pages === []) {
            $pages = [[]];
        }

        return $this->makePdf($pages, $context);
    }

    private function makePdf(array $pages, array $context): string
    {
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            2 => '',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>',
        ];

        $kids = [];
        $nextObject = 5;
        $pageWidth = 842;
        $pageHeight = 595;

        foreach ($pages as $pageIndex => $rows) {
            $contentObject = $nextObject++;
            $pageObject = $nextObject++;
            $kids[] = $pageObject.' 0 R';
            $stream = $this->pageStream($rows, $pageIndex + 1, count($pages), $context);

            $objects[$contentObject] = "<< /Length ".strlen($stream)." >>\nstream\n{$stream}\nendstream";
            $objects[$pageObject] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 {$pageWidth} {$pageHeight}] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents {$contentObject} 0 R >>";
        }

        $objects[2] = '<< /Type /Pages /Kids ['.implode(' ', $kids).'] /Count '.count($kids).' >>';
        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $id => $body) {
            $offsets[$id] = strlen($pdf);
            $pdf .= $id." 0 obj\n".$body."\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }

    private function pageStream(array $rows, int $page, int $totalPages, array $context): string
    {
        $stream = '';
        $stream .= $this->drawHeader();
        $stream .= $this->drawReportInfo($context);
        $stream .= $this->drawTable($rows);

        $stream .= $this->drawText('Dicetak melalui SIPENO Disdukcapil pada '.$context['printed_at'], 36, 24, 7, 'F1', [0.35, 0.39, 0.45]);
        $stream .= $this->drawText('Halaman '.$page.' dari '.$totalPages, 740, 24, 7, 'F1', [0.35, 0.39, 0.45]);

        return $stream;
    }

    private function drawHeader(): string
    {
        $stream = '';
        $stream .= $this->fillRect(36, 510, 770, 56, [0.93, 0.96, 1.00]);
        $stream .= $this->strokeRect(36, 510, 770, 56, [0.78, 0.84, 0.94]);

        // Logo mark SIPENO.
        $stream .= $this->fillRect(50, 522, 34, 34, [0.12, 0.28, 0.63]);
        $stream .= $this->drawText('S', 62, 534, 18, 'F2', [1, 1, 1]);
        $stream .= $this->drawText('SIPENO DISDUKCAPIL', 98, 546, 16, 'F2', [0.08, 0.12, 0.20]);
        $stream .= $this->drawText('Sistem Penomoran Surat Dinas', 98, 532, 9, 'F1', [0.25, 0.31, 0.40]);
        $stream .= $this->drawText('Laporan resmi pengajuan dan penomoran surat bulanan', 98, 520, 8, 'F1', [0.39, 0.45, 0.55]);

        $stream .= $this->drawText('LAPORAN BULANAN', 664, 543, 12, 'F2', [0.12, 0.28, 0.63]);
        $stream .= $this->drawText('Nomor Surat', 704, 529, 8, 'F1', [0.39, 0.45, 0.55]);
        $stream .= $this->line(36, 500, 806, 500, [0.12, 0.28, 0.63], 1.2);

        return $stream;
    }

    private function drawReportInfo(array $context): string
    {
        $stream = '';
        $stream .= $this->fillRect(36, 450, 770, 36, [1, 1, 1]);
        $stream .= $this->strokeRect(36, 450, 770, 36, [0.88, 0.91, 0.95]);

        $stream .= $this->drawText('Periode', 52, 472, 8, 'F2', [0.39, 0.45, 0.55]);
        $stream .= $this->drawText($context['periode'], 52, 458, 11, 'F2', [0.08, 0.12, 0.20]);

        $stream .= $this->drawText('Bidang', 208, 472, 8, 'F2', [0.39, 0.45, 0.55]);
        $stream .= $this->drawText($this->fitText($context['bidang'], 42), 208, 458, 11, 'F2', [0.08, 0.12, 0.20]);

        $stream .= $this->drawText('Total Surat', 520, 472, 8, 'F2', [0.39, 0.45, 0.55]);
        $stream .= $this->drawText((string) $context['total'].' surat', 520, 458, 11, 'F2', [0.08, 0.12, 0.20]);

        $rekap = $context['rekap_jenis'] === [] ? 'Rekap jenis: tidak ada data' : 'Rekap jenis: '.implode('; ', $context['rekap_jenis']);
        $stream .= $this->drawText($this->fitText($rekap, 128), 36, 430, 8, 'F1', [0.35, 0.39, 0.45]);

        return $stream;
    }

    private function drawTable(array $rows): string
    {
        $stream = '';
        $x = 36;
        $y = 398;
        $rowHeight = 18;
        $headers = ['No', 'Tanggal', 'Nomor Surat', 'Pembuat', 'Bidang', 'Jenis', 'Pengolah', 'Ditujukan Kepada'];
        $widths = [26, 58, 112, 90, 105, 115, 95, 169];

        $stream .= $this->fillRect($x, $y, array_sum($widths), $rowHeight, [0.12, 0.28, 0.63]);
        $cursor = $x;
        foreach ($headers as $index => $header) {
            $stream .= $this->drawText($header, $cursor + 4, $y + 6, 7, 'F2', [1, 1, 1]);
            $cursor += $widths[$index];
        }

        $y -= $rowHeight;

        if ($rows === []) {
            $stream .= $this->strokeRect($x, $y - 14, array_sum($widths), 32, [0.88, 0.91, 0.95]);
            $stream .= $this->drawText('Tidak ada data surat pada periode ini.', $x + 250, $y, 9, 'F1', [0.39, 0.45, 0.55]);
            return $stream;
        }

        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex % 2 === 0) {
                $stream .= $this->fillRect($x, $y, array_sum($widths), $rowHeight, [0.98, 0.99, 1.00]);
            }

            $stream .= $this->strokeRect($x, $y, array_sum($widths), $rowHeight, [0.90, 0.93, 0.96], 0.4);
            $cursor = $x;

            foreach ($row as $index => $value) {
                $stream .= $this->drawText(
                    $this->fitText((string) $value, $this->columnTextWidth($widths[$index])),
                    $cursor + 4,
                    $y + 6,
                    7,
                    $index === 2 ? 'F2' : 'F1',
                    [0.12, 0.16, 0.23]
                );
                $cursor += $widths[$index];
            }

            $y -= $rowHeight;
        }

        return $stream;
    }

    private function columnTextWidth(int $width): int
    {
        return max(4, (int) floor(($width - 8) / 4.1));
    }

    private function drawText(string $text, float $x, float $y, float $size, string $font = 'F1', array $rgb = [0, 0, 0]): string
    {
        return sprintf(
            "%.3f %.3f %.3f rg BT /%s %.1f Tf %.1f %.1f Td (%s) Tj ET\n",
            $rgb[0],
            $rgb[1],
            $rgb[2],
            $font,
            $size,
            $x,
            $y,
            $this->escapePdfText($text)
        );
    }

    private function fillRect(float $x, float $y, float $w, float $h, array $rgb): string
    {
        return sprintf("%.3f %.3f %.3f rg %.1f %.1f %.1f %.1f re f\n", $rgb[0], $rgb[1], $rgb[2], $x, $y, $w, $h);
    }

    private function strokeRect(float $x, float $y, float $w, float $h, array $rgb, float $lineWidth = 0.8): string
    {
        return sprintf("%.3f %.3f %.3f RG %.1f w %.1f %.1f %.1f %.1f re S\n", $rgb[0], $rgb[1], $rgb[2], $lineWidth, $x, $y, $w, $h);
    }

    private function line(float $x1, float $y1, float $x2, float $y2, array $rgb, float $lineWidth = 1): string
    {
        return sprintf("%.3f %.3f %.3f RG %.1f w %.1f %.1f m %.1f %.1f l S\n", $rgb[0], $rgb[1], $rgb[2], $lineWidth, $x1, $y1, $x2, $y2);
    }

    private function fitText(string $text, int $width): string
    {
        $text = preg_replace('/\s+/', ' ', trim($text));

        if (mb_strwidth($text) <= $width) {
            return $text;
        }

        return mb_strimwidth($text, 0, max(0, $width - 3), '...');
    }

    private function escapePdfText(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', $text);
        $converted = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $text);

        if ($converted !== false) {
            $text = $converted;
        }

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
