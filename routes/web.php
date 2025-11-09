<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Jika user sudah login, redirect ke dashboard sesuai role
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    
    // Jika belum login, redirect ke halaman login
    return redirect('/login');
});

// Default dashboard route that redirects to appropriate role-specific dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    
    // Use resource controllers
    Route::resource('users', \App\Http\Controllers\Admin\UsersController::class, ['as' => 'admin']);
    Route::resource('roles', \App\Http\Controllers\Admin\RolesController::class, ['as' => 'admin']);
});

// Pemilik (Owner) routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':pemilik'])->prefix('pemilik')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pemilikDashboard'])->name('pemilik.dashboard');
    
    // Property resource routes
    Route::resource('properti', \App\Http\Controllers\Pemilik\PropertiController::class, ['as' => 'pemilik']);
    
    // Booking routes
    Route::get('/pemesanan', function() { return view('pemilik.pemesanan'); })->name('pemilik.pemesanan');
});

// Penyewa (Tenant) routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':penyewa'])->prefix('penyewa')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'penyewaDashboard'])->name('penyewa.dashboard');
    
    // Browse properties
    Route::get('/browse', [\App\Http\Controllers\Penyewa\BrowseController::class, 'index'])->name('penyewa.browse');
    Route::get('/browse/{id}', [\App\Http\Controllers\Penyewa\BrowseController::class, 'show'])->name('penyewa.property.show');
    
    // Booking and payment routes
    Route::get('/pemesanan', [\App\Http\Controllers\Penyewa\BookingController::class, 'index'])->name('penyewa.pemesanan.index');
    Route::get('/pemesanan/{id}', [\App\Http\Controllers\Penyewa\BookingController::class, 'show'])->name('penyewa.pemesanan.show');
    Route::get('/pemesanan/{id}/cancel', [\App\Http\Controllers\Penyewa\BookingController::class, 'cancel'])->name('penyewa.pemesanan.cancel');
    Route::get('/book/{id}', [\App\Http\Controllers\Penyewa\BookingController::class, 'create'])->name('penyewa.pemesanan.create');
    Route::post('/book/{id}', [\App\Http\Controllers\Penyewa\BookingController::class, 'store'])->name('penyewa.pemesanan.store');
    Route::get('/payment/{id}', [\App\Http\Controllers\Penyewa\BookingController::class, 'payment'])->name('penyewa.payment.show');
    Route::post('/payment/{id}', [\App\Http\Controllers\Penyewa\BookingController::class, 'processPayment'])->name('penyewa.payment.process');
    Route::get('/payment/{id}/success', [\App\Http\Controllers\Penyewa\BookingController::class, 'success'])->name('penyewa.pemesanan.success');
    
    Route::get('/review', function() { return view('penyewa.review'); })->name('penyewa.review');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Payment gateway webhook callback routes - No auth required
Route::match(['get', 'post'], '/payment/callback', [\App\Http\Controllers\Penyewa\BookingController::class, 'paymentCallback'])->name('penyewa.payment.callback');
Route::post('/api/webhook/payment', [\App\Http\Controllers\Penyewa\BookingController::class, 'paymentCallback'])->name('api.webhook.payment');

// Test payment routes - Available in all environments for easier testing
Route::get('/test-payment', [\App\Http\Controllers\TestPaymentController::class, 'showForm'])->name('test-payment');
Route::get('/test-payment/connection', [\App\Http\Controllers\TestPaymentController::class, 'testConnection']);
Route::post('/test-payment/webhook', [\App\Http\Controllers\TestPaymentController::class, 'simulateWebhook']);
Route::get('/test-payment/webhook/{transaction_id}/{status}', [\App\Http\Controllers\TestPaymentController::class, 'simulateWebhookGet']);

// Webhook testing UI
Route::get('/webhook-test', function() {
    return view('webhook-test');
});

// Manual update routes
Route::get('/manual-update', [App\Http\Controllers\ManualController::class, 'showManualUpdateForm'])->name('manual.form');
Route::post('/manual-update', [App\Http\Controllers\ManualController::class, 'updatePemesananStatus'])->name('manual.update');

// Debug routes for payment bypass (CSRF disabled for testing)
Route::post('/debug/update-payment-status', function(Illuminate\Http\Request $request) {
    $bookingId = $request->input('booking_id');
    $booking = \App\Models\Pemesanan::find($bookingId);
    
    if (!$booking) {
        return response()->json(['error' => 'Booking not found'], 404);
    }
    
    // Update payment status
    $booking->payment_status = 'completed';
    $booking->status = 'confirmed';
    $booking->payment_transaction_id = 'MANUAL-' . time();
    $booking->save();
    
    // Update property status
    if ($booking->properti) {
        $booking->properti->status = 'disewa';
        $booking->properti->save();
    }
    
    return redirect('/payment-fix-tool.html?success=1&booking_id=' . $bookingId);
});

Route::get('/debug/check-payment-status', function(Illuminate\Http\Request $request) {
    $bookingId = $request->input('booking_id');
    $booking = \App\Models\Pemesanan::with('properti')->find($bookingId);
    
    if (!$booking) {
        return response()->json(['error' => 'Booking not found'], 404);
    }
    
    return response()->json([
        'booking_id' => $booking->id_pemesanan,
        'payment_status' => $booking->payment_status,
        'booking_status' => $booking->status,
        'transaction_id' => $booking->payment_transaction_id,
        'total_amount' => $booking->total_harga,
        'property_status' => $booking->properti->status ?? 'unknown'
    ]);
});

Route::get('/payment-debug', function() {
    return view('payment-debug');
});

Route::get('/debug/payment-config', function() {
    return response()->json([
        'payment_api_key' => config('payment.api_key') ? 'SET' : 'NOT_SET',
        'payment_merchant_code' => config('payment.merchant_code'),
        'payment_gateway_url' => config('payment.gateway_url'),
        'app_url' => config('app.url'),
        'ngrok_url' => 'https://cristopher-hastiest-unviolably.ngrok-free.dev',
        'correct_webhook_url' => 'https://cristopher-hastiest-unviolably.ngrok-free.dev/payment/callback',
        'callback_route' => route('penyewa.payment.callback'),
    ]);
});

// Test direct webhook endpoint that doesn't need authentication or CSRF
Route::post('/webhook/test', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('Webhook test received', [
        'headers' => $request->headers->all(),
        'body' => $request->all()
    ]);
    return response()->json(['success' => true, 'message' => 'Webhook received']);
});

require __DIR__.'/auth.php';
