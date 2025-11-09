<?php

/**
 * Payment Options Test Script
 * 
 * This script tests all available payment options:
 * 1. Regular payment through the gateway
 * 2. Simulated successful payment
 * 3. Offline payment (pay later in person)
 */

// Load the Laravel environment
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pemesanan;
use App\Services\PaymentService;
use App\Services\DirectPaymentService;
use Illuminate\Support\Facades\Log;

echo "=========================================\n";
echo "PAYMENT OPTIONS TEST\n";
echo "=========================================\n";

// 1. Test simulated payment
echo "\n--- TESTING SIMULATED PAYMENT ---\n";
$bookingSimulated = createTestBooking('simulated');
echo "Created test booking with ID: {$bookingSimulated->id_pemesanan}\n";

$resultSimulated = DirectPaymentService::processPayment($bookingSimulated, 'simulated');
echo "Simulated payment result: " . ($resultSimulated ? "SUCCESS" : "FAILED") . "\n";

// Reload booking to get updated status
$bookingSimulated = Pemesanan::find($bookingSimulated->id_pemesanan);
echo "Payment status: {$bookingSimulated->payment_status}\n";
echo "Booking status: {$bookingSimulated->status}\n";
echo "Transaction ID: {$bookingSimulated->payment_transaction_id}\n";

// 2. Test offline payment
echo "\n--- TESTING OFFLINE PAYMENT ---\n";
$bookingOffline = createTestBooking('offline');
echo "Created test booking with ID: {$bookingOffline->id_pemesanan}\n";

$resultOffline = DirectPaymentService::processPayment($bookingOffline, 'offline');
echo "Offline payment result: " . ($resultOffline ? "SUCCESS" : "FAILED") . "\n";

// Reload booking to get updated status
$bookingOffline = Pemesanan::find($bookingOffline->id_pemesanan);
echo "Payment status: {$bookingOffline->payment_status}\n";
echo "Booking status: {$bookingOffline->status}\n";
echo "Transaction ID: {$bookingOffline->payment_transaction_id}\n";

// 3. Test regular payment through gateway
echo "\n--- TESTING REGULAR PAYMENT ---\n";
$bookingRegular = createTestBooking('transfer');
echo "Created test booking with ID: {$bookingRegular->id_pemesanan}\n";

$paymentService = new PaymentService();
$transaction = $paymentService->createTransaction($bookingRegular);

if ($transaction) {
    echo "Transaction created successfully\n";
    echo "Payment status: {$bookingRegular->payment_status}\n";
    echo "Transaction ID: {$bookingRegular->payment_transaction_id}\n";
    
    // If gateway is not accessible, it will create an offline transaction
    echo "Is offline mode: " . (isset($transaction['offline_mode']) && $transaction['offline_mode'] ? "Yes" : "No") . "\n";
} else {
    echo "Failed to create transaction\n";
}

echo "\n=========================================\n";
echo "TEST COMPLETED\n";
echo "=========================================\n";

/**
 * Create a test booking
 */
function createTestBooking($paymentMethod = 'transfer')
{
    // Delete any existing test bookings to prevent duplicate ID errors
    Pemesanan::where('id_pemesanan', 'like', 'TEST-%')->delete();
    
    $booking = new Pemesanan();    $booking->id_pemesanan = 'TEST-' . uniqid();
    $booking->id_penyewa = 1; // Assume user ID 1 exists
    $booking->id_properti = 1; // Assume property ID 1 exists
    $booking->tanggal_mulai = date('Y-m-d');
    $booking->tanggal_selesai = date('Y-m-d', strtotime('+30 days'));
    $booking->durasi = 30;
    $booking->total_harga = 1000000;
    $booking->status = 'pending';
    $booking->metode_pembayaran = $paymentMethod;
    $booking->created_at = now();
    $booking->updated_at = now();
      // Load the property and penyewa relationships
    try {
        $booking->properti = \App\Models\Properti::findOrFail($booking->id_properti);
        $booking->penyewa = \App\Models\Akun::findOrFail($booking->id_penyewa);
    } catch (\Exception $e) {
        echo "Warning: Could not load related models: " . $e->getMessage() . "\n";
        echo "Using mock data instead\n";
        
        // Use mock property data
        $properti = new class {
            public $nama = 'Test Property';
            public $status = 'tersedia';
            public function save() { return true; }
        };
        $booking->setRelation('properti', $properti);
        
        // Use mock user data
        $penyewa = new class {
            public $username = 'Test User';
            public $email = 'test@example.com';
        };
        $booking->setRelation('penyewa', $penyewa);
    }
    
    $booking->save();
    return $booking;
}
