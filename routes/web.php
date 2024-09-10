<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ReportController;

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
    return view('home');
});

// Time tracker routes
Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
Route::get('user/{id}/shifts', [ShiftController::class, 'index'])->name('shifts.user');
Route::post('/shift', [ShiftController::class, 'create'])->name('shifts.create');
Route::put('/shift/{id}/update', [ShiftController::class, 'update'])->name('shifts.update');
Route::get('/shift/{id}', [ShiftController::class, 'show'])->name('shifts.show');
Route::get('/shift/{id}/edit', [ShiftController::class, 'edit'])->name('shifts.edit');
// Pauses routes
Route::post('/pause', [PauseController::class, 'create'])->name('pauses.create');
Route::put('/pause/{id}/update', [PauseController::class, 'update'])->name('pauses.update');
// Snoozes routes
Route::post('/snooze', [SnoozeController::class, 'create'])->name('snoozes.create');
Route::put('/snooze/{id}/update', [SnoozeController::class, 'update'])->name('snoozes.update');
// Reports routes
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('user/{id}/reports', [ReportController::class, 'index'])->name('reports.user');
