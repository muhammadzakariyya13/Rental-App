@extends('layouts.penyewa')

@section('title', 'Pembayaran - Order #' . $pemesanan->id_pemesanan)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 md:px-8">
        
        {{-- Header --}}
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            
            {{-- Error Messages --}}
            @if(session('error'))
                <div class="bg-red-100 border-2 border-red-400 text-red-800 px-6 py-4 rounded-lg mb-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran</h1>
                <p class="text-gray-600">Order ID: <span class="font-semibold">#{{ $pemesanan->id_pemesanan }}</span></p>
            </div>

            {{-- Order Summary --}}
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pesanan</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Properti:</span>
                        <span class="font-semibold text-gray-900 text-right">{{ $pemesanan->properti->nama }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Mulai:</span>
                        <span class="font-semibold text-gray-900">{{ $pemesanan->tanggal_pemesanan->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Durasi Sewa:</span>
                        <span class="font-semibold text-gray-900">{{ $pemesanan->lama_sewa }} Bulan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga per Bulan:</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($pemesanan->properti->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-gray-300 pt-3 mt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total Pembayaran:</span>
                            <span class="text-2xl font-bold text-sky-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Status --}}
            <div class="mb-6">
                @if($pemesanan->status_pemesanan == 'confirmed' && $pemesanan->status_pembayaran == 'sudah_bayar')
                    <div class="bg-green-100 border-2 border-green-400 rounded-lg p-4 text-center">
                        <svg class="w-12 h-12 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-green-800 font-bold text-lg">Pembayaran Berhasil!</p>
                        <p class="text-green-700 text-sm mt-1">Pesanan Anda telah dikonfirmasi</p>
                    </div>
                @elseif($pemesanan->status_pemesanan == 'cancelled')
                    <div class="bg-red-100 border-2 border-red-400 rounded-lg p-4 text-center">
                        <svg class="w-12 h-12 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-red-800 font-bold text-lg">Pembayaran Dibatalkan</p>
                        <p class="text-red-700 text-sm mt-1">Pesanan ini telah dibatalkan atau expired</p>
                    </div>
                @else
                    <div class="bg-yellow-100 border-2 border-yellow-400 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <p class="text-yellow-800 font-semibold">Status: Menunggu Pembayaran</p>
                                <p class="text-yellow-700 text-sm mt-1">Klik tombol di bawah untuk melanjutkan pembayaran</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Payment Button --}}
            @if($pemesanan->status_pemesanan == 'pending' && $pemesanan->status_pembayaran == 'belum_bayar')
                <button id="pay-button" 
                        class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-4 rounded-lg transition shadow-lg mb-4">
                    💳 Bayar Sekarang
                </button>
            @elseif($pemesanan->status_pemesanan == 'confirmed')
                <a href="{{ route('penyewa.pemesanan.success', $pemesanan->id_pemesanan) }}" 
                   class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg transition shadow-lg text-center mb-4">
                    ✓ Lihat Detail Pesanan
                </a>
            @else
                <a href="{{ route('penyewa.pemesanan.index') }}" 
                   class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 rounded-lg transition shadow-lg text-center mb-4">
                    ← Kembali ke Daftar Pemesanan
                </a>
            @endif

            <a href="{{ route('penyewa.dashboard') }}" 
               class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg transition">
                ← Kembali ke Dashboard
            </a>

            {{-- Payment Methods Info --}}
            <div class="mt-8 border-t pt-6">
                <h4 class="font-semibold text-gray-900 mb-3">Metode Pembayaran yang Tersedia:</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-center text-sm text-gray-600">
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="font-semibold">Virtual Account</div>
                        <div class="text-xs">BCA, BNI, BRI, Mandiri</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="font-semibold">E-Wallet</div>
                        <div class="text-xs">GoPay, ShopeePay, QRIS</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="font-semibold">Credit Card</div>
                        <div class="text-xs">Visa, Mastercard, JCB</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="font-semibold">Bank Transfer</div>
                        <div class="text-xs">Permata VA</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Midtrans Snap Script --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');
        
        if (!payButton) {
            console.log('Pay button not found');
            return;
        }

        // Check if snap_token exists
        const snapToken = '{{ $pemesanan->snap_token ?? "" }}';
        
        console.log('=== Payment Info ===');
        console.log('Snap Token:', snapToken);
        console.log('Order ID:', '{{ $pemesanan->id_pemesanan }}');
        console.log('Status:', '{{ $pemesanan->status_pemesanan }}');
        console.log('Snap loaded:', typeof snap !== 'undefined');
        
        // Add click event listener
        payButton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Button clicked!');
            
            if (!snapToken) {
                alert('Token pembayaran tidak ditemukan. Silakan buat pemesanan baru.');
                window.location.href = '{{ route("penyewa.pemesanan.index") }}';
                return;
            }
            
            // Check if Midtrans snap is loaded
            if (typeof snap === 'undefined') {
                alert('Midtrans payment system tidak dapat dimuat. Silakan refresh halaman.');
                return;
            }
            
            // Disable button to prevent double click
            payButton.disabled = true;
            payButton.innerHTML = '⏳ Memuat...';
            
            // Open Midtrans payment popup
            snap.pay(snapToken, {
                onSuccess: function(result){
                    console.log('success', result);
                    window.location.href = '{{ route("penyewa.pemesanan.success", $pemesanan->id_pemesanan) }}';
                },
                onPending: function(result){
                    console.log('pending', result);
                    alert('Menunggu pembayaran! Silakan selesaikan pembayaran Anda.');
                    payButton.disabled = false;
                    payButton.innerHTML = '💳 Bayar Sekarang';
                },
                onError: function(result){
                    console.log('error', result);
                    alert('Pembayaran gagal! ' + (result.status_message || 'Terjadi kesalahan'));
                    payButton.disabled = false;
                    payButton.innerHTML = '💳 Bayar Sekarang';
                },
                onClose: function(){
                    console.log('customer closed the popup without finishing the payment');
                    payButton.disabled = false;
                    payButton.innerHTML = '💳 Bayar Sekarang';
                }
            });
        });
    });
</script>
@endsection
