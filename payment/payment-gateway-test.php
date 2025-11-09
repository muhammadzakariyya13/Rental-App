<?php
/**
 * Script untuk mengetes koneksi ke Payment Gateway API
 */

// Load environment variables from .env
require __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Get API credentials from environment
$apiKey = $_ENV['PAYMENT_API_KEY'] ?? null;
$merchantCode = $_ENV['PAYMENT_MERCHANT_CODE'] ?? null;
$gatewayUrl = $_ENV['PAYMENT_GATEWAY_URL'] ?? 'https://payment-dummy.doovera.com';

if (!$apiKey) {
    echo "Error: PAYMENT_API_KEY not found in .env file\n";
    exit(1);
}

if (!$merchantCode) {
    echo "Error: PAYMENT_MERCHANT_CODE not found in .env file\n";
    exit(1);
}

echo "Testing payment gateway connection...\n";
echo "Gateway URL: $gatewayUrl\n";
echo "Merchant Code: $merchantCode\n\n";

// Test 1: Basic connection to gateway
echo "TEST 1: Basic connection to gateway\n";
$ch = curl_init($gatewayUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing only

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status Code: $httpCode\n";
if ($error) {
    echo "Error: $error\n";
} else {
    echo "Connection successful\n";
}

// Test 2: Test the virtual account API endpoint
echo "\nTEST 2: Virtual Account API endpoint\n";

$testData = [
    'external_id' => 'TEST-' . time(),
    'amount' => 100000,
    'merchant_code' => $merchantCode,
    'customer_name' => 'Test Customer',
    'customer_email' => 'test@example.com',
    'payment_method' => 'bank_transfer',
    'description' => 'Test transaction from script',
    'callback_url' => 'https://example.com/callback',
    'success_redirect_url' => 'https://example.com/success',
    'failure_redirect_url' => 'https://example.com/failure',
];

$ch = curl_init("$gatewayUrl/api/v1/virtual-account/create");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: ' . $apiKey,
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing only

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status Code: $httpCode\n";
if ($error) {
    echo "Error: $error\n";
} else {
    echo "Response:\n";
    $json = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode($json, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo $response . "\n";
    }
}

// Test 3: Try an alternative endpoint to see what's available
echo "\nTEST 3: Alternative API endpoint test\n";

$ch = curl_init("$gatewayUrl/api/v1/transactions/create");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: ' . $apiKey,
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing only

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status Code: $httpCode\n";
if ($error) {
    echo "Error: $error\n";
} else {
    echo "Response:\n";
    $json = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode($json, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo $response . "\n";
    }
}

// Test 4: Check status endpoint
echo "\nTEST 4: Status endpoint test\n";

$ch = curl_init("$gatewayUrl/api/v1/status");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: ' . $apiKey,
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing only

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status Code: $httpCode\n";
if ($error) {
    echo "Error: $error\n";
} else {
    echo "Response:\n";
    $json = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode($json, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo $response . "\n";
    }
}
