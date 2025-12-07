<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Properti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class PemesananController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Pemesanan::where('id_akun', $user->id)
            ->with(['properti', 'akun'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pemesanan', $request->status);
        }

        $pemesanan = $query->paginate(10);

        // Auto-sync status dari Midtrans untuk pemesanan yang belum selesai
        foreach ($pemesanan as $item) {
            // Sync jika status pending atau belum bayar dan ada transaction_id
            if ($item->status_pemesanan == 'pending' && $item->transaction_id) {
                $this->syncPaymentStatus($item);
            }
        }

        // Reload data setelah sync
        $pemesanan = $query->paginate(10);

        return view('penyewa.pemesanan.index', compact('pemesanan'));
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with(['properti', 'akun'])->findOrFail($id);
        
        // Check authorization
        if ($pemesanan->id_akun != Auth::user()->id) {
            abort(403, 'Unauthorized');
        }

        return view('penyewa.pemesanan.show', compact('pemesanan'));
    }

    public function create($id_properti)
    {
        $properti = Properti::findOrFail($id_properti);
        
        // Check if property is available
        if ($properti->status != 'tersedia') {
            return redirect()->route('penyewa.browse')->with('error', 'Properti tidak tersedia untuk disewa.');
        }

        return view('penyewa.pemesanan.create', compact('properti'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_properti' => 'required|exists:properti,id_properti',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'lama_sewa' => 'required|integer|min:1|max:12',
        ]);

        $properti = Properti::findOrFail($request->id_properti);
        
        // Calculate total price
        $total_harga = $properti->harga * $request->lama_sewa;

        // Create pemesanan
        $pemesanan = Pemesanan::create([
            'id_akun' => Auth::id(),
            'id_properti' => $request->id_properti,
            'tanggal_pemesanan' => $request->tanggal_mulai,
            'lama_sewa' => $request->lama_sewa,
            'total_harga' => $total_harga,
            'status_pemesanan' => 'pending',
            'metode_pembayaran' => 'midtrans',
            'status_pembayaran' => 'belum_bayar',
        ]);

        // Prepare transaction details for Midtrans
        $transaction_details = [
            'order_id' => 'ORDER-' . $pemesanan->id_pemesanan . '-' . time(),
            'gross_amount' => (int) $total_harga,
        ];

        // Customer details
        $customer_details = [
            'first_name' => Auth::user()->nama,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->telepon ?? '08123456789',
        ];

        // Item details
        $item_details = [
            [
                'id' => $properti->id_properti,
                'price' => (int) $properti->harga,
                'quantity' => $request->lama_sewa,
                'name' => $properti->nama . ' (' . $request->lama_sewa . ' bulan)',
            ]
        ];

        // Compile transaction data
        $transaction_data = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        ];

        try {
            // Get Snap Token from Midtrans
            $snapToken = Snap::getSnapToken($transaction_data);
            
            // Save snap token to pemesanan
            $pemesanan->update([
                'snap_token' => $snapToken,
                'transaction_id' => $transaction_details['order_id'],
            ]);

            return redirect()->route('penyewa.pemesanan.payment', $pemesanan->id_pemesanan);
        } catch (\Exception $e) {
            // If failed, delete the pemesanan and show error
            $pemesanan->delete();
            return back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    public function payment($id_pemesanan)
    {
        $pemesanan = Pemesanan::with(['properti', 'akun'])->findOrFail($id_pemesanan);
        
        // Make sure this pemesanan belongs to the logged in user
        if ($pemesanan->id_akun != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Sync payment status sebelum tampilkan halaman
        if ($pemesanan->status_pemesanan == 'pending' && $pemesanan->transaction_id) {
            $this->syncPaymentStatus($pemesanan);
            $pemesanan->refresh();
        }

        // Jika sudah confirmed, redirect ke success page
        if ($pemesanan->status_pemesanan == 'confirmed') {
            return redirect()->route('penyewa.pemesanan.success', $pemesanan->id_pemesanan);
        }

        // Jika cancelled, redirect ke index dengan pesan
        if ($pemesanan->status_pemesanan == 'cancelled') {
            return redirect()->route('penyewa.pemesanan.index')
                ->with('error', 'Pemesanan ini telah dibatalkan.');
        }

        // Jika tidak punya snap_token, generate baru
        if (!$pemesanan->snap_token && $pemesanan->status_pemesanan == 'pending') {
            try {
                // Prepare transaction details for Midtrans
                $transaction_details = [
                    'order_id' => 'ORDER-' . $pemesanan->id_pemesanan . '-' . time(),
                    'gross_amount' => (int) $pemesanan->total_harga,
                ];

                // Customer details
                $customer_details = [
                    'first_name' => $pemesanan->akun->nama,
                    'email' => $pemesanan->akun->email,
                    'phone' => $pemesanan->akun->telepon ?? '08123456789',
                ];

                // Item details
                $item_details = [
                    [
                        'id' => $pemesanan->properti->id_properti,
                        'price' => (int) $pemesanan->properti->harga,
                        'quantity' => $pemesanan->lama_sewa,
                        'name' => $pemesanan->properti->nama . ' (' . $pemesanan->lama_sewa . ' bulan)',
                    ]
                ];

                // Compile transaction data
                $transaction_data = [
                    'transaction_details' => $transaction_details,
                    'customer_details' => $customer_details,
                    'item_details' => $item_details,
                ];

                // Get Snap Token from Midtrans
                $snapToken = Snap::getSnapToken($transaction_data);
                
                // Save snap token to pemesanan
                $pemesanan->update([
                    'snap_token' => $snapToken,
                    'transaction_id' => $transaction_details['order_id'],
                ]);
                
                $pemesanan->refresh();
            } catch (\Exception $e) {
                return redirect()->route('penyewa.pemesanan.index')
                    ->with('error', 'Gagal membuat token pembayaran: ' . $e->getMessage());
            }
        }

        return view('penyewa.pemesanan.payment', compact('pemesanan'));
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            // Extract order ID
            $order_id = $request->order_id;
            $transaction_status = $request->transaction_status;
            $payment_type = $request->payment_type;
            $fraud_status = $request->fraud_status ?? 'accept';

            // Find pemesanan by transaction_id
            $pemesanan = Pemesanan::where('transaction_id', $order_id)->first();

            if ($pemesanan) {
                // SUCCESS: capture (credit card) atau settlement (other payment methods)
                if ($transaction_status == 'capture') {
                    if ($fraud_status == 'accept') {
                        $pemesanan->update([
                            'status_pembayaran' => 'sudah_bayar',
                            'status_pemesanan' => 'confirmed',
                            'payment_type' => $payment_type,
                            'paid_at' => now(),
                        ]);
                        // Update properti status to disewa
                        $pemesanan->properti->update(['status' => 'disewa']);
                    }
                } elseif ($transaction_status == 'settlement') {
                    $pemesanan->update([
                        'status_pembayaran' => 'sudah_bayar',
                        'status_pemesanan' => 'confirmed',
                        'payment_type' => $payment_type,
                        'paid_at' => now(),
                    ]);
                    // Update properti status to disewa
                    $pemesanan->properti->update(['status' => 'disewa']);
                    
                // PENDING: user belum menyelesaikan pembayaran
                } elseif ($transaction_status == 'pending') {
                    $pemesanan->update([
                        'status_pembayaran' => 'belum_bayar',
                        'status_pemesanan' => 'pending',
                        'payment_type' => $payment_type,
                    ]);
                    
                // FAILED/CANCELLED: deny, expire, cancel
                } elseif (in_array($transaction_status, ['deny', 'expire', 'cancel'])) {
                    $pemesanan->update([
                        'status_pembayaran' => 'belum_bayar',
                        'status_pemesanan' => 'cancelled',
                        'payment_type' => $payment_type,
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function success($id_pemesanan)
    {
        $pemesanan = Pemesanan::with(['properti', 'akun'])->findOrFail($id_pemesanan);
        
        if ($pemesanan->id_akun != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('penyewa.pemesanan.success', compact('pemesanan'));
    }

    public function cancel($id_pemesanan)
    {
        $pemesanan = Pemesanan::findOrFail($id_pemesanan);
        
        // Check authorization
        if ($pemesanan->id_akun != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow cancel if status is pending and payment is not completed
        if ($pemesanan->status_pemesanan == 'pending' && $pemesanan->status_pembayaran == 'belum_bayar') {
            // Delete the pemesanan
            $pemesanan->delete();
            
            return redirect()->route('penyewa.pemesanan.index')->with('success', 'Pemesanan berhasil dibatalkan');
        }

        return redirect()->back()->with('error', 'Pemesanan tidak dapat dibatalkan');
    }

    /**
     * Sync payment status from Midtrans
     * Status mapping:
     * - capture/settlement -> confirmed (sudah_bayar)
     * - pending -> pending (belum_bayar)
     * - deny/expire/cancel -> cancelled (belum_bayar)
     */
    private function syncPaymentStatus($pemesanan)
    {
        try {
            // Get transaction status from Midtrans
            $status = Transaction::status($pemesanan->transaction_id);
            
            $transaction_status = $status->transaction_status;
            $payment_type = $status->payment_type ?? null;
            $fraud_status = $status->fraud_status ?? 'accept';

            // SUCCESS: Update based on transaction status
            if ($transaction_status == 'capture') {
                if ($fraud_status == 'accept') {
                    $pemesanan->update([
                        'status_pembayaran' => 'sudah_bayar',
                        'status_pemesanan' => 'confirmed',
                        'payment_type' => $payment_type,
                        'paid_at' => now(),
                    ]);
                    
                    // Update properti status
                    $pemesanan->properti->update(['status' => 'disewa']);
                }
            } else if ($transaction_status == 'settlement') {
                $pemesanan->update([
                    'status_pembayaran' => 'sudah_bayar',
                    'status_pemesanan' => 'confirmed',
                    'payment_type' => $payment_type,
                    'paid_at' => now(),
                ]);
                
                // Update properti status
                $pemesanan->properti->update(['status' => 'disewa']);
                
            // PENDING: Menunggu pembayaran
            } else if ($transaction_status == 'pending') {
                // Hanya update jika belum cancelled
                if ($pemesanan->status_pemesanan !== 'cancelled') {
                    $pemesanan->update([
                        'status_pembayaran' => 'belum_bayar',
                        'status_pemesanan' => 'pending',
                        'payment_type' => $payment_type,
                    ]);
                }
                
            // FAILED: Pembayaran gagal/expired/dibatalkan
            } else if (in_array($transaction_status, ['deny', 'expire', 'cancel'])) {
                $pemesanan->update([
                    'status_pembayaran' => 'belum_bayar',
                    'status_pemesanan' => 'cancelled',
                    'payment_type' => $payment_type,
                ]);
            }
        } catch (\Exception $e) {
            // Jika gagal get status dari Midtrans, skip saja
            \Log::info('Failed to sync payment status for order ' . $pemesanan->id_pemesanan . ': ' . $e->getMessage());
        }
    }
}
