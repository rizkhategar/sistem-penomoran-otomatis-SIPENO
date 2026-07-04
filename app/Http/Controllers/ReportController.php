<?php

namespace App\Http\Controllers;

use App\Models\LetterSubmission;
use App\Models\LetterType;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('n');
        $tahun = $request->tahun ?? now()->format('Y');
        $bidang = $request->bidang;

        $query = LetterSubmission::with(['user', 'letterType'])
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan);

        if ($bidang) {
            $query->whereHas('letterType', fn($q) => $q->where('bidang', $bidang));
        } elseif (!auth()->user()->isAdmin()) {
            $query->whereHas('letterType', fn($q) => $q->where('bidang', auth()->user()->bidang));
        }

        $submissions = $query->latest()->paginate(20);

        $total = $submissions->total();
        $perJenis = LetterSubmission::selectRaw('letter_type_id, COUNT(*) as total')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->groupBy('letter_type_id')
            ->with('letterType')
            ->get();

        if ($bidang) {
            $perJenis = $perJenis->filter(fn($s) => $s->letterType->bidang == $bidang);
        } elseif (!auth()->user()->isAdmin()) {
            $perJenis = $perJenis->filter(fn($s) => $s->letterType->bidang == auth()->user()->bidang);
        }

        $bidangs = User::whereNotNull('bidang')->distinct('bidang')->pluck('bidang');
        $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $tahuns = range(now()->year - 2, now()->year);

        return view('report.index', compact(
            'submissions', 'total', 'perJenis',
            'bulan', 'tahun', 'bidang',
            'bidangs', 'months', 'tahuns'
        ));
    }
}
