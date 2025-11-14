<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboardController;
use App\Http\Controllers\Pemilik\PropertiController;
use App\Http\Controllers\Pemilik\PemesananController;
use App\Http\Controllers\Pemilik\ReviewController;
use App\Http\Controllers\Pemilik\BookingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Ganti route ini untuk redirect ke login jika belum auth
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// Default dashboard route that redirects to appropriate role-specific dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/users', function() { return view('admin.users'); })->name('admin.users');
    Route::get('/roles', function() { return view('admin.roles'); })->name('admin.roles');
});

// Pemilik Routes
Route::middleware(['auth'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', [PemilikDashboardController::class, 'index'])->name('dashboard');
    
    // Properti routes
    Route::get('/properti', [PropertiController::class, 'index'])->name('properti');
    Route::get('/properti/create', [PropertiController::class, 'create'])->name('properti.create');
    Route::post('/properti', [PropertiController::class, 'store'])->name('properti.store');
    Route::get('/properti/{id}/edit', [PropertiController::class, 'edit'])->name('properti.edit');
    Route::put('/properti/{id}', [PropertiController::class, 'update'])->name('properti.update');
    Route::delete('/properti/{id}', [PropertiController::class, 'destroy'])->name('properti.destroy');
    Route::delete('/properti/bulk-delete', [PropertiController::class, 'bulkDelete'])->name('properti.bulk-delete');
    Route::get('/properti/{id}', [PropertiController::class, 'show'])->name('properti.show');
    
    // IMAGE ROUTES
    Route::delete('/properti/{properti}/images/{image}', [PropertiController::class, 'deleteImage'])->name('properti.image.delete');
    Route::post('/properti/{properti}/images/{image}/primary', [PropertiController::class, 'setPrimaryImage'])->name('properti.image.primary');
    
    // REVIEW ROUTES
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{id}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
    Route::post('/reviews/{id}/approval', [ReviewController::class, 'updateApproval'])->name('reviews.approval');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/bulk-action', [ReviewController::class, 'bulkAction'])->name('reviews.bulkAction');
        
    // Booking routes untuk pemilik
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::post('/bookings/bulk-update', [BookingController::class, 'bulkUpdateStatus'])->name('bookings.bulkUpdate');
    Route::get('/bookings/export', [BookingController::class, 'exportExcel'])->name('bookings.export');
    Route::get('/bookings/stats', [BookingController::class, 'getStats'])->name('bookings.stats');
    
    // Pemesanan routes
    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('pemesanan');
    
    // TAMBAH INI - Chart data untuk analytics
    Route::get('/chart-data', [PemesananController::class, 'chartData'])->name('chart.data');
});

// Penyewa (Tenant) routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':penyewa'])->prefix('penyewa')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'penyewaDashboard'])->name('penyewa.dashboard');
    Route::get('/browse', function() { return view('penyewa.browse'); })->name('penyewa.browse');
    Route::get('/pemesanan', function() { return view('penyewa.pemesanan'); })->name('penyewa.pemesanan');
    Route::get('/review', function() { return view('penyewa.review'); })->name('penyewa.review');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// TAMBAH TEST LOGIN ROUTES - HAPUS SETELAH SELESAI TEST
Route::get('/test-login-pemilik', function() {
    $user = \App\Models\Akun::find(2); // User pemilik
    auth()->login($user);
    return redirect('/pemilik/pemesanan')->with('success', 'Login sebagai: ' . $user->username);
});

Route::get('/test-login-admin', function() {
    $user = \App\Models\Akun::find(1); // User admin  
    auth()->login($user);
    return redirect('/dashboard')->with('success', 'Login sebagai: ' . $user->username);
});

require __DIR__.'/auth.php';