<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Properti;
use App\Models\Pemesanan;
use App\Services\PaymentService;
use App\Services\DirectPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Show the booking form for a specific property.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function create($id)
    {
        $property = Properti::findOrFail($id);
        
        // Check if property is available
        if ($property->status !== 'tersedia') {
            return redirect()->route('penyewa.browse')->with('error', 'Properti ini tidak tersedia untuk disewa.');
        }
        
        return view('penyewa.pemesanan.create', compact('property'));
    }

    /**
     * Store a new booking in the database.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $id)
    {
        $property = Properti::findOrFail($id);
        
        // Validate request
        $validated = $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|string|in:transfer,kartu_kredit,tunai',
            'catatan' => 'nullable|string|max:500',
        ]);
          $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        // Ensure durasi is converted to integer before using it with addDays
        $durasi = (int)$validated['durasi'];
        $tanggalSelesai = $tanggalMulai->copy()->addDays($durasi);
        
        // Calculate total price (daily rate * duration)
        // Assuming the price in the database is the property value, we'll use a rental rate of 0.01% of property value per day
        $dailyRate = $property->harga * 0.0001;
        $totalHarga = $dailyRate * $durasi;
        
        // Create new booking
        $booking = new Pemesanan();
        $booking->id_properti = $property->id_properti;
        $booking->id_penyewa = Auth::id();
        $booking->tanggal_mulai = $tanggalMulai;
        $booking->tanggal_selesai = $tanggalSelesai;        $booking->durasi = $durasi;
        $booking->total_harga = $totalHarga;
        $booking->status = 'pending';
        $booking->metode_pembayaran = $validated['metode_pembayaran'];
        $booking->catatan = $validated['catatan'] ?? '';
        $booking->save();
        
        // Redirect to payment page
        return redirect()->route('penyewa.payment.show', $booking->id_pemesanan);
    }
    
    /**
     * Show payment page for a booking.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function payment($id)
    {
        $booking = Pemesanan::with('properti')->findOrFail($id);
        
        // Ensure the booking belongs to the logged-in user
        if ($booking->id_penyewa != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('penyewa.pemesanan.payment', compact('booking'));
    }    /**
     * Process the payment.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */    public function processPayment(Request $request, $id)
    {
        $booking = Pemesanan::with(['properti', 'penyewa'])->findOrFail($id);
        
        // Ensure the booking belongs to the logged-in user
        if ($booking->id_penyewa != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        try {            // For demo purposes, we can simulate direct payment success without gateway
            if ($request->has('demo_success')) {
                // Use the direct payment service for simulation
                $result = DirectPaymentService::processPayment($booking, 'simulated');
                
                if ($result) {
                    return redirect()->route('penyewa.pemesanan.success', $booking->id_pemesanan)
                        ->with('success', 'Payment simulation successful! Your booking is confirmed.');
                } else {
                    return redirect()->route('penyewa.payment.show', $booking->id_pemesanan)
                        ->with('error', 'Simulated payment processing failed. Please try again.');
                }
            }
            
            // Offline payment option (pay later in person)
            if ($request->has('offline_payment')) {
                // Use the direct payment service for offline payment
                $result = DirectPaymentService::processPayment($booking, 'offline');
                
                if ($result) {
                    return redirect()->route('penyewa.pemesanan.success', $booking->id_pemesanan)
                        ->with('info', 'Your booking is confirmed. Please complete the payment at our office.');
                } else {
                    return redirect()->route('penyewa.payment.show', $booking->id_pemesanan)
                        ->with('error', 'Failed to process offline payment. Please try again.');
                }
            }
            
            // Integrate with payment gateway
            $paymentService = new PaymentService();
            $transaction = $paymentService->createTransaction($booking);
            
            if (!$transaction) {
                // Complete payment failure
                Log::error('Payment transaction creation completely failed for booking', [
                    'booking_id' => $booking->id_pemesanan,
                ]);
                
                return redirect()->route('penyewa.payment.show', $booking->id_pemesanan)
                    ->with('error', 'Payment processing failed. Please try the offline payment option.');
            }
            
            // Check if this is an offline transaction (gateway unavailable)
            if (isset($transaction['offline_mode']) && $transaction['offline_mode']) {
                Log::info('Payment gateway unavailable, using offline mode', [
                    'booking_id' => $booking->id_pemesanan,
                    'transaction_id' => $transaction['transaction_id'] ?? null
                ]);
                
                return redirect()->route('penyewa.pemesanan.success', $booking->id_pemesanan)
                    ->with('info', 'The payment gateway is currently unavailable. Your booking is saved and you can complete payment later.');
            }
            
            // Check if there was an error but we created a fallback transaction
            if (isset($transaction['error_mode']) && $transaction['error_mode']) {
                Log::warning('Payment gateway error, using fallback', [
                    'booking_id' => $booking->id_pemesanan,
                    'transaction_id' => $transaction['transaction_id'] ?? null,
                    'message' => $transaction['message'] ?? 'Unknown error'
                ]);
                
                return redirect()->route('penyewa.payment.show', $booking->id_pemesanan)
                    ->with('error', $transaction['message'] ?? 'Payment processing error. Please try again or use offline payment.');
            }
            
            // Normal flow - check if we need to redirect to payment gateway
            if (isset($transaction['payment_url'])) {
                // If payment requires redirect (like for credit card)
                return redirect()->away($transaction['payment_url']);
            } else {
                // If payment was processed directly
                $booking->payment_status = 'processing';
                $booking->save();
                
                return redirect()->route('penyewa.pemesanan.success', $booking->id_pemesanan);
            }
        } catch (\Exception $e) {
            Log::error('Payment processing exception', [
                'booking_id' => $booking->id_pemesanan,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Create emergency transaction record in case of complete failure
            $booking->payment_transaction_id = 'EMERGENCY-' . uniqid();
            $booking->payment_status = 'error';
            $booking->save();
            
            return redirect()->route('penyewa.payment.show', $booking->id_pemesanan)
                ->with('error', 'An error occurred during payment processing. Please try again later or contact support.');
        }
    }
    
    /**
     * Show booking success page.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function success($id)
    {
        $booking = Pemesanan::with('properti')->findOrFail($id);
        
        // Ensure the booking belongs to the logged-in user
        if ($booking->id_penyewa != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('penyewa.pemesanan.success', compact('booking'));
    }
    
    /**
     * Display a listing of the user's bookings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bookings = Pemesanan::with('properti')
            ->where('id_penyewa', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('penyewa.pemesanan.index', compact('bookings'));
    }
    
    /**
     * Display the specified booking.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $booking = Pemesanan::with('properti')->findOrFail($id);
        
        // Ensure the booking belongs to the logged-in user
        if ($booking->id_penyewa != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('penyewa.pemesanan.show', compact('booking'));
    }
    
    /**
     * Cancel a booking.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */    public function cancel($id)
    {
        $booking = Pemesanan::findOrFail($id);
        
        // Ensure the booking belongs to the logged-in user
        if ($booking->id_penyewa != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only pending bookings can be cancelled
        if ($booking->status != 'pending') {
            return redirect()->route('penyewa.pemesanan.index')->with('error', 'Hanya pemesanan dengan status pending yang dapat dibatalkan.');
        }
        
        $booking->status = 'cancelled';
        $booking->save();
        
        return redirect()->route('penyewa.pemesanan.index')->with('success', 'Pemesanan berhasil dibatalkan.');
    }
      /**
     * Handle payment gateway callback.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function paymentCallback(Request $request)
    {
        Log::info('Payment callback received', [
            'headers' => $request->headers->all(),
            'content_type' => $request->header('Content-Type'),
            'data_size' => strlen($request->getContent()),
            'ip' => $request->ip(),
            'method' => $request->method()
        ]);
        
        // Jika ini adalah GET request, tampilkan halaman informasi
        if ($request->isMethod('get')) {
            return response()->json([
                'status' => 'info',
                'message' => 'Webhook endpoint aktif. Endpoint ini digunakan untuk menerima notifikasi pembayaran dari payment gateway.',
                'info' => 'Akses melalui browser akan menampilkan pesan ini. Webhook sebenarnya harus menggunakan metode POST.'
            ]);
        }
        
        try {
            $paymentService = new PaymentService();
              // Verify webhook signature
            $signature = $request->header('X-Webhook-Signature');
            $payload = $request->getContent();
            
            if (empty($payload)) {
                Log::error('Empty webhook payload received');
                return response()->json(['error' => 'Empty payload'], 400);
            }
            
            // Verify the webhook signature
            if (!$paymentService->verifyWebhook($signature, $payload)) {
                Log::error('Invalid webhook signature', [
                    'received_signature' => $signature,
                    'payload_size' => strlen($payload),
                    'webhook_secret_configured' => !empty(config('payment.webhook_secret')),
                ]);
                
                // Only reject in production; allow in local environment for testing
                if (!app()->environment('local')) {
                    return response()->json(['error' => 'Invalid signature'], 400);
                } else {
                    Log::warning('Proceeding with invalid signature in local environment');
                }
            }
            
            // Parse the payload as JSON
            try {
                $data = json_decode($payload, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Invalid JSON payload', [
                        'error' => json_last_error_msg(),
                        'payload_excerpt' => substr($payload, 0, 100) . (strlen($payload) > 100 ? '...' : '')
                    ]);
                    return response()->json(['error' => 'Invalid JSON payload'], 400);
                }
            } catch (\Exception $e) {
                Log::error('Failed to parse webhook payload', [
                    'error' => $e->getMessage(),
                    'payload_excerpt' => substr($payload, 0, 100) . (strlen($payload) > 100 ? '...' : '')
                ]);
                return response()->json(['error' => 'Failed to parse payload'], 400);
            }
              // Process the webhook data
            $result = $paymentService->processWebhookPayload($data);
            
            if ($result === true) {
                Log::info('Webhook processed successfully', [
                    'transaction_id' => $data['transaction_id'] ?? $data['id'] ?? 'unknown',
                    'status' => $data['status'] ?? 'unknown'
                ]);
                return response()->json(['status' => 'success']);
            } else {
                // $result could be a string error message
                $errorMessage = is_string($result) ? $result : 'Failed to process webhook';
                
                Log::error('Failed to process webhook', [
                    'transaction_id' => $data['transaction_id'] ?? $data['id'] ?? 'unknown',
                    'error' => $errorMessage
                ]);
                return response()->json(['status' => 'error', 'message' => $errorMessage], 500);
            }
        } catch (\Exception $e) {
            Log::error('Webhook processing exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error during webhook processing'
            ], 500);
        }
    }
}