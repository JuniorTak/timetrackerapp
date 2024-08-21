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
    return view('welcome');
});

// Time tracker routes
Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
Route::get('user/{id}/shifts', [ShiftController::class, 'index'])->name('shifts.user');
Route::post('/shift', [ShiftController::class, 'create'])->name('shifts.create');
Route::get('/shift/{id}', [ShiftController::class, 'show'])->name('shifts.show');
Route::get('/shift/{id}/edit', [ShiftController::class, 'edit'])->name('shifts.edit');
Route::put('/shift/{id}/update', [ShiftController::class, 'update'])->name('shifts.update');
Route::get('/reports', [ReportController::class, 'show'])->name('reports.show');
