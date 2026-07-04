<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterSubmission;
use App\Models\LetterType;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $submissions = LetterSubmission::with(['user', 'letterType'])
                ->latest()
                ->paginate(10);

            $stats = [
                'total' => LetterSubmission::count(),
                'approved' => LetterSubmission::where('status', 'approved')->count(),
            ];

            $perBidang = LetterSubmission::selectRaw('letter_type_id, COUNT(*) as total')
                ->groupBy('letter_type_id')
                ->with('letterType')
                ->get()
                ->groupBy(fn($s) => $s->letterType->bidang ?? 'UMUM');

            $bidangStats = [];
            foreach ($perBidang as $bidang => $items) {
                $bidangStats[$bidang] = $items->sum('total');
            }

            $monthly = LetterSubmission::selectRaw("MONTH(created_at) as bulan, COUNT(*) as total")
                ->whereYear('created_at', now()->year)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->pluck('total', 'bulan');
            $monthlyLabels = [];
            $monthlyData = [];
            $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyLabels[] = $months[$i - 1];
                $monthlyData[] = $monthly[$i] ?? 0;
            }

            $perType = LetterSubmission::selectRaw('letter_type_id, COUNT(*) as total')
                ->groupBy('letter_type_id')
                ->with('letterType')
                ->get();
            $typeLabels = $perType->map(fn($s) => $s->letterType->name ?? 'Unknown');
            $typeData = $perType->pluck('total');

            $bidangs = User::whereNotNull('bidang')->distinct('bidang')->pluck('bidang');

            return view('dashboard', compact(
                'submissions', 'stats',
                'monthlyLabels', 'monthlyData',
                'typeLabels', 'typeData',
                'bidangStats', 'bidangs'
            ));
        }

        $bidang = auth()->user()->bidang;
        $submissions = LetterSubmission::with('letterType')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        $bidangTotal = LetterSubmission::whereHas('letterType', fn($q) => $q->where('bidang', $bidang))
            ->count();

        return view('dashboard', compact('submissions', 'bidang', 'bidangTotal'));
    }
}
