<?php
/**
 * Enhanced ngrok testing script for payment integration
 * This script tests the connection to the payment gateway through ngrok
 * and tests webhook delivery with proper signature verification
 */

// Set your ngrok URL here (update this with your actual ngrok URL)
$ngrokUrl = "https://your-ngrok-subdomain.ngrok-free.dev";

// Include banner
echo "=========================================================\n";
echo "ENHANCED PAYMENT GATEWAY NGROK TEST\n";
echo "=========================================================\n";
echo "Testing with ngrok URL: $ngrokUrl\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n\n";

// Perform multiple tests
testEndpoint($ngrokUrl . "/test-payment", "Payment Test Page (HTML)");
testEndpoint($ngrokUrl . "/test-payment/connection", "Payment Connection API");
testEndpoint($ngrokUrl . "/api/payment/test", "Direct Payment Gateway API Test");

// Test multiple webhook endpoints to ensure at least one works
$webhookEndpoints = [
    "/webhook/test",
    "/api/webhook/payment", 
    "/api/payment/webhook", 
    "/webhook", 
    "/payment/callback"
];

echo "\n=========================================================\n";
echo "TESTING MULTIPLE WEBHOOK ENDPOINTS\n";
echo "=========================================================\n";

foreach ($webhookEndpoints as $endpoint) {
    testWebhook($ngrokUrl . $endpoint, "Webhook: " . $endpoint);
}

// Summary
echo "\n=========================================================\n";
echo "TEST COMPLETED\n";
echo "=========================================================\n";
echo "Make sure at least one webhook endpoint returned a successful response.\n";
echo "Check your Laravel logs for more details about webhook processing.\n";

/**
 * Test a specific endpoint and display results
 */
function testEndpoint($url, $description) {
    echo "\n=== $description ===\n";
    echo "URL: $url\n";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing only
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    echo "Status: " . ($httpCode == 200 ? "SUCCESS" : "FAILED") . " ($httpCode)\n";
    
    if ($error) {
        echo "Error: $error\n";
    }
    
    echo "Headers:\n$headers\n";
    
    // If the response appears to be JSON, format it
    if (strpos($headers, "application/json") !== false) {
        $json = json_decode($body);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "Body (JSON):\n" . json_encode($json, JSON_PRETTY_PRINT) . "\n";
        } else {
            echo "Body (first 200 chars):\n" . substr($body, 0, 200) . "...\n";
        }
    } else {
        echo "Body (first 200 chars):\n" . substr($body, 0, 200) . "...\n";
    }
}

/**
 * Test webhook endpoint with comprehensive error handling and debug information
 */
function testWebhook($url, $description) {
    echo "\n=== $description ===\n";
    echo "URL: $url\n";
    
    // Load environment variables
    loadEnvVariables();
    
    $webhookSecret = getenv('PAYMENT_WEBHOOK_SECRET');
    $merchantCode = getenv('PAYMENT_MERCHANT_CODE');
    
    if (!$webhookSecret) {
        echo "Error: PAYMENT_WEBHOOK_SECRET not found in .env file\n";
        return;
    }
    
    // Create payload with all required fields
    $transactionId = 'TEST-' . time();
    $payload = json_encode([
        'data' => [
            'id' => $transactionId,
            'status' => 'completed',
            'amount' => 100000,
            'payment_method' => 'bank_transfer',
            'merchant_code' => $merchantCode ?? 'MCH-GXLUDZ',
            'external_id' => 'ORD-' . rand(10000, 99999),
            'created_at' => date('c'),
            'updated_at' => date('c'),
            'paid_at' => date('c'),
            'va_number' => $transactionId, // Some gateways use va_number instead of id
            'transaction_id' => $transactionId, // Add redundant field for compatibility
        ]
    ]);
    
    // Generate signature
    $signature = hash_hmac('sha256', $payload, $webhookSecret);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-Webhook-Signature: ' . $signature,
        'X-Payment-Signature: ' . $signature,  // Add multiple signature headers for compatibility
        'X-Payment-Webhook: true'              // Add a flag for our middleware to recognize
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing only
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    echo "Status: " . ($httpCode >= 200 && $httpCode < 300 ? "SUCCESS" : "FAILED") . " ($httpCode)\n";
    
    if ($error) {
        echo "Error: $error\n";
    }
    
    echo "Webhook Data:\n";
    echo "  Transaction ID: $transactionId\n";
    echo "  Signature: $signature\n";
    
    echo "Response:\n$response\n";
}

/**
 * Load environment variables from .env file
 */
function loadEnvVariables() {
    if (file_exists(__DIR__ . '/.env')) {
        $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                if (!empty($name)) {
                    putenv("$name=$value");
                }
            }
        }
    }
}
