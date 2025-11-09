<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/payment/callback',
        '/test-payment/webhook',
        '/api/webhook/payment',
        '/api/payment/webhook',
        '/api/webhook',
        '/debug/update-payment-status',
        '/debug/check-payment-status', 
        '/webhook',
        '/api/payment/webhook',
        '/webhook/test',
        // Added more patterns to catch all webhook routes
        'webhook/*',
        'api/webhook/*',
        'api/*/webhook',
        '*/webhook/*',
    ];
    
    /**
     * Override the inExceptArray method to better handle webhook URIs
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        // Check if the request is a payment webhook by examining headers
        $isWebhook = $request->header('X-Webhook-Signature') !== null || 
                     $request->header('X-Payment-Signature') !== null ||
                     $request->header('X-Payment-Webhook') !== null;
                     
        // Always exclude webhooks from CSRF verification
        if ($isWebhook) {
            return true;
        }
        
        // Call the parent method to check the except array
        return parent::inExceptArray($request);
    }
}
