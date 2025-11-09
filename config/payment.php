<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your payment gateway settings for the application.
    | This includes API keys, webhook secrets, and other settings.
    |
    */    'api_key' => env('PAYMENT_API_KEY', ''),
    'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET', ''),
    'gateway_url' => env('PAYMENT_GATEWAY_URL', 'https://payment-dummy.doovera.com'),
    'merchant_code' => env('PAYMENT_MERCHANT_CODE', ''),
      // Define payment status values
    'status' => [
        'pending' => 'pending',
        'processing' => 'processing',
        'completed' => 'completed',
        'failed' => 'failed',
        'cancelled' => 'cancelled',
    ],
    
    // Payment options
    'options' => [
        'regular' => 'Regular payment through gateway',
        'simulated' => 'Simulated successful payment (testing only)',
        'offline' => 'Offline payment (pay later in person)',
    ],
    
    // Transaction ID prefixes
    'transaction_prefixes' => [
        'simulated' => 'SIM-',
        'offline' => 'OFFLINE-',
        'emergency' => 'EMERGENCY-',
    ],
];
