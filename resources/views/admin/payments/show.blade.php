@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.payments.index') }}" 
               class="text-gray-600 hover:text-gray-900">
                ← Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold">Detail Pembayaran #{{ $payment->id }}</h1>
        </div>
        
        <div class="flex space-x-2">
            @if($payment->isPaid())
                <a href="{{ route('payment.receipt', $payment) }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Lihat Receipt
                </a>
            @endif
            
            <button onclick="refreshStatus()" id="refreshBtn"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Refresh Status
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Payment Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Informasi Pembayaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID Payment</label>
                        <p class="mt-1 text-sm text-gray-900">#{{ $payment->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order ID</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->order_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment ID (Gateway)</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->payment_id ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="inline-flex mt-1 px-2 py-1 text-xs font-semibold rounded-full
                              {{ $payment->isPaid() ? 'bg-green-100 text-green-800' : 
                                 ($payment->isFailed() || $payment->isExpired() ? 'bg-red-100 text-red-800' : 
                                 ($payment->isCancelled() ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800')) }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                        <p class="mt-1 text-sm text-gray-900 font-medium">{{ $payment->formatted_amount }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $payment->method ? ucwords(str_replace('_', ' ', $payment->method)) : 'Belum dipilih' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Informasi Customer</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->customer_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->customer_email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telepon</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->customer_phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Pemesanan Information -->
            @if($payment->pemesanan)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Informasi Pemesanan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID Pemesanan</label>
                        <p class="mt-1 text-sm text-gray-900">#{{ $payment->pemesanan->id }}</p>
                    </div>
                    @if($payment->pemesanan->properti)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Properti</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->pemesanan->properti->nama ?? '-' }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $payment->pemesanan->tanggal_mulai ? $payment->pemesanan->tanggal_mulai->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $payment->pemesanan->tanggal_selesai ? $payment->pemesanan->tanggal_selesai->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Gateway Response -->
            @if($payment->gateway_response)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Gateway Response</h3>
                <div class="bg-gray-50 rounded p-4">
                    <pre class="text-xs text-gray-700 whitespace-pre-wrap">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Timeline -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pembayaran Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $payment->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    
                    @if($payment->paid_at)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pembayaran Berhasil</p>
                            <p class="text-xs text-gray-500">{{ $payment->paid_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($payment->failed_at)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pembayaran Gagal</p>
                            <p class="text-xs text-gray-500">{{ $payment->failed_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($payment->expired_at)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-gray-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pembayaran Expired</p>
                            <p class="text-xs text-gray-500">{{ $payment->expired_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Aksi</h3>
                <div class="space-y-3">
                    @if($payment->isPending() && $payment->canBeCancelled())
                        <button onclick="cancelPayment()" 
                                class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Cancel Pembayaran
                        </button>
                    @endif
                    
                    @if($payment->payment_url && $payment->isPending())
                        <a href="{{ $payment->payment_url }}" target="_blank"
                           class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Buka Payment Page
                        </a>
                    @endif
                    
                    <button onclick="copyToClipboard('{{ $payment->order_id }}')"
                            class="w-full bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Copy Order ID
                    </button>
                </div>
            </div>

            <!-- Payment URL QR Code -->
            @if($payment->payment_url && $payment->isPending())
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">QR Code</h3>
                <div class="text-center">
                    <div id="qrcode" class="mx-auto mb-4"></div>
                    <p class="text-xs text-gray-500">Scan untuk membuka halaman pembayaran</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Include QR Code library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
// Generate QR Code
@if($payment->payment_url && $payment->isPending())
QRCode.toCanvas(document.getElementById('qrcode'), '{{ $payment->payment_url }}', {
    width: 200,
    height: 200,
    colorDark: '#000000',
    colorLight: '#ffffff'
});
@endif

// Refresh payment status
async function refreshStatus() {
    const btn = document.getElementById('refreshBtn');
    btn.disabled = true;
    btn.textContent = 'Refreshing...';
    
    try {
        const response = await fetch(`/api/payment/{{ $payment->order_id }}/status`);
        const data = await response.json();
        
        if (data.status !== '{{ $payment->status }}') {
            location.reload();
        } else {
            btn.textContent = 'Status Up to Date';
            setTimeout(() => {
                btn.textContent = 'Refresh Status';
                btn.disabled = false;
            }, 2000);
        }
    } catch (error) {
        console.error('Error refreshing status:', error);
        btn.textContent = 'Error';
        setTimeout(() => {
            btn.textContent = 'Refresh Status';
            btn.disabled = false;
        }, 2000);
    }
}

// Cancel payment
async function cancelPayment() {
    if (!confirm('Yakin ingin membatalkan pembayaran ini?')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/payment/{{ $payment->order_id }}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Gagal membatalkan pembayaran'));
        }
    } catch (error) {
        console.error('Error cancelling payment:', error);
        alert('Terjadi error saat membatalkan pembayaran');
    }
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Order ID berhasil disalin!');
    }).catch(err => {
        console.error('Error copying to clipboard:', err);
    });
}
</script>
@endsection