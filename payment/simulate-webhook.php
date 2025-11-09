<?php

/**
 * PG Dummy Webhook Simulator
 * --------------------------
 * This script simulates a webhook from the PG Dummy payment gateway.
 * It sends a webhook to your application's callback URL with proper format and signatures.
 * 
 * Usage:
 * ------
 * php simulate-webhook.php [transaction_id] [status] [callback_url]
 * 
 * Examples:
 * ---------
 * php simulate-webhook.php 8800000671781298 completed http://localhost:8000/api/webhook/payment
 * php simulate-webhook.php BOOK-123 failed https://your-ngrok-url.ngrok-free.dev/api/webhook/payment
 */

// Check for required parameters
if ($argc < 4) {
    echo "Usage: php simulate-webhook.php [transaction_id] [status] [callback_url]\n";
    echo "Example: php simulate-webhook.php 8800000671781298 completed http://localhost:8000/api/webhook/payment\n";
    exit(1);
}

// Get the parameters
$transactionId = $argv[1];
$status = $argv[2];
$callbackUrl = $argv[3];

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

// Get webhook secret from .env file
$webhookSecret = $envVars['PAYMENT_WEBHOOK_SECRET'] ?? '';
$merchantCode = $envVars['PAYMENT_MERCHANT_CODE'] ?? '';

if (empty($webhookSecret)) {
    echo "Error: PAYMENT_WEBHOOK_SECRET not found in .env file\n";
    exit(1);
}

// Check if status is valid
$validStatuses = ['pending', 'processing', 'completed', 'failed', 'cancelled'];
if (!in_array($status, $validStatuses)) {
    echo "Warning: Status '{$status}' is not one of: " . implode(', ', $validStatuses) . "\n";
    echo "Continuing anyway...\n";
}

// Determine if this is a booking ID or transaction ID
$externalId = '';
if (strpos($transactionId, 'BOOK-') === 0) {
    $externalId = $transactionId;
    $transactionId = '88' . str_pad(rand(1000000, 9999999), 10, '0', STR_PAD_LEFT);
} else {
    $externalId = 'BOOK-' . rand(1000, 9999);
}

// Prepare webhook payload according to PG Dummy API documentation
$webhookPayload = [
    'data' => [
        'id' => $transactionId,
        'status' => $status,
        'amount' => rand(10000, 1000000), // Random amount between 10K and 1M
        'payment_method' => 'bank_transfer',
        'merchant_code' => $merchantCode,
        'external_id' => $externalId,
        'created_at' => date('Y-m-d\TH:i:s\Z', strtotime('-1 hour')),
        'updated_at' => date('Y-m-d\TH:i:s\Z'),
        'paid_at' => $status === 'completed' ? date('Y-m-d\TH:i:s\Z') : null
    ]
];

// Convert to JSON
$jsonPayload = json_encode($webhookPayload);

// Generate signature
$signature = hash_hmac('sha256', $jsonPayload, $webhookSecret);

// Print the request details
echo "Sending webhook to: {$callbackUrl}\n";
echo "Transaction ID: {$transactionId}\n";
echo "External ID: {$externalId}\n";
echo "Status: {$status}\n";
echo "Signature: {$signature}\n";
echo "Payload: {$jsonPayload}\n\n";

// Send the webhook using cURL
$ch = curl_init($callbackUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Webhook-Signature: ' . $signature
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Print the response
echo "Response HTTP Code: {$httpCode}\n";
echo "Response Body: {$response}\n";

// Exit with success or failure code based on HTTP response
exit($httpCode >= 200 && $httpCode < 300 ? 0 : 1);
