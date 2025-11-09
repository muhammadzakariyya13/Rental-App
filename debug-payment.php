<?php
// Debug Payment Gateway Configuration
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== PAYMENT GATEWAY DEBUG ===\n\n";

echo "1. Environment Variables:\n";
echo "PAYMENT_API_KEY: " . (env('PAYMENT_API_KEY') ? 'SET (' . substr(env('PAYMENT_API_KEY'), 0, 10) . '...)' : 'NOT SET') . "\n";
echo "PAYMENT_MERCHANT_CODE: " . (env('PAYMENT_MERCHANT_CODE') ? env('PAYMENT_MERCHANT_CODE') : 'NOT SET') . "\n";
echo "PAYMENT_GATEWAY_URL: " . (env('PAYMENT_GATEWAY_URL') ? env('PAYMENT_GATEWAY_URL') : 'NOT SET') . "\n";
echo "PAYMENT_WEBHOOK_SECRET: " . (env('PAYMENT_WEBHOOK_SECRET') ? 'SET' : 'NOT SET') . "\n";
echo "APP_URL: " . env('APP_URL') . "\n\n";

echo "2. Expected Webhook URLs:\n";
echo "Local: " . env('APP_URL') . "/payment/callback\n";
echo "Ngrok: https://cristopher-hastiest-unviolably.ngrok-free.dev/payment/callback\n\n";

echo "3. Testing PG Dummy API Connection:\n";
$apiKey = env('PAYMENT_API_KEY');
$baseUrl = env('PAYMENT_GATEWAY_URL');
$merchantCode = env('PAYMENT_MERCHANT_CODE');

if ($apiKey && $baseUrl && $merchantCode) {
    // Test dengan data dummy untuk cek koneksi ke API
    $testPayload = [
        'external_id' => 'TEST-' . time(),
        'amount' => 100000,
        'merchant_code' => $merchantCode,
        'customer_name' => 'Test User',
        'customer_email' => 'test@example.com',
        'payment_method' => 'va_bca',
        'description' => 'Test payment',
        'callback_url' => env('APP_URL') . '/payment/callback'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/v1/virtual-account/create');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testPayload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-API-Key: ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    echo "API Endpoint: " . $baseUrl . "/api/v1/virtual-account/create\n";
    echo "HTTP Code: " . $httpCode . "\n";
    
    if (curl_error($ch)) {
        echo "cURL Error: " . curl_error($ch) . "\n";
    } else {
        echo "Response: " . substr($response, 0, 500) . (strlen($response) > 500 ? '...' : '') . "\n";
        
        // Parse JSON response untuk analisis lebih lanjut
        $jsonResponse = json_decode($response, true);
        if ($jsonResponse) {
            echo "Response Status: " . ($jsonResponse['status'] ?? 'unknown') . "\n";
            echo "Response Message: " . ($jsonResponse['message'] ?? 'no message') . "\n";
        }
    }
    
    curl_close($ch);
} else {
    echo "Missing required configuration:\n";
    echo "API Key: " . ($apiKey ? 'SET' : 'MISSING') . "\n";
    echo "Base URL: " . ($baseUrl ? 'SET' : 'MISSING') . "\n";
    echo "Merchant Code: " . ($merchantCode ? 'SET' : 'MISSING') . "\n";
}

echo "\n=== END DEBUG ===\n";
?>