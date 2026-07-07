<?php

namespace App\Http\Controllers;

use App\Models\LetterSubmission;

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
        $submission->loadMissing(['user', 'letterType']);

        return view('admin.submissions.show', compact('submission'));
    }

    public function destroy(LetterSubmission $submission)
    {
        $submission->delete();

        return redirect()->route('admin.submissions.index')
            ->with('success', 'Surat berhasil dihapus oleh admin.');
    }
}
