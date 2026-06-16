<?php

namespace App\Http\Controllers;

use App\Models\LetterSubmission;
use App\Models\LetterType;
use Illuminate\Http\Request;

class LetterSubmissionController extends Controller
{
    public function index()
    {
        $submissions = LetterSubmission::with('letterType')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
        return view('submissions.index', compact('submissions'));
    }

    public function create()
    {
        $letterTypes = LetterType::all();
        return view('submissions.create', compact('letterTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'letter_type_id' => 'required|exists:letter_types,id',
            'keperluan' => 'required|string|max:1000',
            'file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = $request->file('file')->store('submissions', 'public');

        LetterSubmission::create([
            'user_id' => auth()->id(),
            'letter_type_id' => $request->letter_type_id,
            'keperluan' => $request->keperluan,
            'file_path' => $filePath,
        ]);

        return redirect()->route('submissions.index')->with('success', 'Pengajuan surat berhasil dikirim.');
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
        if ($submission->user_id !== auth()->id()) {
            abort(403);
        }
        if ($submission->status !== 'pending') {
            return back()->with('error', 'Tidak dapat menghapus pengajuan yang sudah diproses.');
        }
        $submission->delete();
        return redirect()->route('submissions.index')->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function resubmit(Request $request, LetterSubmission $submission)
    {
        if ($submission->user_id !== auth()->id()) {
            abort(403);
        }
        if ($submission->status !== 'rejected') {
            return back()->with('error', 'Hanya pengajuan yang ditolak yang bisa diajukan ulang.');
        }

        $request->validate([
            'letter_type_id' => 'required|exists:letter_types,id',
            'keperluan' => 'required|string|max:1000',
            'file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = $request->file('file')->store('submissions', 'public');

        $submission->update([
            'letter_type_id' => $request->letter_type_id,
            'keperluan' => $request->keperluan,
            'file_path' => $filePath,
            'status' => 'pending',
            'alasan_penolakan' => null,
            'rejected_at' => null,
        ]);

        return redirect()->route('submissions.index')->with('success', 'Pengajuan ulang berhasil dikirim.');
    }
}
