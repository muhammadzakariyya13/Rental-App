@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Pembayaran</h1>
        
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="paymentForm" class="space-y-6">
            @csrf
            
            <!-- Order Information -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-3">Informasi Pesanan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Pesanan</label>
                        <input type="text" name="order_id" value="{{ $orderId ?? 'ORDER-' . time() }}" 
                               class="w-full border rounded-md px-3 py-2" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembayaran</label>
                        <div class="w-full border rounded-md px-3 py-2 bg-gray-50 text-lg font-semibold text-blue-600">
                            Rp {{ number_format($amount, 0, ',', '.') }}
                        </div>
                        <input type="hidden" name="amount" value="{{ $amount }}">
                        <p class="text-xs text-gray-500 mt-1">Jumlah pembayaran tidak dapat diubah</p>
                    </div>
                </div>

                @if($pemesanan)
                <div class="mt-3 p-3 bg-blue-50 rounded">
                    <p class="text-sm text-blue-800">
                        <strong>Properti:</strong> {{ $pemesanan->properti->nama ?? 'N/A' }}<br>
                        <strong>Penyewa:</strong> {{ $pemesanan->akun->nama ?? 'N/A' }}
                    </p>
                </div>
                @endif
            </div>

            <!-- Customer Information -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Informasi Pelanggan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="customer_name" 
                               value="{{ auth()->user()->nama ?? '' }}" 
                               class="w-full border rounded-md px-3 py-2" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="customer_email" 
                               value="{{ auth()->user()->email ?? '' }}" 
                               class="w-full border rounded-md px-3 py-2" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="tel" name="customer_phone" 
                               value="{{ auth()->user()->phone_number ?? '' }}" 
                               class="w-full border rounded-md px-3 py-2" required>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Metode Pembayaran</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($paymentMethods as $key => $name)
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="method" value="{{ $key }}" class="mr-3" required>
                            <span>{{ $name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                <textarea name="description" rows="3" 
                          class="w-full border rounded-md px-3 py-2"
                          placeholder="Deskripsi pembayaran..."></textarea>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                <textarea name="notes" rows="2" 
                          class="w-full border rounded-md px-3 py-2"
                          placeholder="Catatan tambahan..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" id="submitBtn" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md disabled:bg-gray-400">
                    Buat Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('paymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.textContent;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.textContent = 'Memproses...';
    
    try {
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        const response = await fetch('{{ route("api.payment.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Redirect to payment page or external payment URL
            if (result.payment_url) {
                window.location.href = result.payment_url;
            } else if (result.redirect_url) {
                window.location.href = result.redirect_url;
            } else if (result.payment_id) {
                // Fallback: redirect to payment show page
                window.location.href = '/payment/' + result.payment_id;
            } else {
                // Final fallback: show success message and redirect to home
                alert('Pembayaran berhasil dibuat!');
                window.location.href = '/';
            }
        } else {
            alert('Error: ' + result.message);
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
        
    } catch (error) {
        console.error('Payment error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});
</script>
@endpush
@endsection