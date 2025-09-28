<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

// Pemilik (Owner) routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':pemilik'])->prefix('pemilik')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pemilikDashboard'])->name('pemilik.dashboard');
    Route::get('/properti', function() { return view('pemilik.properti'); })->name('pemilik.properti');
    Route::get('/pemesanan', function() { return view('pemilik.pemesanan'); })->name('pemilik.pemesanan');
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

require __DIR__.'/auth.php';
