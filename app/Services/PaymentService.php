<?php

namespace App\Services;

use App\Models\Payment;
use App\Mail\PaymentSuccessNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PaymentService
{
    private string $apiKey;
    private string $baseUrl;
    private string $webhookSecret;

    public function __construct()
    {
        $this->apiKey = config('payment.doovera.api_key');
        $this->baseUrl = config('payment.doovera.base_url');
        $this->webhookSecret = config('payment.doovera.webhook_secret');
    }

    /**
     * Create payment via Doovera API
     */
    public function createPayment(array $paymentData): array
    {
        try {
            // Generate unique order ID if not provided
            $orderId = $paymentData['order_id'] ?? 'ORDER-' . time() . '-' . rand(1000, 9999);
            
            // Calculate expiry time (24 hours from now)
            $expiredAt = Carbon::now()->addHours(24);

            // Prepare API request
            $requestData = [
                'order_id' => $orderId,
                'amount' => $paymentData['amount'],
                'currency' => 'IDR',
                'description' => $paymentData['description'] ?? 'Payment for order ' . $orderId,
                'customer' => [
                    'name' => $paymentData['customer_name'],
                    'email' => $paymentData['customer_email'],
                    'phone' => $paymentData['customer_phone'],
                ],
                'expired_at' => $expiredAt->toISOString(),
                'callback_url' => route('payment.webhook'),
                'return_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
            ];

            // Send request to Doovera API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/payments', $requestData);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Create payment record in database
                $payment = Payment::create([
                    'order_id' => $orderId,
                    'payment_id' => $responseData['payment_id'] ?? null,
                    'amount' => $paymentData['amount'],
                    'status' => Payment::STATUS_PENDING,
                    'method' => $paymentData['method'] ?? null,
                    'customer_name' => $paymentData['customer_name'],
                    'customer_email' => $paymentData['customer_email'],
                    'customer_phone' => $paymentData['customer_phone'],
                    'expired_at' => $expiredAt,
                    'gateway_response' => $responseData,
                    'payment_url' => $responseData['payment_url'] ?? null,
                    'notes' => $paymentData['notes'] ?? null,
                ]);

                return [
                    'success' => true,
                    'payment' => $payment,
                    'payment_url' => $responseData['payment_url'] ?? null,
                    'payment_id' => $responseData['payment_id'] ?? null,
                ];
            }

            Log::error('Doovera API Error', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Payment gateway error: ' . $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('Payment Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check payment status from Doovera API
     */
    public function checkPaymentStatus(Payment $payment): array
    {
        try {
            if (!$payment->payment_id) {
                return [
                    'success' => false,
                    'message' => 'Payment ID not found',
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(30)->get($this->baseUrl . '/payments/' . $payment->payment_id);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Update payment status based on API response
                $this->updatePaymentFromApiResponse($payment, $responseData);

                return [
                    'success' => true,
                    'payment' => $payment->fresh(),
                    'api_response' => $responseData,
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to check payment status: ' . $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('Payment Status Check Error', [
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to check payment status: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Cancel payment via API
     */
    public function cancelPayment(Payment $payment): array
    {
        try {
            if (!$payment->canBeCancelled()) {
                return [
                    'success' => false,
                    'message' => 'Payment cannot be cancelled',
                ];
            }

            if ($payment->payment_id) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])->timeout(30)->post($this->baseUrl . '/payments/' . $payment->payment_id . '/cancel');

                if (!$response->successful()) {
                    Log::warning('API Cancel Failed', [
                        'payment_id' => $payment->id,
                        'api_response' => $response->body(),
                    ]);
                }
            }

            // Cancel locally regardless of API response
            $payment->cancel();

            return [
                'success' => true,
                'payment' => $payment->fresh(),
            ];

        } catch (\Exception $e) {
            Log::error('Payment Cancellation Error', [
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to cancel payment: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, $this->webhookSecret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Handle webhook notification
     */
    public function handleWebhook(array $webhookData): array
    {
        try {
            $paymentId = $webhookData['payment_id'] ?? null;
            
            if (!$paymentId) {
                return [
                    'success' => false,
                    'message' => 'Payment ID not found in webhook',
                ];
            }

            $payment = Payment::where('payment_id', $paymentId)->first();
            
            if (!$payment) {
                return [
                    'success' => false,
                    'message' => 'Payment not found',
                ];
            }

            // Update payment status based on webhook data
            $this->updatePaymentFromApiResponse($payment, $webhookData);

            return [
                'success' => true,
                'payment' => $payment->fresh(),
            ];

        } catch (\Exception $e) {
            Log::error('Webhook Handling Error', [
                'webhook_data' => $webhookData,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to handle webhook: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Update payment status from API response
     */
    private function updatePaymentFromApiResponse(Payment $payment, array $apiResponse): void
    {
        $apiStatus = $apiResponse['status'] ?? '';
        
        $previousStatus = $payment->status;
        
        switch (strtolower($apiStatus)) {
            case 'success':
            case 'paid':
            case 'completed':
                $payment->markAsPaid($apiResponse);
                
                // Send success email notification if status changed
                if ($previousStatus !== Payment::STATUS_PAID) {
                    try {
                        Mail::send(new PaymentSuccessNotification($payment));
                    } catch (\Exception $e) {
                        Log::error('Failed to send payment success email', [
                            'payment_id' => $payment->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
                break;
            
            case 'failed':
                $payment->markAsFailed($apiResponse);
                break;
            
            case 'expired':
                $payment->markAsExpired();
                break;
            
            case 'cancelled':
                $payment->cancel();
                break;
            
            default:
                // Update gateway response but keep current status
                $payment->update([
                    'gateway_response' => array_merge($payment->gateway_response ?? [], $apiResponse),
                ]);
        }
    }

    /**
     * Auto-cancel expired payments
     */
    public function cancelExpiredPayments(): int
    {
        $expiredPayments = Payment::expiring()->get();
        $cancelledCount = 0;

        foreach ($expiredPayments as $payment) {
            $payment->markAsExpired();
            $cancelledCount++;
        }

        return $cancelledCount;
    }

    /**
     * Get payment methods available
     */
    public function getPaymentMethods(): array
    {
        return [
            'bank_transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
            'e_wallet' => 'E-Wallet',
            'virtual_account' => 'Virtual Account',
            'qris' => 'QRIS',
        ];
    }
}