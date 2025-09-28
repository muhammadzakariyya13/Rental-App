<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PropertiController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk dashboard admin (tambahkan middleware jika perlu)
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware(['auth', 'verified']) // opsional: batasi hanya user login & verifikasi
    ->name('admin.dashboard');

// Route untuk properti
Route::get('/properti', [PropertiController::class, 'index'])
    ->name('properti.index');
Route::get('/properti/{id}', [PropertiController::class, 'show'])
    ->name('properti.show');

// Route dashboard (hanya untuk user yang sudah login & terverifikasi)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Aktifkan route auth dari Breeze (login, register, dll)
require __DIR__.'/auth.php';