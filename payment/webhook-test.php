<?php

/*
 * This is a simple test script to simulate payment gateway webhooks
 * You can run this script from the command line to test webhook processing
 * 
 * Usage: php webhook-test.php [transaction_id] [status]
 * Example: php webhook-test.php TRX-1234567 completed
 */

require __DIR__.'/../vendor/autoload.php';

// Get arguments
$transactionId = $argv[1] ?? 'TEST-' . uniqid();
$status = $argv[2] ?? 'completed';

// Create payload matching the payment gateway webhook format
$payload = json_encode([
    'data' => [
        'id' => $transactionId,
        'status' => $status,
        'amount' => 1000000,
        'payment_method' => 'bank_transfer',
        'merchant_code' => 'MCH-GXLUDZ',
        'external_id' => 'ORD-' . rand(10000, 99999),
        'created_at' => date('c'),
        'updated_at' => date('c'),
        'paid_at' => $status === 'completed' || $status === 'success' ? date('c') : null,
    ]
]);

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get the webhook secret
$webhookSecret = $_ENV['PAYMENT_WEBHOOK_SECRET'] ?? null;

if (!$webhookSecret) {
    echo "Error: PAYMENT_WEBHOOK_SECRET not found in .env file\n";
    exit(1);
}

// Generate signature
$signature = hash_hmac('sha256', $payload, $webhookSecret);

// Build the curl command
$callbackUrl = 'http://localhost:8000/webhook/test';
$command = "curl -X POST {$callbackUrl} \
-H 'Content-Type: application/json' \
-H 'X-Webhook-Signature: {$signature}' \
-d '{$payload}'";

echo "Sending webhook to {$callbackUrl}...\n";
echo "Transaction ID: {$transactionId}\n";
echo "Status: {$status}\n";
echo "Signature: {$signature}\n";
echo "Payload: {$payload}\n\n";

// Execute the curl command
echo "Command: {$command}\n\n";
echo "Response:\n";
passthru($command);
echo "\n";
