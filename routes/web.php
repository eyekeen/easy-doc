<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MethodologistController;
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
})->name('home');

Route::get('/student/dashboard', [StudentController::class, 'index'])
    ->middleware(['auth'])->name('student.dashboard');

Route::get('/student/send', [StudentController::class, 'show'])->middleware(['auth'])->name('student.send');
Route::post('/student/send', [StudentController::class, 'store'])->middleware(['auth'])->name('student.store');


Route::get('/methodologist/dashboard', [MethodologistController::class, 'index'])->middleware(['auth'])->name('methodologist.dashboard');
Route::put('/methodologist/{pid}', [MethodologistController::class, 'update'])->middleware(['auth'])->name('methodologist.update');
Route::put('/methodologist/reject/{pid}', [MethodologistController::class, 'reject'])->middleware(['auth'])->name('methodologist.reject');


Route::get('/department/dashboard', [DepartmentController::class, 'index'])->middleware(['auth'])->name('department.dashboard');

Route::post('/department/electronickey', [DepartmentController::class, 'electronicKey'])->middleware(['auth'])->name('department.electronicKey');
Route::put('/department/reject/{pid}', [DepartmentController::class, 'reject'])->middleware(['auth'])->name('department.reject');
Route::post('/department/{pid}', [DepartmentController::class, 'update'])->middleware(['auth'])->name('department.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/upload', [UploadController::class, 'index'])->name('upload.index')->middleware('auth');
Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');

require __DIR__ . '/auth.php';
