<?php

namespace App\Http\Controllers;

use App\Models\LetterDailySequence;
use App\Models\LetterSubmission;
use App\Models\LetterType;
use App\Models\MasterBidang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LetterSubmissionController extends Controller
{
    private const DEFAULT_DAILY_INSERTION_LIMIT = 5;

    private function generateLetterNumber(string $numberFormat, Carbon $date, bool $isInsertion, int $dailyInsertionLimit): string
    {
        return DB::transaction(function () use ($numberFormat, $date, $isInsertion, $dailyInsertionLimit) {
            $sequence = $this->getOrCreateDailySequence($date, $dailyInsertionLimit);

            if ($isInsertion) {
                if ($sequence->insertion_used >= $dailyInsertionLimit) {
                    throw ValidationException::withMessages([
                        'submission_date' => 'Kuota sisipan nomor untuk tanggal '.$date->format('d/m/Y').' sudah penuh. Maksimal '.$dailyInsertionLimit.' nomor per hari.',
                    ]);
                }

                $sequence->insertion_used = $sequence->insertion_used + 1;
                $sequence->save();

                $nextNumber = $sequence->last_regular_number + $sequence->insertion_used;
            } else {
                $sequence->last_regular_number = $sequence->last_regular_number + 1;
                $sequence->save();

                $nextNumber = $sequence->last_regular_number;
            }

            $number = str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            $format = trim($numberFormat, " /\t\n\r\0\x0B");

            return "{$number}/{$format}";
        });
    }

    private function getOrCreateDailySequence(Carbon $date, int $dailyInsertionLimit): LetterDailySequence
    {
        $dateString = $date->toDateString();

        $sequence = LetterDailySequence::whereDate('sequence_date', $dateString)
            ->lockForUpdate()
            ->first();

        if ($sequence) {
            return $sequence;
        }

        $previous = LetterDailySequence::whereDate('sequence_date', '<', $dateString)
            ->orderByDesc('sequence_date')
            ->lockForUpdate()
            ->first();

        $startingNumber = $previous
            ? $previous->last_regular_number + $dailyInsertionLimit
            : 0;

        return LetterDailySequence::create([
            'sequence_date' => $dateString,
            'last_regular_number' => $startingNumber,
            'insertion_used' => 0,
        ]);
    }

    private function bidangOptions(): array
    {
        $bidangs = MasterBidang::where('is_active', true)
            ->orderBy('name')
            ->pluck('name')
            ->values()
            ->all();

        return $bidangs ?: [
            'PELAYANAN PENDAFTARAN PENDUDUK',
            'PELAYANAN PENCATATAN SIPIL',
            'PIAK',
            'SEKRETARIATAN',
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

        $bidangs = $this->bidangOptions();
        $letterTypes = LetterType::with(['masterBidang', 'masterJenisSurat'])
            ->where('is_active', true)
            ->whereIn('bidang', $bidangs)
            ->orderBy('bidang')
            ->orderBy('name')
            ->get();

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
            'number_format' => ['required', 'string', 'max:150', 'regex:/^[0-9A-Za-z.\/\-\s]+$/'],
            'pengolah' => 'required|string|max:255',
            'ditujukan_kepada' => 'required|string|max:255',
            'keperluan' => 'required|string|max:1000',
            'file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'is_sk' => 'boolean',
            'submission_date' => 'nullable|date|before_or_equal:today',
        ], [
            'number_format.regex' => 'Format nomor hanya boleh berisi huruf, angka, spasi, garis miring (/), titik, dan strip.',
        ]);

        $letterType = LetterType::findOrFail($request->letter_type_id);
        if ($letterType->bidang !== $request->bidang) {
            throw ValidationException::withMessages([
                'letter_type_id' => 'Jenis surat tidak sesuai dengan bidang yang dipilih.',
            ]);
        }

        $isInsertion = $request->boolean('is_sk') && $request->filled('submission_date');
        $date = $isInsertion
            ? Carbon::parse($request->submission_date, 'Asia/Jakarta')
            : now('Asia/Jakarta');

        $dailyInsertionLimit = (int) ($letterType->daily_insertion ?: self::DEFAULT_DAILY_INSERTION_LIMIT);
        $letterNumber = $this->generateLetterNumber($request->number_format, $date, $isInsertion, $dailyInsertionLimit);

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
            'approved_at' => now('Asia/Jakarta'),
            'approved_by' => auth()->id(),
            'is_sk' => $isInsertion,
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
