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
    // Ambil 6 properti terbaru untuk ditampilkan sebagai unggulan
    $propertiUnggulan = \App\Models\Properti::latest()->take(6)->get();
    
    return view('welcome', compact('propertiUnggulan'));
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

// Route untuk booking properti (perlu login)
Route::middleware(['auth'])->group(function () {
    Route::get('/properti/{id}/book', [PropertiController::class, 'book'])
        ->name('properti.book');
    Route::post('/properti/{id}/book', [PropertiController::class, 'processBooking'])
        ->name('properti.processBooking');
});

// Route dashboard (hanya untuk user yang sudah login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

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

    // Profile self-service (edit/update)
    Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])
        ->name('profile.update');
});

// Payment routes
use App\Http\Controllers\PaymentController;

Route::middleware(['auth'])->group(function () {
    // Payment pages
    Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::get('/payment/{payment}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payment.receipt');
    Route::get('/payment-history', [PaymentController::class, 'history'])->name('payment.history');
});

// Payment API routes with rate limiting
Route::prefix('api/v1')->middleware(['payment.rate.limit'])->group(function () {
    Route::post('/payments', [PaymentController::class, 'store'])
        ->middleware(['auth'])
        ->name('api.payment.create');
    Route::get('/payments/{payment}', [PaymentController::class, 'status'])
        ->name('api.payment.status');
    Route::post('/payments/{payment}/cancel', [PaymentController::class, 'cancel'])
        ->middleware(['auth'])
        ->name('api.payment.cancel');
});

// Public payment routes (no auth required)
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancelled'])->name('payment.cancel');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])
    ->middleware(['payment.rate.limit'])
    ->withoutMiddleware(['web'])
    ->name('payment.webhook');

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
    Route::delete('/properti/{id}', [AdminPropertiController::class, 'destroy'])->name('properti.destroy');

    // Pemesanan: view + edit (status)
    Route::get('/pemesanan', [AdminPemesananController::class, 'index'])->name('pemesanan.index');
    Route::get('/pemesanan/{id}/edit', [AdminPemesananController::class, 'edit'])->name('pemesanan.edit');
    Route::put('/pemesanan/{id}', [AdminPemesananController::class, 'update'])->name('pemesanan.update');

    // Kontrak: view + detail
    Route::get('/kontrak', [AdminKontrakController::class, 'index'])->name('kontrak.index');
    Route::get('/kontrak/{id}', [AdminKontrakController::class, 'show'])->name('kontrak.show');

    // Review: view + delete
    // Admin tidak lagi diberi akses untuk mengelola/menghapus review di sini.
    // Tampilkan review tetap tersedia pada halaman properti (owner/mitra dapat melihat ulasan di halaman properti).

    // Akun: view + edit/update
    Route::get('/akun', [AdminAkunController::class, 'index'])->name('akun.index');
    Route::get('/akun/{id}/edit', [AdminAkunController::class, 'edit'])->name('akun.edit');
    Route::put('/akun/{id}', [AdminAkunController::class, 'update'])->name('akun.update');
    Route::delete('/akun/{id}', [AdminAkunController::class, 'destroy'])->name('akun.destroy');

    // Payment monitoring for admin
    Route::get('/payments', [PaymentController::class, 'adminIndex'])->name('payments.index');
    Route::get('/payments/{payment}', [PaymentController::class, 'adminShow'])->name('payments.show');
});