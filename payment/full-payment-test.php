<?php

/**
 * Full Payment Integration Test Script
 * 
 * This script tests the entire payment flow:
 * 1. Creates a test transaction with the payment gateway
 * 2. Sends a test webhook to simulate payment completion
 * 3. Verifies the transaction status was updated
 */

// Load the Laravel environment
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\PaymentService;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Log;

// Configuration
$baseUrl = $argv[1] ?? 'http://localhost:8000';  // Default to localhost, override with CLI arg
$isNgrok = strpos($baseUrl, 'ngrok') !== false;

echo "Starting full payment integration test with base URL: $baseUrl\n";
echo ($isNgrok ? "Using ngrok URL\n" : "Using local URL\n");

// Step 1: Create a test booking if needed
$booking = Pemesanan::where('metode_pembayaran', 'test_payment')->first();

if (!$booking) {
    echo "Creating a test booking...\n";
    // This is a simplified version - in a real application, you would create a proper booking
    $booking = new Pemesanan();
    $booking->id_pemesanan = 'TEST-' . time();
    $booking->id_penyewa = 1;  // Assume user ID 1 exists
    $booking->id_properti = 1; // Assume property ID 1 exists
    $booking->tanggal_masuk = date('Y-m-d');
    $booking->tanggal_keluar = date('Y-m-d', strtotime('+30 days'));
    $booking->total_harga = 1000000;
    $booking->status = 'pending';
    $booking->metode_pembayaran = 'test_payment';
    $booking->created_at = now();
    $booking->updated_at = now();
    $booking->save();
    
    echo "Created test booking with ID: {$booking->id_pemesanan}\n";
} else {
    echo "Using existing test booking with ID: {$booking->id_pemesanan}\n";
}

// Step 2: Create a payment transaction
echo "Creating payment transaction...\n";

$paymentService = app(PaymentService::class);
$transactionResult = $paymentService->createTransaction($booking);

if (!$transactionResult) {
    echo "Failed to create transaction\n";
    exit(1);
}

// Reload booking to get updated transaction ID
$booking = Pemesanan::find($booking->id_pemesanan);
$transactionId = $booking->payment_transaction_id;

echo "Transaction created with ID: $transactionId\n";
echo "Payment URL: {$booking->payment_url}\n";
echo "Payment Status: {$booking->payment_status}\n";

// Step 3: Send a test webhook to simulate payment completion
echo "\nSimulating payment completion webhook...\n";

// Create webhook payload
$payload = json_encode([
    'data' => [
        'id' => $transactionId,
        'status' => 'completed',
        'amount' => $booking->total_harga,
        'payment_method' => 'bank_transfer',
        'merchant_code' => config('payment.merchant_code'),
        'external_id' => $booking->id_pemesanan,
        'created_at' => date('c'),
        'updated_at' => date('c'),
        'paid_at' => date('c'),
    ]
]);

// Generate signature
$webhookSecret = config('payment.webhook_secret');
$signature = hash_hmac('sha256', $payload, $webhookSecret);

// Use all webhook endpoints to ensure one works
$webhookEndpoints = [
    '/webhook/test',
    '/api/webhook/payment',
    '/api/payment/webhook',
    '/webhook',
    '/payment/callback'
];

$successfulWebhook = false;

foreach ($webhookEndpoints as $endpoint) {
    $webhookUrl = rtrim($baseUrl, '/') . $endpoint;
    
    echo "\nTesting webhook endpoint: $webhookUrl\n";
    
    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-Webhook-Signature: ' . $signature,
        'X-Payment-Webhook: true'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing only
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    echo "Response code: $httpCode\n";
    if ($error) {
        echo "Error: $error\n";
    } else {
        echo "Response: $response\n";
        
        // If we got a 2xx response, consider it successful
        if ($httpCode >= 200 && $httpCode < 300) {
            $successfulWebhook = true;
            echo "Webhook successfully delivered to $endpoint!\n";
            break;
        }
    }
}

if (!$successfulWebhook) {
    echo "\nWarning: None of the webhook endpoints returned a successful response.\n";
    echo "This might be because the webhook was processed asynchronously or there's an issue with webhook handling.\n";
}

// Step 4: Check if the booking status was updated
sleep(2); // Wait a bit for processing
echo "\nChecking booking status after webhook...\n";

// Reload booking to get updated status
$updatedBooking = Pemesanan::find($booking->id_pemesanan);

echo "Previous status: {$booking->status}\n";
echo "Current status: {$updatedBooking->status}\n";
echo "Payment status: {$updatedBooking->payment_status}\n";

if ($updatedBooking->status === 'confirmed' || $updatedBooking->payment_status === 'paid') {
    echo "\nSuccess! The payment flow is working correctly.\n";
} else {
    echo "\nWarning: Booking status was not updated to 'confirmed'. Check the server logs for more details.\n";
}

echo "\nTest completed.\n";
