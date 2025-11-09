<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;
    
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
      /**
     * Test the payment gateway API connection
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testConnection()
    {
        $isAccessible = $this->paymentService->isGatewayAccessible();
        
        return response()->json([
            'success' => $isAccessible,
            'message' => $isAccessible 
                ? 'Successfully connected to payment gateway' 
                : 'Failed to connect to payment gateway',
            'api_key_set' => !empty(config('payment.api_key')),
            'webhook_secret_set' => !empty(config('payment.webhook_secret')),
            'gateway_url' => config('payment.gateway_url'),
            'merchant_code' => config('payment.merchant_code'),
        ]);
    }
    
    /**
     * Create a test transaction
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTestTransaction(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'payment_method' => 'required|string|in:bank_transfer,credit_card,cash',
                'description' => 'nullable|string'
            ]);
            
            // Create a dummy booking object that mimics the Pemesanan model
            $booking = new \stdClass();
            $booking->id_pemesanan = 'TEST-' . time();
            $booking->total_harga = $request->amount;
            $booking->metode_pembayaran = $this->mapToInternalPaymentMethod($request->payment_method);
            $booking->properti = new \stdClass();
            $booking->properti->nama = $request->description ?? 'Test Property';
            $booking->payment_transaction_id = null;
            $booking->payment_status = null;
            $booking->payment_url = null;
            $booking->status = 'pending';
            
            // Add save method to the booking object
            $booking->save = function() {
                // Do nothing, this is a mock
                return true;
            };
            
            // Add penyewa relation
            $booking->penyewa = new \stdClass();
            $booking->penyewa->username = $request->customer_name;
            $booking->penyewa->email = $request->customer_email;
            
            // Add relationLoaded method to check if a relation is loaded
            $booking->relationLoaded = function($relation) {
                return true;
            };
            
            // Create transaction
            $result = $this->paymentService->createTransaction($booking);
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test transaction created successfully',
                    'data' => [
                        'transaction_id' => $booking->payment_transaction_id,
                        'payment_url' => $booking->payment_url,
                        'payment_status' => $booking->payment_status,
                        'amount' => $booking->total_harga,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create test transaction',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error creating test transaction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Handle payment webhooks from the payment gateway
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Log the incoming webhook
            Log::info('Payment webhook received', [
                'headers' => $request->headers->all(),
                'ip' => $request->ip(),
                'method' => $request->method()
            ]);
            
            // Get the raw payload
            $payload = $request->getContent();
            
            // Get the webhook signature from various possible header names
            $signature = $request->header('X-Webhook-Signature') ??
                         $request->header('X-Payment-Signature') ??
                         $request->header('X-Signature') ??
                         '';
            
            // Verify the webhook signature
            if (!$this->paymentService->verifyWebhook($signature, $payload)) {
                Log::warning('Invalid webhook signature', [
                    'received' => $signature
                ]);
                
                // In local/development environments, allow invalid signatures for testing
                if (!app()->environment('local', 'development')) {
                    return response()->json(['error' => 'Invalid signature'], 401);
                }
                
                Log::info('Bypassing signature validation in development environment');
            }
            
            // Parse the payload
            $data = json_decode($payload, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid JSON payload in webhook', [
                    'error' => json_last_error_msg(),
                    'payload' => $payload
                ]);
                
                return response()->json(['error' => 'Invalid payload'], 400);
            }
            
            // Process the webhook
            $success = $this->paymentService->processWebhookPayload($data);
            
            if ($success) {
                return response()->json(['success' => true, 'message' => 'Webhook processed successfully']);
            } else {
                Log::warning('Failed to process webhook payload', [
                    'data' => $data
                ]);
                
                return response()->json(['success' => false, 'message' => 'Failed to process webhook'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error processing webhook', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
    
    /**
     * Map gateway payment method to internal payment method
     *
     * @param string $method
     * @return string
     */
    protected function mapToInternalPaymentMethod($method)
    {
        $map = [
            'bank_transfer' => 'transfer',
            'credit_card' => 'kartu_kredit',
            'cash' => 'tunai',
        ];
        
        return $map[$method] ?? 'transfer';
    }
}
