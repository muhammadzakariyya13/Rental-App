<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestPaymentController extends Controller
{
    /**
     * Constructor - Explicitly skip auth middleware for this controller
     */
    public function __construct()
    {
        // This controller doesn't require authentication
        // This helps ensure it works with ngrok or other external access
    }
    
    /**
     * Show test payment form
     * 
     * @return \Illuminate\View\View
     */    public function showForm()
    {
        // Use the public version of the payment test page that doesn't require auth
        return view('test-payment-public');
    }
      /**
     * Test payment gateway connection
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testConnection(Request $request)
    {
        try {
            $apiKey = config('payment.api_key');
            $webhookSecret = config('payment.webhook_secret');
            $gatewayUrl = config('payment.gateway_url');
            
            $response = [
                'success' => !empty($apiKey) && !empty($gatewayUrl),
                'message' => 'Payment configuration found',
                'api_key' => !empty($apiKey) ? 'Set (Hidden)' : 'Not set',
                'webhook_secret' => !empty($webhookSecret) ? 'Set (Hidden)' : 'Not set',
                'gateway_url' => $gatewayUrl ?? 'Not set',
                'connection_test' => 'Not performed',
                'cache_test' => 'Not performed'
            ];
            
            // Check for cached connectivity result
            if (cache()->has('payment_gateway_accessible')) {
                $response['cache_test'] = 'Found';
                $response['cached_result'] = cache()->get('payment_gateway_accessible') ? 'Accessible' : 'Not accessible';
                $response['cache_message'] = 'Using cached gateway accessibility status. You may need to clear the cache to force a fresh test.';
            } else {
                $response['cache_test'] = 'Not found';
            }
            
            // Check connection directly
            if (!empty($gatewayUrl)) {
                try {
                    $testResponse = \Illuminate\Support\Facades\Http::timeout(5)->get($gatewayUrl);
                    $response['connection_test'] = $testResponse->successful() ? 'Successful' : 'Failed';
                    $response['status_code'] = $testResponse->status();
                              // Try an API endpoint if base URL is successful
                    if ($testResponse->successful()) {
                        try {
                            $merchantCode = config('payment.merchant_code');
                            
                            // Add merchant code to the response
                            $response['merchant_code'] = !empty($merchantCode) ? $merchantCode : 'Not set';
                              // Updated to use the X-API-Key header and proper endpoint format
                            $apiResponse = \Illuminate\Support\Facades\Http::timeout(5)
                                ->withHeaders([
                                    'X-API-Key' => $apiKey,
                                    'Content-Type' => 'application/json'
                                ])
                                ->get($gatewayUrl . '/api/status');
                                
                            $response['api_test'] = $apiResponse->successful() ? 'Successful' : 'Failed';
                            $response['api_status_code'] = $apiResponse->status();
                            
                            // If we have a merchant code, also test merchant-specific endpoint
                            if (!empty($merchantCode)) {
                                $merchantResponse = \Illuminate\Support\Facades\Http::timeout(5)
                                    ->withHeaders([
                                        'X-API-Key' => $apiKey,
                                        'Content-Type' => 'application/json'
                                    ])
                                    ->get($gatewayUrl . '/api/merchant/' . $merchantCode . '/status');
                                    
                                $response['merchant_test'] = $merchantResponse->successful() ? 'Successful' : 'Failed';
                                $response['merchant_status_code'] = $merchantResponse->status();
                                
                                if ($merchantResponse->successful()) {
                                    $response['merchant_data'] = $merchantResponse->json();
                                }
                            }
                            
                            // Update cache with fresh result - set as accessible if either general API or merchant API works
                            $isAccessible = $apiResponse->successful() || 
                                (isset($merchantResponse) && $merchantResponse->successful());
                            cache()->put('payment_gateway_accessible', $isAccessible, now()->addMinutes(5));
                            $response['cache_updated'] = true;
                            
                        } catch (\Exception $e) {
                            $response['api_test'] = 'Failed';
                            $response['api_error'] = $e->getMessage();
                        }
                    }
                } catch (\Exception $e) {
                    $response['connection_test'] = 'Failed';
                    $response['connection_error'] = $e->getMessage();
                    
                    // Update cache with failed result
                    cache()->put('payment_gateway_accessible', false, now()->addMinutes(2));
                    $response['cache_updated'] = true;
                }
            }
            
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error while testing payment configuration',
                'error' => $e->getMessage()
            ], 500);
        }
    }
      /**
     * Simulate payment webhook
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function simulateWebhook(Request $request)
    {
        try {
            $request->validate([
                'transaction_id' => 'required|string',
                'status' => 'required|string|in:completed,failed,cancelled,processing,pending',
            ]);
            
            // Check if the transaction exists in a booking
            $booking = \App\Models\Pemesanan::where('payment_transaction_id', $request->transaction_id)->first();
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'No booking found with transaction ID: ' . $request->transaction_id,
                    'suggestion' => 'Make sure you enter a valid transaction ID from an existing booking'
                ], 404);
            }
            
            // Add a webhook signature to simulate a real payment gateway call
            $payload = json_encode([
                'transaction_id' => $request->transaction_id,
                'status' => $request->status,
                'amount' => $request->amount ?? $booking->total_harga ?? 0,
                'payment_method' => $request->payment_method ?? $booking->metode_pembayaran ?? 'test',
                'timestamp' => now()->toIso8601String(),
                'booking_id' => $booking->id_pemesanan,
            ]);
            
            // Generate a signature using the webhook secret
            $webhookSecret = config('payment.webhook_secret');
            $signature = hash_hmac('sha256', $payload, $webhookSecret);
            
            // Log the webhook simulation attempt
            Log::info('Test webhook simulation', [
                'transaction_id' => $request->transaction_id,
                'status' => $request->status,
                'signature' => $signature
            ]);
            
            $paymentService = new PaymentService();
            
            // First verify the signature
            $signatureValid = $paymentService->verifyWebhook($signature, $payload);
            
            if (!$signatureValid && !app()->environment('local')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Webhook signature verification failed',
                    'signature' => $signature,
                ], 400);
            }
            
            // Process the webhook payload
            $result = $paymentService->processWebhookPayload(json_decode($payload, true));
            
            return response()->json([
                'success' => $result,
                'message' => $result ? 'Webhook processed successfully' : 'Failed to process webhook',
                'booking_status' => $booking->refresh()->status,
                'payment_status' => $booking->payment_status,
                'property_status' => $booking->properti->status ?? 'unknown',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing simulated webhook',
                'error' => $e->getMessage(),
                'trace' => app()->environment('local') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Simulate payment webhook via GET request for easy testing
     * 
     * @param string $transaction_id
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function simulateWebhookGet($transaction_id, $status)
    {
        // Create a request object with the parameters
        $request = new Request();
        $request->merge([
            'transaction_id' => $transaction_id,
            'status' => $status,
        ]);
        
        // Call the existing simulateWebhook method
        return $this->simulateWebhook($request);
    }
}
