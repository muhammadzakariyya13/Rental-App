<?php

namespace App\Console\Commands;

use App\Services\PaymentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestPaymentGateway extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:test {--clear-cache : Clear the cached gateway status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the payment gateway connection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Clear cache if requested
        if ($this->option('clear-cache')) {
            $this->info('Clearing payment gateway cache...');
            cache()->forget('payment_gateway_accessible');
        }

        $this->info('Testing payment gateway connection...');
        
        $apiKey = config('payment.api_key');
        $webhookSecret = config('payment.webhook_secret');
        $merchantCode = config('payment.merchant_code');
        $gatewayUrl = config('payment.gateway_url');
        
        // Check configuration
        $this->info('Configuration:');
        $this->info('- Gateway URL: ' . $gatewayUrl);
        $this->info('- API Key: ' . (!empty($apiKey) ? 'Set (Hidden)' : 'Not set'));
        $this->info('- Webhook Secret: ' . (!empty($webhookSecret) ? 'Set (Hidden)' : 'Not set'));
        $this->info('- Merchant Code: ' . (!empty($merchantCode) ? $merchantCode : 'Not set'));
        
        // Check cached status
        if (cache()->has('payment_gateway_accessible')) {
            $cachedStatus = cache()->get('payment_gateway_accessible') ? 'Accessible' : 'Not accessible';
            $this->info('Cached Status: ' . $cachedStatus);
        } else {
            $this->info('Cached Status: Not cached');
        }
        
        $this->info('Testing basic connectivity...');
        try {
            $testResponse = Http::timeout(5)->get($gatewayUrl);
            $this->info('- Basic Connection: ' . ($testResponse->successful() ? 'Successful' : 'Failed'));
            $this->info('- Status Code: ' . $testResponse->status());
            
            if ($testResponse->successful()) {
                if (!empty($apiKey)) {
                    $this->info('Testing API connection...');
                    
                    try {                            $apiResponse = Http::timeout(5)
                                ->withHeaders([
                                    'X-API-Key' => $apiKey,
                                    'Content-Type' => 'application/json'
                                ])
                                ->get($gatewayUrl . '/api/status');
                            
                            $this->info('- API Connection: ' . ($apiResponse->successful() ? 'Successful' : 'Failed'));
                            $this->info('- API Status Code: ' . $apiResponse->status());
                            
                            if ($apiResponse->successful()) {
                                $this->info('- API Response: ' . json_encode($apiResponse->json()));
                            } else {
                                $this->error('- API Error: ' . $apiResponse->body());
                            }
                        } catch (\Exception $e) {
                            $this->error('- API Error: ' . $e->getMessage());
                        }
                        
                        // Test merchant API if merchant code is set
                        if (!empty($merchantCode)) {
                            $this->info('Testing merchant API...');
                            
                            try {
                                $merchantResponse = Http::timeout(5)
                                    ->withHeaders([
                                        'X-API-Key' => $apiKey,
                                        'Content-Type' => 'application/json'
                                    ])
                                    ->get($gatewayUrl . '/api/merchant/' . $merchantCode . '/status');
                            
                            $this->info('- Merchant API: ' . ($merchantResponse->successful() ? 'Successful' : 'Failed'));
                            $this->info('- Merchant Status Code: ' . $merchantResponse->status());
                            
                            if ($merchantResponse->successful()) {
                                $this->info('- Merchant Response: ' . json_encode($merchantResponse->json()));
                                
                                // Update cache
                                cache()->put('payment_gateway_accessible', true, now()->addMinutes(5));
                            } else {
                                $this->error('- Merchant API Error: ' . $merchantResponse->body());
                            }
                        } catch (\Exception $e) {
                            $this->error('- Merchant API Error: ' . $e->getMessage());
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->error('Connection error: ' . $e->getMessage());
        }
        
        $paymentService = new PaymentService();
        $isAccessible = $paymentService->isGatewayAccessible();
        $this->info('Payment Service Gateway Status: ' . ($isAccessible ? 'Accessible' : 'Not accessible'));
        
        return 0;
    }
}
