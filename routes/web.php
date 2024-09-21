<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RegisterAdminController;
use App\Http\Controllers\RegisterUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('no.admin');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Guest Routes for Admin Registration (only for the first admin).
Route::middleware(['guest', 'check.admin'])->group(function () {
    Route::get('/register-admin', [RegisterAdminController::class, 'create'])->name('register-admin');
    Route::post('/register-admin', [RegisterAdminController::class, 'store']);
});

// Authenticated Routes for Verified Admin and User.
Route::middleware('auth', 'verified')->group(function () {
    Route::post('/shift', [ShiftController::class, 'create'])->name('shifts.create');
    Route::put('/shift/{id}/update', [ShiftController::class, 'update'])->name('shifts.update');
    // Pauses routes
    Route::post('/pause', [PauseController::class, 'create'])->name('pauses.create');
    Route::put('/pause/{id}/update', [PauseController::class, 'update'])->name('pauses.update');
    // Snoozes routes
    Route::post('/snooze', [SnoozeController::class, 'create'])->name('snoozes.create');
    Route::put('/snooze/{id}/update', [SnoozeController::class, 'update'])->name('snoozes.update');
    
    // User Registration and Admin Only Routes.
    Route::middleware('is.admin')->group(function () {
        Route::get('/register-user', [RegisterUserController::class, 'create'])->name('register-user');
        Route::post('/register-user', [RegisterUserController::class, 'store']);
        // Time tracker routes.
        Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
        Route::get('user/{id}/shifts', [ShiftController::class, 'index'])->name('shifts.user');
        Route::get('/shift/{id}', [ShiftController::class, 'show'])->name('shifts.show');
        Route::get('/shift/{id}/edit', [ShiftController::class, 'edit'])->name('shifts.edit');
        // Reports routes.
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('user/{id}/reports', [ReportController::class, 'index'])->name('reports.user');
    });
});
