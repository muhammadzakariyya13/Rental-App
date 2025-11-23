<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:cancel-expired 
                            {--dry-run : Show what would be cancelled without actually cancelling}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel expired payments that are past their expiry time';

    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        parent::__construct();
        $this->paymentService = $paymentService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for expired payments...');

        // Get expired payments that are still pending
        $expiredPayments = Payment::where('expired_at', '<=', now())
                                 ->where('status', Payment::STATUS_PENDING)
                                 ->get();

        if ($expiredPayments->isEmpty()) {
            $this->info('No expired payments found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$expiredPayments->count()} expired payments.");

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN MODE - No payments will actually be cancelled');
            
            $headers = ['ID', 'Order ID', 'Amount', 'Customer', 'Expired At'];
            $rows = [];
            
            foreach ($expiredPayments as $payment) {
                $rows[] = [
                    $payment->id,
                    $payment->order_id,
                    $payment->formatted_amount,
                    $payment->customer_name,
                    $payment->expired_at->format('Y-m-d H:i:s'),
                ];
            }
            
            $this->table($headers, $rows);
            return Command::SUCCESS;
        }

        $cancelledCount = 0;
        $errorCount = 0;

        foreach ($expiredPayments as $payment) {
            try {
                $payment->markAsExpired();
                $cancelledCount++;
                
                $this->line("✓ Cancelled payment #{$payment->id} - {$payment->order_id}");
                
                // Log the cancellation
                Log::info('Auto-cancelled expired payment', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'amount' => $payment->amount,
                    'expired_at' => $payment->expired_at,
                ]);
                
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("✗ Failed to cancel payment #{$payment->id}: {$e->getMessage()}");
                
                Log::error('Failed to auto-cancel expired payment', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("\nSummary:");
        $this->info("- Cancelled: {$cancelledCount} payments");
        
        if ($errorCount > 0) {
            $this->warn("- Errors: {$errorCount} payments");
        }

        return Command::SUCCESS;
    }
}
