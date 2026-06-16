<?php

namespace App\Http\Controllers;

use App\Models\LetterSubmission;
use App\Models\LetterType;
use App\Notifications\SubmissionApproved;
use App\Notifications\SubmissionRejected;
use Illuminate\Http\Request;

class AdminLetterController extends Controller
{
    public function index()
    {
        $submissions = LetterSubmission::with(['user', 'letterType'])
            ->latest()
            ->paginate(15);
        return view('admin.submissions.index', compact('submissions'));
    }

    public function show(LetterSubmission $submission)
    {
        return view('admin.submissions.show', compact('submission'));
    }

    public function approve(Request $request, $id)
    {
        $submission = LetterSubmission::findOrFail($id);

        if ($submission->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        $request->validate([
            'letter_number' => 'required|string|max:255',
        ]);

        $submission->update([
            'status' => 'approved',
            'letter_number' => $request->letter_number,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $submission->user->notify(new SubmissionApproved($submission));

        return redirect()->route('admin.submissions.index')->with('success', 'Pengajuan disetujui. Nomor surat: ' . $submission->letter_number);
    }

    public function reject(Request $request, $id)
    {
        $submission = LetterSubmission::findOrFail($id);

        if ($submission->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ]);

        $submission->update([
            'status' => 'rejected',
            'alasan_penolakan' => $request->alasan_penolakan,
            'rejected_at' => now(),
        ]);

        $submission->user->notify(new SubmissionRejected($submission));

        return redirect()->route('admin.submissions.index')->with('success', 'Pengajuan ditolak.');
    }
}
