<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Payment API routes
Route::get('/payment/test', [PaymentController::class, 'testConnection']);
Route::post('/payment/create-test-transaction', [PaymentController::class, 'createTestTransaction']);

// Payment webhook endpoints - Multiple URLs to ensure they can be received
Route::post('/payment/webhook', [PaymentController::class, 'handleWebhook']);
Route::post('/webhook/payment', [PaymentController::class, 'handleWebhook']);
Route::post('/webhook', [PaymentController::class, 'handleWebhook']);

// Booking and Transaction ID lookup route (no auth required for testing)
Route::get('/bookings/{id}/transaction-id', function($id) {
    $booking = \App\Models\Pemesanan::find($id);
    
    if (!$booking) {
        return response()->json([
            'error' => 'Booking not found',
            'booking_id' => $id
        ], 404);
    }
    
    return response()->json([
        'booking_id' => $booking->id_pemesanan,
        'transaction_id' => $booking->payment_transaction_id,
        'status' => $booking->status,
        'payment_status' => $booking->payment_status
    ]);
});

// Public payment test routes (no auth required)
Route::prefix('public')->group(function() {
    Route::get('/payment-test', function() {
        return view('test-payment-public');
    });
    
    Route::get('/payment-test/connection', function() {
        $apiKey = config('payment.api_key');
        $webhookSecret = config('payment.webhook_secret');
        $merchantCode = config('payment.merchant_code');
        $gatewayUrl = config('payment.gateway_url');
        
        // Try connecting to the payment gateway
        try {
            $testResponse = \Illuminate\Support\Facades\Http::timeout(5)->get($gatewayUrl);
            $isAccessible = $testResponse->successful();
            $statusCode = $testResponse->status();
        } catch (\Exception $e) {
            $isAccessible = false;
            $statusCode = 0;
            $error = $e->getMessage();
        }
        
        return response()->json([
            'success' => !empty($apiKey) && !empty($gatewayUrl),
            'message' => 'Payment configuration found',
            'api_key' => !empty($apiKey) ? 'Set (Hidden)' : 'Not set',
            'webhook_secret' => !empty($webhookSecret) ? 'Set (Hidden)' : 'Not set',
            'merchant_code' => $merchantCode ?? 'Not set',
            'gateway_url' => $gatewayUrl ?? 'Not set',
            'connection_test' => isset($isAccessible) && $isAccessible ? 'Successful' : 'Failed',
            'status_code' => $statusCode ?? 'N/A',
            'error' => $error ?? null
        ]);
    });
});
