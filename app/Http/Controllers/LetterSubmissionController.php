<?php

namespace App\Http\Controllers;

use App\Models\LetterGlobalSequence;
use App\Models\LetterSubmission;
use App\Models\LetterType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LetterSubmissionController extends Controller
{
    private function generateLetterNumber(string $numberFormat, Carbon $date): string
    {
        return DB::transaction(function () use ($numberFormat, $date) {
            $month = (int) $date->format('n');
            $year = (int) $date->format('Y');

            $sequence = LetterGlobalSequence::where('month', $month)
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                $sequence = LetterGlobalSequence::create([
                    'month' => $month,
                    'year' => $year,
                    'last_number' => 0,
                ]);
            }

            $sequence->last_number = $sequence->last_number + 1;
            $sequence->save();

            $number = str_pad((string) $sequence->last_number, 3, '0', STR_PAD_LEFT);
            $format = trim($numberFormat, " /\t\n\r\0\x0B");

            return "{$number}/{$format}";
        });
    }

    private function ensureBackdatedQuotaAvailable(Carbon $date): void
    {
        if ($date->isToday() || $date->greaterThan(today())) {
            return;
        }

        $used = LetterSubmission::whereDate('submission_date', $date->toDateString())
            ->whereDate('created_at', '>', $date->toDateString())
            ->count();

        if ($used >= 5) {
            throw ValidationException::withMessages([
                'submission_date' => 'Kuota sisipan nomor mundur untuk tanggal '.$date->format('d/m/Y').' sudah penuh. Maksimal 5 nomor per hari.',
            ]);
        }
    }

    private function bidangOptions(): array
    {
        return [
            'PELAYANAN PENDAFTARAN PENDUDUK',
            'PELAYANAN PENCATATAN SIPIL',
            'PIAK',
            'SEKRETARIATAN',
        ];
    }

    private function letterTypeOrder(): array
    {
        return [
            'Surat Tugas & SPPD',
            'Surat Tugas',
            'Nota Dinas',
            'Naskah Dinas Korespondensi Internal',
            'Naskah Dinas Korespondensi Eksternal',
            'Naskah Dinas Khusus',
            'Naskah Dinas Surat',
        ];
    }

    public function index()
    {
        $submissions = LetterSubmission::with(['user', 'letterType'])
            ->latest()
            ->paginate(10);

        return view('submissions.index', compact('submissions'));
    }

    public function create()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.submissions.index')
                ->with('error', 'Admin hanya mengelola surat yang sudah diajukan dan tidak membuat nomor surat.');
        }

        $order = $this->letterTypeOrder();
        $bidangs = $this->bidangOptions();
        $letterTypes = LetterType::where('is_active', true)
            ->whereIn('name', $order)
            ->whereIn('bidang', $bidangs)
            ->get()
            ->sortBy(fn ($type) => array_search($type->name, $order, true))
            ->values();

        return view('submissions.create', compact('letterTypes', 'bidangs'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.submissions.index')
                ->with('error', 'Admin hanya mengelola surat yang sudah diajukan dan tidak membuat nomor surat.');
        }

        $request->validate([
            'bidang' => ['required', Rule::in($this->bidangOptions())],
            'letter_type_id' => 'required|exists:letter_types,id',
            'number_format' => ['required', 'string', 'size:16', 'regex:/^\d{3}\/\d{3}\/\d{2}\.\d\.\d\.\d$/'],
            'pengolah' => 'required|string|max:255',
            'ditujukan_kepada' => 'required|string|max:255',
            'keperluan' => 'required|string|max:1000',
            'file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'is_sk' => 'boolean',
            'submission_date' => 'nullable|date|before_or_equal:today',
        ], [
            'number_format.size' => 'Format nomor harus mengikuti pola 000/000/00.0.0.0.',
            'number_format.regex' => 'Format nomor harus mengikuti pola 000/000/00.0.0.0. Ganti angka 0 sesuai kebutuhan, pemisah / dan . tidak diubah.',
        ]);

        $letterType = LetterType::findOrFail($request->letter_type_id);
        if ($letterType->bidang !== $request->bidang) {
            throw ValidationException::withMessages([
                'letter_type_id' => 'Jenis surat tidak sesuai dengan bidang yang dipilih.',
            ]);
        }

        $date = ($request->boolean('is_sk') && $request->submission_date)
            ? Carbon::parse($request->submission_date)
            : now();

        $this->ensureBackdatedQuotaAvailable($date);
        $letterNumber = $this->generateLetterNumber($request->number_format, $date);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        LetterSubmission::create([
            'user_id' => auth()->id(),
            'letter_type_id' => $letterType->id,
            'keperluan' => $request->keperluan,
            'pengolah' => $request->pengolah,
            'ditujukan_kepada' => $request->ditujukan_kepada,
            'number_format' => $request->number_format,
            'file_path' => $filePath,
            'status' => 'approved',
            'letter_number' => $letterNumber,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'is_sk' => $request->boolean('is_sk'),
            'submission_date' => $date,
        ]);

        return redirect()->route('submissions.index')
            ->with('success', "Surat berhasil dibuat. Nomor surat: {$letterNumber}");
    }

    public function show(LetterSubmission $submission)
    {
        $submission->loadMissing(['user', 'letterType']);

        return view('submissions.show', compact('submission'));
    }

    public function destroy(LetterSubmission $submission)
    {
        if ($submission->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $submission->delete();

        return redirect()->route('submissions.index')->with('success', 'Surat berhasil dihapus.');
    }
}
