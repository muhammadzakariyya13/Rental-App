@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4
                        {{ $payment->isPaid() ? 'bg-green-100' : ($payment->isFailed() || $payment->isExpired() ? 'bg-red-100' : 'bg-yellow-100') }}">
                @if($payment->isPaid())
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                @elseif($payment->isFailed() || $payment->isExpired())
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                @else
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @endif
            </div>
            
            <h1 class="text-2xl font-bold mb-2">
                @if($payment->isPaid())
                    Pembayaran Berhasil
                @elseif($payment->isFailed())
                    Pembayaran Gagal
                @elseif($payment->isExpired())
                    Pembayaran Kadaluarsa
                @elseif($payment->isCancelled())
                    Pembayaran Dibatalkan
                @else
                    Menunggu Pembayaran
                @endif
            </h1>
            
            <p class="text-gray-600">
                @if($payment->isPaid())
                    Pembayaran Anda telah berhasil diproses.
                @elseif($payment->isFailed())
                    Pembayaran Anda gagal diproses. Silakan coba lagi.
                @elseif($payment->isExpired())
                    Pembayaran sudah melewati batas waktu.
                @elseif($payment->isCancelled())
                    Pembayaran telah dibatalkan.
                @else
                    Silakan selesaikan pembayaran sebelum {{ $payment->expired_at->format('d/m/Y H:i') }} WIB.
                @endif
            </p>
        </div>

        <!-- Payment Details -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Detail Pembayaran</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">ID Pembayaran:</span>
                    <span class="font-medium">#{{ $payment->id }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">ID Pesanan:</span>
                    <span class="font-medium">{{ $payment->order_id }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Jumlah:</span>
                    <span class="font-bold text-lg">{{ $payment->formatted_amount }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="font-medium px-2 py-1 rounded text-sm
                          {{ $payment->isPaid() ? 'bg-green-100 text-green-800' : 
                             ($payment->isFailed() || $payment->isExpired() ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
                
                @if($payment->method)
                <div class="flex justify-between">
                    <span class="text-gray-600">Metode:</span>
                    <span class="font-medium">{{ ucwords(str_replace('_', ' ', $payment->method)) }}</span>
                </div>
                @endif
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Dibuat:</span>
                    <span class="font-medium">{{ $payment->created_at->format('d/m/Y H:i') }} WIB</span>
                </div>
                
                @if($payment->paid_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Dibayar:</span>
                    <span class="font-medium">{{ $payment->paid_at->format('d/m/Y H:i') }} WIB</span>
                </div>
                @endif
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Kadaluarsa:</span>
                    <span class="font-medium">{{ $payment->expired_at->format('d/m/Y H:i') }} WIB</span>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-semibold mb-4">Informasi Pelanggan</h3>
            
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama:</span>
                    <span class="font-medium">{{ $payment->customer_name }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium">{{ $payment->customer_email }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Telepon:</span>
                    <span class="font-medium">{{ $payment->customer_phone }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="border-t pt-6 mt-6 space-y-3">
            @if($payment->isPending() && $payment->payment_url)
                <a href="{{ $payment->payment_url }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 px-4 rounded-md font-medium">
                    Bayar Sekarang
                </a>
            @endif
            
            @if($payment->isPaid())
                <a href="{{ route('payment.receipt', $payment) }}" 
                   class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 px-4 rounded-md font-medium">
                    Download Kwitansi
                </a>
            @endif
            
            @if($payment->canBeCancelled())
                <button onclick="cancelPayment()" 
                        class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-3 px-4 rounded-md font-medium">
                    Batalkan Pembayaran
                </button>
            @endif
            
            <a href="{{ route('payment.history') }}" 
               class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center py-3 px-4 rounded-md font-medium">
                Lihat Riwayat Pembayaran
            </a>
        </div>
    </div>
</div>

@if($payment->isPending())
<!-- Auto-refresh for pending payments -->
<script>
let refreshInterval;

function startStatusCheck() {
    refreshInterval = setInterval(async function() {
        try {
            const response = await fetch(`/api/v1/payments/{{ $payment->id }}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.success && result.payment.status !== 'pending') {
                clearInterval(refreshInterval);
                location.reload();
            }
        } catch (error) {
            console.error('Status check error:', error);
        }
    }, 10000); // Check every 10 seconds
}

// Start checking when page loads
document.addEventListener('DOMContentLoaded', startStatusCheck);

// Stop checking when user leaves page
window.addEventListener('beforeunload', function() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});
</script>
@endif

@if($payment->canBeCancelled())
<script>
async function cancelPayment() {
    if (!confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/v1/payments/{{ $payment->id }}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Gagal membatalkan pembayaran: ' + result.message);
        }
    } catch (error) {
        console.error('Cancel error:', error);
        alert('Terjadi kesalahan saat membatalkan pembayaran');
    }
}
</script>
@endif
@endsection