<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\CostumeController;
use App\Http\Controllers\TutorialController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modules (materi page)
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show');

    // Materials — halaman baca materi (sebelum soal)
    Route::get('/materials/{material}/materi', [MaterialController::class, 'materi'])->name('materials.materi');

    // Materials (level page — daftar soal)
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('materials.show');

    // Questions (soal page)
    Route::get('/questions/{question}', [QuestionController::class, 'show'])->name('questions.show');
    Route::post('/questions/{question}/autosave', [QuestionController::class, 'autosave'])->name('questions.autosave');
    Route::post('/questions/{question}/check', [QuestionController::class, 'check'])->name('questions.check');

    // Progress API routes
    Route::get('/progress', [ProgressController::class, 'getProgress'])->name('progress.get');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/leaderboard/data', [LeaderboardController::class, 'getData'])->name('leaderboard.data');

    // Costumes (kostumisasi page)
    Route::get('/costumes', [CostumeController::class, 'index'])->name('costumes.index');
    Route::get('/costumes/create', [CostumeController::class, 'create'])->name('costumes.create');
    Route::post('/costumes', [CostumeController::class, 'store'])->name('costumes.store');
    Route::post('/costumes/{costume}/activate', [CostumeController::class, 'activate'])->name('costumes.activate');
    Route::get('/costumes/check-unlocks', [CostumeController::class, 'checkUnlocks'])->name('costumes.check');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tutorial
    Route::post('/api/tutorial/complete', [TutorialController::class, 'complete'])->name('tutorial.complete');
    Route::post('/api/tutorial/reset', [TutorialController::class, 'reset'])->name('tutorial.reset');
    Route::get('/api/tutorial/status', [TutorialController::class, 'status'])->name('tutorial.status');
});

require __DIR__.'/auth.php';

// Demo route for Toast & Loading System (Development only)
Route::get('/demo/toast', function () {
    return view('demo-toast');
})->name('demo.toast');

// Demo error pages (only in local environment)
if (app()->environment('local')) {
    Route::get('/demo/error/404', function () {
        abort(404);
    });
    
    Route::get('/demo/error/403', function () {
        abort(403, 'Kamu tidak memiliki akses ke fitur admin.');
    });
    
    Route::get('/demo/error/500', function () {
        abort(500);
    });
    
    Route::get('/demo/error/503', function () {
        abort(503, 'Maintenance dijadwalkan hingga pukul 10:00 WIB.');
    });
}
