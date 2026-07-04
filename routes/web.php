<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LetterSubmissionController;
use App\Http\Controllers\AdminLetterController;
use App\Http\Controllers\LetterTypeController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('submissions', LetterSubmissionController::class)
        ->except(['edit', 'update']);

    Route::get('/report', [ReportController::class, 'index'])->name('report.index');

    Route::get('/manual', function () {
        return view('manual');
    })->name('manual');

    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read-all');
    Route::get('/notifications/data', function () {
        $user = auth()->user();
        $notifs = $user->notifications()->latest()->take(10)->get();
        $unread = $user->unreadNotifications->count();
        $html = '';
        foreach ($notifs as $notif) {
            $subId = $notif->data['submission_id'] ?? null;
            $url = $subId ? route('submissions.show', $subId) : '#';
            $isUnread = !$notif->read_at;
            $html .= '<a href="'.$url.'" class="block px-4 py-3 border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition" style="'.($isUnread ? 'background:rgb(239 246 255 / 0.5)' : '').'">';
            $html .= '<div class="flex items-start gap-2">';
            $html .= '<span class="w-1.5 h-1.5 rounded-full mt-1.5 shrink-0" style="background:'.($isUnread ? '#3b82f6' : '#d1d5db').'"></span>';
            $html .= '<div class="min-w-0"><p class="text-xs text-gray-700 leading-relaxed">'.e($notif->data['message'] ?? '').'</p>';
            $html .= '<p class="text-[10px] text-gray-400 mt-0.5">'.$notif->created_at->diffForHumans().'</p></div></div></a>';
        }
        if (!$notifs->count()) {
            $html = '<div class="px-4 py-6 text-center text-xs text-gray-400">Tidak ada notifikasi</div>';
        }
        return response()->json(['unread' => $unread, 'html' => $html]);
    })->name('notifications.data');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/submissions', [AdminLetterController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [AdminLetterController::class, 'show'])->name('submissions.show');

    Route::resource('letter-types', LetterTypeController::class)
        ->except(['show']);

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
});

require __DIR__.'/auth.php';
