<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\PauseController;
use App\Http\Controllers\SnoozeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserManagementController;

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

// Guest Routes for Admin Registration (only for the first admin).
Route::middleware(['guest', 'check.admin'])->group(function () {
    Route::get('/admin/register', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/register', [AdminController::class, 'store'])->name('admin.store');
});

Route::middleware('no.admin')->group(function () {
    Route::get('/', function () {
        return view('home');
    });
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    
    require __DIR__.'/auth.php';
    
    // Authenticated Routes for Verified Admin and User.
    Route::middleware('auth', 'verified')->group(function () {
        Route::post('/shift', [ShiftController::class, 'create'])->name('shifts.create');
        Route::patch('/shift/{id}/update', [ShiftController::class, 'update'])->name('shifts.update');
        Route::get('/current-shift', [ShiftController::class, 'getCurrentShift']);
        // Pauses routes
        Route::post('/pause/shift/{shiftId}', [PauseController::class, 'create'])->name('pauses.create');
        Route::patch('/pause/{id}/update', [PauseController::class, 'update'])->name('pauses.update');
        // Snoozes routes
        Route::post('/snooze/shift/{shiftId}', [SnoozeController::class, 'create'])->name('snoozes.create');
        Route::patch('/snooze/{id}/update', [SnoozeController::class, 'update'])->name('snoozes.update');
        
        // User Registration and Admin Only Routes.
        Route::middleware('is.admin')->group(function () {
            Route::get('admin/user/register', [UserManagementController::class, 'create'])->name('admin.user.create');
            Route::post('admin/user/register', [UserManagementController::class, 'store'])->name('admin.user.store');
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
});
