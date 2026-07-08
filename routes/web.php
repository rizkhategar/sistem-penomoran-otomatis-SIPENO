<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LetterSubmissionController;
use App\Http\Controllers\AdminLetterController;
use App\Http\Controllers\LetterTypeController;
use App\Http\Controllers\MasterBidangController;
use App\Http\Controllers\MasterJenisSuratController;
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
    Route::get('/report/pdf', [ReportController::class, 'pdf'])->name('report.pdf');

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

        return response()->json([
            'unread' => $user->unreadNotifications->count(),
            'items' => $notifs->map(fn ($notif) => [
                'message' => $notif->data['message'] ?? '',
                'created_at' => $notif->created_at->diffForHumans(),
                'is_unread' => $notif->read_at === null,
            ]),
        ]);
    })->name('notifications.data');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/submissions', [AdminLetterController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [AdminLetterController::class, 'show'])->name('submissions.show');
    Route::delete('/submissions/{submission}', [AdminLetterController::class, 'destroy'])->name('submissions.destroy');

    Route::resource('master-bidangs', MasterBidangController::class)
        ->except(['show']);
    Route::resource('master-jenis-surats', MasterJenisSuratController::class)
        ->except(['show']);

    Route::resource('letter-types', LetterTypeController::class)
        ->except(['show']);

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
});

require __DIR__.'/auth.php';
