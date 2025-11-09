<?php

namespace App\Services;

use App\Models\Pemesanan;
use Illuminate\Support\Facades\Log;

class DirectPaymentService
{
    /**
     * Process a direct payment (no gateway)
     *
     * @param Pemesanan $booking
     * @param string $type The type of direct payment ('simulated', 'offline', etc.)
     * @return bool
     */
    public static function processPayment(Pemesanan $booking, $type = 'simulated')
    {
        try {
            switch ($type) {
                case 'simulated':
                    // Simulated payment - mark as completed
                    $booking->status = 'confirmed';
                    $booking->payment_status = 'completed';
                    $booking->payment_transaction_id = config('payment.transaction_prefixes.simulated', 'SIM-') . uniqid();
                    
                    // Update property status
                    $property = $booking->properti;
                    if ($property) {
                        $property->status = 'disewa';
                        $property->save();
                    }
                    
                    Log::info('Simulated payment processed successfully', [
                        'booking_id' => $booking->id_pemesanan,
                        'transaction_id' => $booking->payment_transaction_id
                    ]);
                    break;
                    
                case 'offline':
                    // Offline payment - mark as pending but booking as confirmed
                    $booking->status = 'confirmed';
                    $booking->payment_status = 'pending';
                    $booking->payment_transaction_id = config('payment.transaction_prefixes.offline', 'OFFLINE-') . uniqid();
                    
                    Log::info('Offline payment option selected', [
                        'booking_id' => $booking->id_pemesanan,
                        'transaction_id' => $booking->payment_transaction_id
                    ]);
                    break;
                    
                default:
                    // Default direct payment - mark as completed
                    $booking->status = 'confirmed';
                    $booking->payment_status = 'completed';
                    $booking->payment_transaction_id = 'DIRECT-' . uniqid();
                    
                    // Update property status
                    $property = $booking->properti;
                    if ($property) {
                        $property->status = 'disewa';
                        $property->save();
                    }
                    
                    Log::info('Direct payment processed successfully', [
                        'booking_id' => $booking->id_pemesanan,
                        'transaction_id' => $booking->payment_transaction_id
                    ]);
            }
            
            $booking->save();
            return true;
        } catch (\Exception $e) {
            Log::error('Direct payment processing failed', [
                'booking_id' => $booking->id_pemesanan,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }
}
