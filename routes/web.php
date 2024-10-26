<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UploadController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/stundet/dashboard', [StudentController::class, 'index'])
        ->middleware(['auth'])->name('student.dashboard');

Route::get('/stundet/send', [StudentController::class, 'show'])->middleware(['auth'])->name('student.send');
Route::post('/stundet/send', [StudentController::class, 'store'])->middleware(['auth'])->name('student.store');


Route::get('/methodologist/dashboard', function () {
    return Inertia::render('Methodologist/Dashboard');
})->middleware(['auth'])->name('methodologist.dashboard');


Route::get('/department/dashboard', function () {
    return Inertia::render('Department/Dashboard');
})->middleware(['auth'])->name('department.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/upload', [UploadController::class, 'index'])->name('upload.index')->middleware('auth');
Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');

require __DIR__ . '/auth.php';
