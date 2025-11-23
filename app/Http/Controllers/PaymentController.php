<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Pemesanan;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Show payment form
     */
    public function create(Request $request): View
    {
        $orderId = $request->get('order_id');
        $amount = $request->get('amount');
        
        // Get pemesanan if order_id provided
        $pemesanan = null;
        if ($orderId) {
            $pemesanan = Pemesanan::where('id_pemesanan', $orderId)->first();
        }

        $paymentMethods = $this->paymentService->getPaymentMethods();

        return view('payment.create', compact('orderId', 'amount', 'pemesanan', 'paymentMethods'));
    }

    /**
     * Create new payment
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000', // Minimum IDR 1,000
            'method' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate amount against pemesanan if order_id provided
        if ($request->order_id && is_numeric($request->order_id)) {
            $pemesanan = Pemesanan::find($request->order_id);
            if ($pemesanan && $pemesanan->total_harga != $request->amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment amount. Amount has been tampered with.',
                ], 422);
            }
        }

        // Check if payment already exists for this order
        $existingPayment = Payment::where('order_id', $request->order_id)
                                 ->whereIn('status', [Payment::STATUS_PENDING, Payment::STATUS_PAID])
                                 ->first();

        if ($existingPayment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment already exists for this order',
                'payment_id' => $existingPayment->id,
            ], 409);
        }

        try {
            $result = $this->paymentService->createPayment([
                'order_id' => $request->order_id,
                'amount' => $request->amount,
                'method' => $request->method,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'description' => $request->description,
                'notes' => $request->notes,
            ]);

            if ($result['success']) {
                $response = [
                    'success' => true,
                    'message' => 'Payment created successfully',
                    'payment_id' => $result['payment']->id,
                    'payment_url' => $result['payment_url'] ?? null,
                    'redirect_url' => route('payment.show', $result['payment']->id),
                ];
                
                // Log response for debugging
                Log::info('Payment creation response', $response);
                
                return response()->json($response);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 500);

        } catch (\Exception $e) {
            Log::error('Payment creation failed', [
                'request' => $request->all(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment creation failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Show payment details and status
     */
    public function show(Payment $payment): View
    {
        // Check payment status from API
        $this->paymentService->checkPaymentStatus($payment);
        $payment = $payment->fresh();

        return view('payment.show', compact('payment'));
    }

    /**
     * Get payment status (AJAX)
     */
    public function status(Payment $payment): JsonResponse
    {
        $result = $this->paymentService->checkPaymentStatus($payment);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'payment' => [
                    'id' => $result['payment']->id,
                    'status' => $result['payment']->status,
                    'amount' => $result['payment']->formatted_amount,
                    'paid_at' => $result['payment']->paid_at?->format('Y-m-d H:i:s'),
                    'expired_at' => $result['payment']->expired_at->format('Y-m-d H:i:s'),
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 500);
    }

    /**
     * Cancel payment
     */
    public function cancel(Payment $payment): JsonResponse
    {
        if (!$payment->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment cannot be cancelled',
            ], 400);
        }

        $result = $this->paymentService->cancelPayment($payment);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Payment cancelled successfully',
                'payment' => $result['payment'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 500);
    }

    /**
     * Handle webhook from payment gateway
     */
    public function webhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Doovera-Signature');

        // Verify webhook signature
        if (!$this->paymentService->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Invalid webhook signature', [
                'signature' => $signature,
                'payload_length' => strlen($payload),
            ]);

            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $webhookData = json_decode($payload, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Invalid webhook JSON', ['payload' => $payload]);
            return response()->json(['message' => 'Invalid JSON'], 400);
        }

        $result = $this->paymentService->handleWebhook($webhookData);

        if ($result['success']) {
            // Send notification if payment status changed
            if (in_array($result['payment']->status, [Payment::STATUS_PAID, Payment::STATUS_FAILED])) {
                // TODO: Send email notification
                // TODO: Send WhatsApp/SMS notification if needed
                Log::info('Payment status updated', [
                    'payment_id' => $result['payment']->id,
                    'new_status' => $result['payment']->status,
                ]);
            }

            return response()->json(['message' => 'Webhook processed successfully']);
        }

        return response()->json(['message' => $result['message']], 400);
    }

    /**
     * Payment success page
     */
    public function success(Request $request): View
    {
        $paymentId = $request->get('payment_id');
        $payment = null;

        if ($paymentId) {
            $payment = Payment::find($paymentId);
        }

        return view('payment.success', compact('payment'));
    }

    /**
     * Payment cancelled page
     */
    public function cancelled(Request $request): View
    {
        $paymentId = $request->get('payment_id');
        $payment = null;

        if ($paymentId) {
            $payment = Payment::find($paymentId);
        }

        return view('payment.cancelled', compact('payment'));
    }

    /**
     * Payment history for authenticated user
     */
    public function history(): View
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $payments = Payment::forCustomer($user->email)
                          ->orderByDesc('created_at')
                          ->paginate(10);

        return view('payment.history', compact('payments'));
    }

    /**
     * Download payment receipt (PDF)
     */
    public function receipt(Payment $payment)
    {
        // Check if user has access to this payment
        if (Auth::user() && Auth::user()->email !== $payment->customer_email) {
            abort(403, 'Unauthorized access to payment receipt');
        }

        if (!$payment->isPaid()) {
            abort(404, 'Receipt not available for unpaid payments');
        }

        return view('payment.receipt', compact('payment'));
    }

    /**
     * Admin: List all payments
     */
    public function adminIndex(Request $request): View
    {
        $query = Payment::with(['pemesanan', 'akun'])->orderByDesc('created_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order ID, customer name, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(20);

        // Calculate statistics
        $stats = [
            'total_amount' => Payment::sum('amount'),
            'paid_amount' => Payment::paid()->sum('amount'),
            'pending_amount' => Payment::pending()->sum('amount'),
            'total_count' => Payment::count(),
            'paid_count' => Payment::paid()->count(),
            'pending_count' => Payment::pending()->count(),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * Admin: Show payment details
     */
    public function adminShow(Payment $payment): View
    {
        $payment->load(['pemesanan', 'akun']);
        
        // Get latest status from API
        $this->paymentService->checkPaymentStatus($payment);
        $payment = $payment->fresh();

        return view('admin.payments.show', compact('payment'));
    }
}
