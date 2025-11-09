<?php
/**
 * Comprehensive ngrok testing script for payment integration
 */

// Set your ngrok URL here
$ngrokUrl = "https://cristopher-hastiest-unviolably.ngrok-free.dev";

// Perform multiple tests
testEndpoint($ngrokUrl . "/test-payment", "Testing payment test page (HTML)");
testEndpoint($ngrokUrl . "/test-payment/connection", "Testing payment connection API");
testEndpoint($ngrokUrl . "/api/payment/test", "Testing direct payment gateway API test");
testWebhook($ngrokUrl . "/api/webhook/payment", "Testing webhook endpoint");
testWebhook($ngrokUrl . "/api/payment/webhook", "Testing alternative webhook endpoint");

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
 * Test webhook endpoint
 */
function testWebhook($url, $description) {
    echo "\n=== $description ===\n";
    echo "URL: $url\n";
    
    // Load environment variables
    if (file_exists(__DIR__ . '/.env')) {
        $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            if (!empty($name)) {
                putenv("$name=$value");
            }
        }
    }
    
    $webhookSecret = getenv('PAYMENT_WEBHOOK_SECRET');
    $merchantCode = getenv('PAYMENT_MERCHANT_CODE');
    
    if (!$webhookSecret) {
        echo "Error: PAYMENT_WEBHOOK_SECRET not found in .env file\n";
        return;
    }
    
    // Create payload
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
        'X-Webhook-Signature: ' . $signature
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For testing only
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
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
?>
