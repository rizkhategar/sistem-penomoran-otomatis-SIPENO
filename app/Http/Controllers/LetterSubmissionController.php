<?php

namespace App\Http\Controllers;

use App\Models\LetterSubmission;
use App\Models\LetterType;
use App\Models\LetterNumberSequence;
use Illuminate\Http\Request;

class LetterSubmissionController extends Controller
{
    private function romanMonth($month)
    {
        $romans = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $romans[(int)$month] ?? '';
    }

    private function generateLetterNumber($letterType, $bidang, $date)
    {
        $month = $date->format('n');
        $year = $date->format('Y');

        $seq = LetterNumberSequence::firstOrCreate(
            [
                'letter_type_id' => $letterType->id,
                'bidang' => $bidang,
                'month' => $month,
                'year' => $year,
            ],
            ['last_number' => 0]
        );

        $seq->increment('last_number');
        $num = str_pad($seq->last_number, 3, '0', STR_PAD_LEFT);
        $roman = $this->romanMonth($month);

        return "{$num}/{$letterType->code}/{$bidang}/{$roman}/{$year}";
    }

    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $submissions = LetterSubmission::with(['user', 'letterType'])
                ->latest()->paginate(15);
        } else {
            $submissions = LetterSubmission::with('letterType')
                ->where('user_id', auth()->id())
                ->latest()->paginate(10);
        }
        return view('submissions.index', compact('submissions'));
    }

    public function create()
    {
        $query = LetterType::where('is_active', true);
        if (!auth()->user()->isAdmin() && auth()->user()->bidang) {
            $query->where('bidang', auth()->user()->bidang);
        }
        $letterTypes = $query->get();
        return view('submissions.create', compact('letterTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'letter_type_id' => 'required|exists:letter_types,id',
            'keperluan' => 'required|string|max:1000',
            'file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'is_sk' => 'boolean',
            'submission_date' => 'nullable|date',
        ]);

        $letterType = LetterType::findOrFail($request->letter_type_id);
        $bidang = auth()->user()->bidang ?? 'UMUM';

        $date = $request->submission_date ? \Carbon\Carbon::parse($request->submission_date) : now();
        $letterNumber = $this->generateLetterNumber($letterType, $bidang, $date);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        LetterSubmission::create([
            'user_id' => auth()->id(),
            'letter_type_id' => $request->letter_type_id,
            'keperluan' => $request->keperluan,
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
        if ($submission->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
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
