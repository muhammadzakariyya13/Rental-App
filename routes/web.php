<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PropertiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\PropertiController as AdminPropertiController;
use App\Http\Controllers\Admin\PemesananController as AdminPemesananController;
use App\Http\Controllers\Admin\KontrakController as AdminKontrakController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\AkunController as AdminAkunController;

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

// Group untuk User (harus login)
Route::middleware(['auth'])->group(function () {
    // Melihat pemesanan & kontrak sendiri
    Route::get('/pemesanan/saya', [UserController::class, 'pemesananSaya'])->name('user.pemesanan');
    Route::get('/kontrak/saya', [UserController::class, 'kontrakSaya'])->name('user.kontrak');

    // Melakukan Review
    Route::get('/properti/{id}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/properti/{id}/review', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Group untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Contoh rute admin, nanti bisa dipecah ke controller terpisah
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Properti: view + create/edit
    Route::get('/properti', [AdminPropertiController::class, 'index'])->name('properti.index');
    Route::get('/properti/create', [AdminPropertiController::class, 'create'])->name('properti.create');
    Route::post('/properti', [AdminPropertiController::class, 'store'])->name('properti.store');
    Route::get('/properti/{id}/edit', [AdminPropertiController::class, 'edit'])->name('properti.edit');
    Route::put('/properti/{id}', [AdminPropertiController::class, 'update'])->name('properti.update');

    // Pemesanan: view + edit (status)
    Route::get('/pemesanan', [AdminPemesananController::class, 'index'])->name('pemesanan.index');
    Route::get('/pemesanan/{id}/edit', [AdminPemesananController::class, 'edit'])->name('pemesanan.edit');
    Route::put('/pemesanan/{id}', [AdminPemesananController::class, 'update'])->name('pemesanan.update');

    // Kontrak: view + detail
    Route::get('/kontrak', [AdminKontrakController::class, 'index'])->name('kontrak.index');
    Route::get('/kontrak/{id}', [AdminKontrakController::class, 'show'])->name('kontrak.show');

    // Review: view + delete
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Akun: view + edit/update
    Route::get('/akun', [AdminAkunController::class, 'index'])->name('akun.index');
    Route::get('/akun/{id}/edit', [AdminAkunController::class, 'edit'])->name('akun.edit');
    Route::put('/akun/{id}', [AdminAkunController::class, 'update'])->name('akun.update');
});