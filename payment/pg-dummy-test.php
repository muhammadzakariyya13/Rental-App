<?php

/*
|--------------------------------------------------------------------------
| PG Dummy Integration Test Script
|--------------------------------------------------------------------------
|
| This script tests the integration with the PG Dummy payment gateway
| by sending a test transaction and verifying the response.
|
*/

require_once __DIR__ . '/../vendor/autoload.php';

// Since we're not bootstrapping Laravel, we'll use Guzzle directly
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

// Load configuration from .env file
$envFile = file_get_contents(__DIR__ . '/../.env');
$envVars = [];
foreach (explode("\n", $envFile) as $line) {
    if (empty(trim($line)) || strpos(trim($line), '#') === 0) {
        continue;
    }
    
    $parts = explode('=', $line, 2);
    if (count($parts) === 2) {
        $key = trim($parts[0]);
        $value = trim($parts[1]);
        // Remove quotes if present
        if (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) {
            $value = substr($value, 1, -1);
        }
        $envVars[$key] = $value;
    }
}

// Get configuration
$apiKey = $envVars['PAYMENT_API_KEY'] ?? '';
$gatewayUrl = $envVars['PAYMENT_GATEWAY_URL'] ?? 'https://payment-dummy.doovera.com';
$merchantCode = $envVars['PAYMENT_MERCHANT_CODE'] ?? '';
$webhookSecret = $envVars['PAYMENT_WEBHOOK_SECRET'] ?? '';

echo "Testing PG Dummy Payment Gateway Integration\n";
echo "==========================================\n";
echo "Gateway URL: {$gatewayUrl}\n";
echo "Merchant Code: {$merchantCode}\n";
echo "API Key: " . substr($apiKey, 0, 5) . '...' . substr($apiKey, -5) . "\n\n";

// Create HTTP client
$client = new Client([
    'timeout' => 10,
    'http_errors' => false
]);

// Test Health Check
echo "1. Testing Health Check...\n";
try {
    $response = $client->request('GET', $gatewayUrl . '/api/health-check', [
        'headers' => [
            'X-API-Key' => $apiKey,
            'Accept' => 'application/json'
        ]
    ]);
    
    $statusCode = $response->getStatusCode();
    $body = $response->getBody()->getContents();
    
    if ($statusCode >= 200 && $statusCode < 300) {
        echo "✅ Health Check successful. Status code: {$statusCode}\n";
        echo "Response body: {$body}\n\n";
    } else {
        echo "❌ Health Check failed. Status code: {$statusCode}\n";
        echo "Error: {$body}\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Health Check exception: " . $e->getMessage() . "\n\n";
}

// Test Transaction Creation
echo "2. Testing Transaction Creation...\n";
try {
    $testPayload = [
        'transaction' => [
            'amount' => 50000, // 50,000 IDR
            'order_id' => 'TEST-' . uniqid(),
            'merchant_code' => $merchantCode,
            'customer_details' => [
                'name' => 'Test Customer',
                'email' => 'test@example.com'
            ],
            'payment_type' => 'bank_transfer',
            'item_details' => [
                [
                    'name' => 'Test Booking',
                    'price' => 50000,
                    'quantity' => 1
                ]
            ],
            'description' => 'Test transaction from integration script',
            'callback_url' => 'https://your-app.com/callback',
            'redirect_urls' => [
                'success' => 'https://your-app.com/success',
                'failure' => 'https://your-app.com/failure'
            ],
            'metadata' => [
                'test' => true
            ]
        ]
    ];
    
    echo "Sending payload:\n" . json_encode($testPayload, JSON_PRETTY_PRINT) . "\n\n";
      $response = $client->request('POST', $gatewayUrl . '/api/transactions', [
        'headers' => [
            'X-API-Key' => $apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ],
        'json' => $testPayload
    ]);
    
    $statusCode = $response->getStatusCode();
    $body = $response->getBody()->getContents();
    $responseData = json_decode($body, true);
    
    if ($statusCode >= 200 && $statusCode < 300) {
        echo "✅ Transaction creation successful. Status code: {$statusCode}\n";
        echo "Transaction ID: " . ($responseData['data']['transaction_id'] ?? 'N/A') . "\n";
        echo "Redirect URL: " . ($responseData['data']['redirect_url'] ?? 'N/A') . "\n";
        echo "Full Response: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n\n";
    } else {
        echo "❌ Transaction creation failed. Status code: {$statusCode}\n";
        echo "Error: {$body}\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Transaction creation exception: " . $e->getMessage() . "\n\n";
}

// Test Transaction Status Check
echo "3. Testing Transaction Status Check...\n";
try {
    // Use a dummy transaction ID for testing
    $testTransactionId = 'TEST123456789';
      $response = $client->request('GET', $gatewayUrl . '/api/transactions/' . $testTransactionId, [
        'headers' => [
            'X-API-Key' => $apiKey,
            'Accept' => 'application/json'
        ]
    ]);
    
    $statusCode = $response->getStatusCode();
    $body = $response->getBody()->getContents();
    
    if ($statusCode >= 200 && $statusCode < 300) {
        echo "✅ Transaction status check successful. Status code: {$statusCode}\n";
        echo "Response body: {$body}\n\n";
    } else {
        echo "❌ Transaction status check failed. Status code: {$statusCode}\n";
        echo "Error: {$body}\n\n";
        echo "Note: This is expected to fail if the transaction ID doesn't exist.\n\n";
    }
} catch (\Exception $e) {
    echo "❌ Transaction status check exception: " . $e->getMessage() . "\n\n";
}

// Test Webhook Verification
echo "4. Testing Webhook Verification Signature Generation...\n";
try {
    $testPayload = json_encode(['status' => 'completed', 'transaction_id' => 'TEST123456789']);
    
    $signature = hash_hmac('sha256', $testPayload, $webhookSecret);
    
    echo "Webhook Secret: " . substr($webhookSecret, 0, 5) . '...' . substr($webhookSecret, -5) . "\n";
    echo "Test Payload: " . $testPayload . "\n";
    echo "Generated Signature: " . $signature . "\n\n";
    
    echo "To test webhook, send a POST request to your callback URL with:\n";
    echo "- The payload in the request body\n";
    echo "- X-PG-Signature: " . $signature . " in the headers\n\n";
} catch (\Exception $e) {
    echo "❌ Webhook verification exception: " . $e->getMessage() . "\n\n";
}

echo "Integration test completed.\n";
