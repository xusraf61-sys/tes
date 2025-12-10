<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\StudySessionController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// RTS routes — faqat auth bo'lganda ishlaydi
Route::middleware('auth')->group(function () {

    // KUNLAR
    Route::get('/days', [DayController::class, 'index'])->name('days.index');
    Route::get('/days/{date}', [DayController::class, 'show'])->name('days.show');
    Route::post('/days/{date}/add-example', [DayController::class, 'addExample'])->name('days.addExample');

    // MISOLLAR
    Route::get('/examples', [ExampleController::class, 'index'])->name('examples.index');

    // TIMER SESSION
    Route::post('/timer/start', [StudySessionController::class, 'start'])->name('timer.start');
    Route::post('/timer/pause', [StudySessionController::class, 'pause'])->name('timer.pause');
    Route::post('/timer/finish', [StudySessionController::class, 'finish'])->name('timer.finish');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
