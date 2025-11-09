<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pemesanan;

class PaymentService
{
    protected $apiKey;
    protected $webhookSecret;
    protected $gatewayUrl;
    protected $merchantCode;

    public function __construct()
    {
        $this->apiKey = config('payment.api_key');
        $this->webhookSecret = config('payment.webhook_secret');
        $this->gatewayUrl = config('payment.gateway_url');
        $this->merchantCode = config('payment.merchant_code');
    }

    /**
     * Check if the payment gateway is accessible
     *
     * @return bool
     */    public function isGatewayAccessible()
    {
        // Check if we have a cached result
        if (cache()->has('payment_gateway_accessible')) {
            return cache()->get('payment_gateway_accessible');
        }
        
        try {
            // Just check the base URL since there's no specific health check endpoint in the docs
            $testResponse = Http::timeout(5)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'Accept' => 'application/json'
                ])
                ->get($this->gatewayUrl);
            
            // The payment gateway is considered accessible if we can reach the base URL
            $isAccessible = $testResponse->successful();
            
            // Cache the result for 5 minutes
            cache()->put('payment_gateway_accessible', $isAccessible, now()->addMinutes(5));
            
            if (!$isAccessible) {
                Log::error('Payment gateway is not accessible', [
                    'status' => $testResponse->status(),
                    'url' => $this->gatewayUrl,
                    'response' => $testResponse->body()
                ]);
            } else {
                Log::info('Payment gateway is accessible', [
                    'status' => $testResponse->status(),
                    'url' => $this->gatewayUrl
                ]);
            }
            
            return $isAccessible;
        } catch (\Exception $e) {
            Log::error('Failed to connect to payment gateway', [
                'url' => $this->gatewayUrl,
                'error' => $e->getMessage(),
                'error_type' => get_class($e)
            ]);
            
            // Cache the negative result for a shorter time (2 minutes)
            cache()->put('payment_gateway_accessible', false, now()->addMinutes(2));
            
            return false;
        }
    }

    /**
     * Create a payment transaction with the payment gateway
     *
     * @param Pemesanan $booking
     * @return array|null
     */
    public function createTransaction(Pemesanan $booking)
    {
        try {
            // Make sure we have the penyewa relationship loaded
            if (!$booking->relationLoaded('penyewa')) {
                $booking->load('penyewa');
            }
            
            // Get customer details
            $customerName = $booking->penyewa ? $booking->penyewa->username : 'Guest User';
            $customerEmail = $booking->penyewa ? $booking->penyewa->email : 'guest@example.com';
            
            // Check if this is a simulated payment
            if ($booking->metode_pembayaran === 'simulated') {
                Log::info("Simulating successful payment", [
                    'booking_id' => $booking->id_pemesanan,
                ]);
                
                // Create a simulated transaction record and mark as completed
                $transactionId = 'SIM-' . uniqid();
                $booking->payment_transaction_id = $transactionId;
                $booking->payment_status = 'completed';
                $booking->status = 'confirmed';
                $booking->save();
                
                // Update property status
                $property = $booking->properti;
                if ($property) {
                    $property->status = 'disewa';
                    $property->save();
                }
                
                return [
                    'transaction_id' => $transactionId,
                    'status' => 'completed',
                    'message' => 'Payment simulation successful',
                    'simulated_mode' => true
                ];
            }
            
            // Check if this is an offline payment
            if ($booking->metode_pembayaran === 'offline') {
                Log::info("Processing offline payment", [
                    'booking_id' => $booking->id_pemesanan,
                ]);
                
                // Create an offline transaction record
                $transactionId = 'OFFLINE-' . uniqid();
                $booking->payment_transaction_id = $transactionId;
                $booking->payment_status = 'pending';
                $booking->status = 'confirmed'; // We still confirm the booking
                $booking->save();
                
                return [
                    'transaction_id' => $transactionId,
                    'status' => 'pending',
                    'message' => 'Offline payment recorded successfully',
                    'offline_mode' => true
                ];
            }
            
            // Check if payment gateway is accessible
            $isGatewayAccessible = $this->isGatewayAccessible();
            
            // If gateway is not accessible, create a fallback transaction
            if (!$isGatewayAccessible) {
                Log::warning("Payment gateway is not accessible, using offline processing mode", [
                    'booking_id' => $booking->id_pemesanan,
                ]);
                
                // Create a local transaction record and provide a pending status
                $transactionId = 'OFFLINE-' . uniqid();
                $booking->payment_transaction_id = $transactionId;
                $booking->payment_status = 'pending';
                $booking->status = 'pending';
                $booking->save();
                
                return [
                    'transaction_id' => $transactionId,
                    'status' => 'pending',
                    'message' => 'Offline processing mode activated due to gateway unavailability',
                    'offline_mode' => true
                ];
            }            // Attempt to create a transaction with the payment gateway
            try {                // Create payload according to PG Dummy API specifications
                // Using the format from the documentation for /api/v1/virtual-account/create
                $payload = [
                    'external_id' => 'BOOK-' . $booking->id_pemesanan, // Add prefix for clarity
                    'amount' => (int)$booking->total_harga, // Ensure amount is an integer
                    'merchant_code' => $this->merchantCode,
                    'customer_name' => $customerName,
                    'customer_email' => $customerEmail,
                    'payment_method' => $this->mapPaymentMethod($booking->metode_pembayaran),
                    'description' => 'Payment for booking ' . $booking->properti->nama,
                    'callback_url' => route('penyewa.payment.callback'),
                    'success_redirect_url' => route('penyewa.pemesanan.success', $booking->id_pemesanan),
                    'failure_redirect_url' => route('penyewa.payment.show', $booking->id_pemesanan) . '?status=failed'
                ];                // Log the complete request being sent to the gateway for debugging
                Log::info('Sending payment gateway request', [
                    'url' => $this->gatewayUrl . '/api/v1/virtual-account/create',
                    'headers' => [
                        'X-API-Key' => '[REDACTED]',
                        'Content-Type' => 'application/json',
                    ],
                    'payload' => $payload
                ]);
                
                $response = Http::timeout(10)->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])->post($this->gatewayUrl . '/api/v1/virtual-account/create', $payload);if ($response->successful()) {
                    $data = $response->json();
                    // PG Dummy API returns data in a specific structure as per documentation
                    $responseData = $data['data'] ?? $data;
                    
                    // Get values from the response based on documented API structure
                    $transaction_id = $responseData['va_number'] ?? ($responseData['id'] ?? null);
                    $payment_url = $responseData['payment_url'] ?? null;
                    $expiry = $responseData['expired_at'] ?? null;
                    
                    // Save to the booking
                    $booking->payment_transaction_id = $transaction_id;
                    $booking->payment_url = $payment_url;
                    $booking->payment_status = 'pending';
                    $booking->save();
                    
                    Log::info('Payment transaction created successfully', [
                        'booking_id' => $booking->id_pemesanan,
                        'transaction_id' => $transaction_id,
                        'payment_url' => $payment_url,
                        'expiry' => $expiry,
                        'response' => $data
                    ]);
                    
                    return $data;
                } else {
                    $errorMessage = $response->body();
                    Log::error('Payment gateway error', [
                        'booking_id' => $booking->id_pemesanan,
                        'response_code' => $response->status(),
                        'error_message' => $errorMessage,
                        'request_data' => [
                            'amount' => $booking->total_harga,
                            'order_id' => $booking->id_pemesanan,
                            'payment_method' => $this->mapPaymentMethod($booking->metode_pembayaran),
                        ]
                    ]);
                    
                    // Create fallback transaction if payment gateway returns error
                    $transactionId = 'ERROR-' . uniqid();
                    $booking->payment_transaction_id = $transactionId;
                    $booking->payment_status = 'pending';
                    $booking->save();
                    
                    return [
                        'transaction_id' => $transactionId,
                        'status' => 'pending',
                        'message' => 'Payment gateway error, please try again later',
                        'error_mode' => true
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Payment gateway request exception', [
                    'booking_id' => $booking->id_pemesanan,
                    'message' => $e->getMessage(),
                ]);
                
                // Create fallback transaction if payment gateway request throws exception
                $transactionId = 'ERROR-' . uniqid();
                $booking->payment_transaction_id = $transactionId;
                $booking->payment_status = 'pending';
                $booking->save();
                
                return [
                    'transaction_id' => $transactionId,
                    'status' => 'pending',
                    'message' => 'Connection error, please try again later',
                    'error_mode' => true
                ];
            }
        } catch (\Exception $e) {
            Log::error('Payment exception', [
                'booking_id' => $booking->id_pemesanan,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Verify a webhook from the payment gateway
     *
     * @param string $signature
     * @param string $payload
     * @return bool
     */    public function verifyWebhook($signature, $payload)
    {
        // If signature is missing, log it but allow testing in local environment
        if (empty($signature) && app()->environment('local')) {
            Log::warning('Missing webhook signature in local environment, skipping verification');
            return true;
        }
        
        // If signature is missing in production, reject the webhook
        if (empty($signature) && !app()->environment('local')) {
            Log::error('Missing webhook signature in production environment');
            return false;
        }
        
        // HMAC verification using SHA-256 with the webhook secret
        $computedSignature = hash_hmac('sha256', $payload, $this->webhookSecret);
        
        // According to docs, the signature comes in X-Webhook-Signature header
        // Try direct comparison first
        $signatureMatches = hash_equals($computedSignature, $signature);
        
        // The signature might come with different formats or headers, try alternatives
        if (!$signatureMatches && strpos($signature, '=') !== false) {
            $parts = explode('=', $signature, 2);
            if (count($parts) == 2) {
                $signatureMatches = hash_equals($computedSignature, $parts[1]);
            }
        }
        
        // Log signature comparison for debugging
        Log::debug('Webhook signature verification', [
            'received' => $signature,
            'computed' => $computedSignature,
            'match' => $signatureMatches ? 'yes' : 'no'
        ]);
        
        return $signatureMatches;
    }    /**
     * Process the webhook payload
     *
     * @param array $data
     * @return bool
     */    public function processWebhookPayload(array $data)
    {
        try {
            // Log the raw webhook data for debugging
            Log::info('Webhook data received', [
                'data' => $data
            ]);
            
            // PG Dummy v2 API uses a specific notification structure
            // The transaction data is usually nested within data or transaction
            $transactionData = $data['data'] ?? $data['transaction'] ?? $data;
            
            // Get transaction ID from the appropriate field based on PG Dummy v2 API
            $transactionId = $transactionData['transaction_id'] ?? 
                            $transactionData['id'] ?? 
                            $transactionData['order_id'] ?? 
                            null;
            
            // Handle offline transactions (from fallback mechanism)
            if ($transactionId && (strpos($transactionId, 'OFFLINE-') === 0 || strpos($transactionId, 'SIM-') === 0)) {
                Log::info('Processing non-gateway transaction', [
                    'transaction_id' => $transactionId,
                    'status' => $transactionData['status'] ?? 'unknown',
                    'type' => strpos($transactionId, 'OFFLINE-') === 0 ? 'offline' : 'simulated'
                ]);
            }
            
            if (!$transactionId) {
                Log::error('No transaction ID found in webhook data');
                return false;
            }
            
            // Find the booking by transaction ID
            $booking = Pemesanan::where('payment_transaction_id', $transactionId)->first();
            
            if (!$booking) {
                // If the transaction ID starts with BOOK- prefix, we need to extract the booking ID
                if (strpos($transactionId, 'BOOK-') === 0) {
                    $bookingId = str_replace('BOOK-', '', $transactionId);
                    $booking = Pemesanan::where('id_pemesanan', $bookingId)->first();
                }
                
                // Try to find by order_id if provided
                if (!$booking && isset($transactionData['order_id'])) {
                    $orderId = $transactionData['order_id'];
                    if (strpos($orderId, 'BOOK-') === 0) {
                        $bookingId = str_replace('BOOK-', '', $orderId);
                        $booking = Pemesanan::where('id_pemesanan', $bookingId)->first();
                    }
                }
                
                if (!$booking) {
                    Log::error('Booking not found for transaction: ' . $transactionId);
                    return false;
                }
            }
            
            Log::info('Found booking for transaction', [
                'transaction_id' => $transactionId,
                'booking_id' => $booking->id_pemesanan
            ]);
            
            // Get the payment status from PG Dummy v2 API structure
            $status = $transactionData['status'] ?? 
                      $transactionData['transaction_status'] ?? 
                      $transactionData['payment_status'] ?? 
                      'unknown';
            
            switch ($status) {
                case 'completed':
                case 'success':
                case 'paid':
                case 'settlement':
                    $booking->status = 'confirmed';
                    $booking->payment_status = 'paid';
                    
                    // Update property status to "disewa"
                    $property = $booking->properti;
                    if ($property) {
                        $property->status = 'disewa';
                        $property->save();
                    }
                    break;
                case 'failed':
                case 'rejected':
                case 'error':
                case 'deny':
                    $booking->status = 'cancelled';
                    break;
                case 'cancelled':
                case 'expired':
                case 'cancel':
                    $booking->status = 'cancelled';
                    break;
                case 'pending':
                case 'processing':
                case 'challenge': // For card transactions that need verification
                    // Keep as pending
                    $booking->status = 'pending';
                    break;
                default:
                    // Log unknown status
                    Log::warning('Unknown payment status received', [
                        'status' => $status,
                        'transaction_id' => $transactionData['id'] ?? $transactionData['transaction_id'] ?? 'unknown'
                    ]);
                    break;
            }
            
            $booking->payment_status = $status;
            $booking->save();
            
            Log::info('Webhook processed successfully', [
                'transaction_id' => $data['transaction_id'],
                'status' => $data['status'],
                'booking_id' => $booking->id_pemesanan
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage(), [
                'transaction_id' => $data['transaction_id'] ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }    /**
     * Map internal payment method to gateway payment method
     *
     * @param string $method
     * @return string
     */
    protected function mapPaymentMethod($method)
    {
        $map = [
            'transfer' => 'bank_transfer',
            'kartu_kredit' => 'credit_card',
            'tunai' => 'ewallet',
            'virtual_account' => 'virtual_account',
        ];

        return $map[$method] ?? 'bank_transfer';
    }
}
