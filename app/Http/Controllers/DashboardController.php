<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterSubmission;
use App\Models\LetterType;
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
                'pending' => LetterSubmission::where('status', 'pending')->count(),
                'approved' => LetterSubmission::where('status', 'approved')->count(),
                'rejected' => LetterSubmission::where('status', 'rejected')->count(),
            ];

            // Monthly chart data (current year)
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

            // Per letter type chart data
            $perType = LetterSubmission::selectRaw('letter_type_id, COUNT(*) as total')
                ->groupBy('letter_type_id')
                ->with('letterType')
                ->get();
            $typeLabels = $perType->map(fn($s) => $s->letterType->name ?? 'Unknown');
            $typeData = $perType->pluck('total');

            return view('dashboard', compact(
                'submissions', 'stats',
                'monthlyLabels', 'monthlyData',
                'typeLabels', 'typeData'
            ));
        }

        $submissions = LetterSubmission::with('letterType')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('dashboard', compact('submissions'));
    }
}
