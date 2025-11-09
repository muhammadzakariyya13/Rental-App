<?php
/**
 * Direct Payment Gateway API Test
 * 
 * This script tests the payment gateway API directly without going through Laravel
 */

// Check if dotenv is available
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
    
    // Load environment variables if possible
    if (class_exists('Dotenv\Dotenv')) {
        // Look for .env in the parent directory since we're in the payment folder
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();
    }
}

// Configuration - either from .env or manually set here
$apiKey = getenv('PAYMENT_API_KEY') ?: 'your-api-key-here';
$merchantCode = getenv('PAYMENT_MERCHANT_CODE') ?: 'your-merchant-code-here';
$gatewayUrl = getenv('PAYMENT_GATEWAY_URL') ?: 'https://payment-dummy.doovera.com';

// Function to make an API request
function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    // Set default headers
    $defaultHeaders = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    // Add API key if present
    if (!empty($headers['X-API-Key'])) {
        $defaultHeaders[] = 'X-API-Key: ' . $headers['X-API-Key'];
        unset($headers['X-API-Key']);
    }
    
    // Add custom headers
    foreach ($headers as $name => $value) {
        $defaultHeaders[] = "$name: $value";
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $defaultHeaders);
    
    // For development only
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    return [
        'status' => $info['http_code'],
        'response' => $response ? json_decode($response, true) : null,
        'error' => $error,
        'info' => $info
    ];
}

// Test connection to the gateway base URL
echo "============================================\n";
echo "PAYMENT GATEWAY API TEST\n";
echo "============================================\n";
echo "Gateway URL: $gatewayUrl\n";
echo "API Key: " . (empty($apiKey) ? "NOT SET" : "Set (hidden)") . "\n";
echo "Merchant Code: " . (empty($merchantCode) ? "NOT SET" : $merchantCode) . "\n";
echo "============================================\n\n";

echo "1. Testing connection to gateway...\n";
$baseTest = makeRequest($gatewayUrl);

echo "Status code: " . $baseTest['status'] . "\n";
if ($baseTest['error']) {
    echo "Error: " . $baseTest['error'] . "\n";
} else {
    echo "Successfully connected to payment gateway\n";
}

// Test API status endpoint
echo "\n2. Testing API status endpoint...\n";
$statusTest = makeRequest($gatewayUrl . '/api/status', 'GET', null, [
    'X-API-Key' => $apiKey
]);

echo "Status code: " . $statusTest['status'] . "\n";
if ($statusTest['error']) {
    echo "Error: " . $statusTest['error'] . "\n";
} else {
    echo "Response: " . json_encode($statusTest['response'], JSON_PRETTY_PRINT) . "\n";
}

// Test merchant status if we have a merchant code
if ($merchantCode) {
    echo "\n3. Testing merchant status endpoint...\n";
    $merchantTest = makeRequest($gatewayUrl . "/api/merchant/$merchantCode/status", 'GET', null, [
        'X-API-Key' => $apiKey
    ]);
    
    echo "Status code: " . $merchantTest['status'] . "\n";
    if ($merchantTest['error']) {
        echo "Error: " . $merchantTest['error'] . "\n";
    } else {
        echo "Response: " . json_encode($merchantTest['response'], JSON_PRETTY_PRINT) . "\n";
    }
}

// Test create virtual account
echo "\n4. Testing create virtual account endpoint...\n";

$transactionData = [
    'amount' => 10000,
    'external_id' => 'TEST-' . time(),
    'merchant_code' => $merchantCode,
    'customer_name' => 'Test Customer',
    'customer_email' => 'test@example.com',
    'payment_method' => 'bank_transfer',
    'description' => 'Test transaction from direct API test',
    'callback_url' => 'https://example.com/webhook',
    'success_redirect_url' => 'https://example.com/success',
    'failure_redirect_url' => 'https://example.com/failure',
];

$createTest = makeRequest($gatewayUrl . '/api/v1/virtual-account/create', 'POST', $transactionData, [
    'X-API-Key' => $apiKey
]);

echo "Status code: " . $createTest['status'] . "\n";
if ($createTest['error']) {
    echo "Error: " . $createTest['error'] . "\n";
} else {
    echo "Response: " . json_encode($createTest['response'], JSON_PRETTY_PRINT) . "\n";
    
    // If successful, save the transaction details for later testing
    if ($createTest['status'] >= 200 && $createTest['status'] < 300) {
        $responseData = $createTest['response']['data'] ?? $createTest['response'];
        $transactionId = $responseData['id'] ?? $responseData['va_number'] ?? null;
        
        if ($transactionId) {
            echo "\nTransaction created successfully!\n";
            echo "Transaction ID: $transactionId\n";
            echo "Payment URL: " . ($responseData['payment_url'] ?? 'Not available') . "\n";
            
            echo "\nYou can use this transaction ID to test webhooks later.\n";
        }
    }
}

echo "\n============================================\n";
echo "TESTS COMPLETED\n";
echo "============================================\n";
?>
