<?php

namespace App\Http\Controllers;

use App\Models\LetterSubmission;
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
}
