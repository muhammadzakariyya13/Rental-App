<?php

return [
    'doovera' => [
        'api_key' => env('DOOVERA_API_KEY', 'ADrpWUUCoWynuAYW258fmgBKcunWa4sa'),
        'base_url' => env('DOOVERA_BASE_URL', 'https://payment-dummy.doovera.com/api/v1'),
        'webhook_secret' => env('DOOVERA_WEBHOOK_SECRET', 'oaMOxFA2fSDWU8wAX1tiV19vGK7irMtt'),
    ],
    
    'currency' => 'IDR',
    'timeout' => 30, // API timeout in seconds
    'payment_expiry_hours' => 24,
    
    // Minimum payment amount
    'min_amount' => 1000,
    
    // Maximum payment amount
    'max_amount' => 100000000, // 100 million IDR
    
    // Auto-cancel expired payments (minutes)
    'auto_cancel_minutes' => 60,
];