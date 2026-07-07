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

        $bidangs = collect(['UMUM', 'PERENCANAAN', 'SEKRETARIATAN'])
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
        $lines = [
            'LAPORAN SURAT BULANAN',
            'Periode : '.$months[$bulan - 1].' '.$tahun,
            'Bidang  : '.($bidang ?: 'Semua Bidang'),
            'Total   : '.$submissions->count().' surat',
            '',
            'Rekap per jenis surat:',
        ];

        if ($perJenis->isEmpty()) {
            $lines[] = '- Tidak ada data';
        } else {
            foreach ($perJenis as $item) {
                $lines[] = '- '.($item->letterType->name ?? 'Jenis tidak ditemukan').' ('.($item->letterType->bidang ?? '-').'): '.$item->total;
            }
        }

        $lines[] = '';
        $lines[] = str_repeat('-', 150);
        $lines[] = $this->rowLine(['No', 'Tanggal', 'Nomor Surat', 'Pembuat', 'Bidang', 'Jenis', 'Pengolah', 'Ditujukan Kepada']);
        $lines[] = str_repeat('-', 150);

        foreach ($submissions as $index => $submission) {
            $date = $submission->submission_date ?: $submission->created_at;
            $lines[] = $this->rowLine([
                (string) ($index + 1),
                $date?->format('d/m/Y') ?? '-',
                $submission->letter_number,
                $submission->user->name ?? '-',
                $submission->letterType->bidang ?? '-',
                $submission->letterType->name ?? '-',
                $submission->pengolah ?? '-',
                $submission->ditujukan_kepada ?? '-',
            ]);
        }

        if ($submissions->isEmpty()) {
            $lines[] = 'Tidak ada data surat pada periode ini.';
        }

        return $this->makePdf($this->chunkLinesForPdf($lines));
    }

    private function rowLine(array $values): string
    {
        $widths = [4, 11, 28, 18, 16, 18, 18, 25];
        $line = '';

        foreach ($values as $index => $value) {
            $line .= str_pad($this->fitText((string) $value, $widths[$index]), $widths[$index]).' ';
        }

        return rtrim($line);
    }

    private function fitText(string $text, int $width): string
    {
        $text = preg_replace('/\s+/', ' ', trim($text));

        if (mb_strwidth($text) <= $width) {
            return $text;
        }

        return mb_strimwidth($text, 0, max(0, $width - 3), '...');
    }

    private function chunkLinesForPdf(array $lines): array
    {
        $pages = [];
        $current = [];
        $maxLines = 42;

        foreach ($lines as $line) {
            $current[] = $line;

            if (count($current) >= $maxLines) {
                $pages[] = $current;
                $current = [];
            }
        }

        if ($current !== []) {
            $pages[] = $current;
        }

        return $pages ?: [[]];
    }

    private function makePdf(array $pages): string
    {
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            2 => '',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>',
        ];

        $kids = [];
        $nextObject = 4;
        $pageWidth = 842;
        $pageHeight = 595;

        foreach ($pages as $pageIndex => $lines) {
            $contentObject = $nextObject++;
            $pageObject = $nextObject++;
            $kids[] = $pageObject.' 0 R';
            $stream = $this->pageStream($lines, $pageIndex + 1, count($pages));

            $objects[$contentObject] = "<< /Length ".strlen($stream)." >>\nstream\n{$stream}\nendstream";
            $objects[$pageObject] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 {$pageWidth} {$pageHeight}] /Resources << /Font << /F1 3 0 R >> >> /Contents {$contentObject} 0 R >>";
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

    private function pageStream(array $lines, int $page, int $totalPages): string
    {
        $stream = '';
        $x = 36;
        $y = 555;
        $fontSize = 7;
        $lineHeight = 12;

        foreach ($lines as $line) {
            $stream .= sprintf(
                "BT /F1 %.1f Tf %.1f %.1f Td (%s) Tj ET\n",
                $fontSize,
                $x,
                $y,
                $this->escapePdfText($line)
            );
            $y -= $lineHeight;
        }

        $stream .= sprintf(
            "BT /F1 7 Tf 730 24 Td (%s) Tj ET\n",
            $this->escapePdfText('Halaman '.$page.' dari '.$totalPages)
        );

        return $stream;
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
