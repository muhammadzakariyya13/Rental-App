@extends('layouts.penyewa')

@section('title', 'Pemesanan Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pemesanan Saya</h1>
            <p class="text-gray-600">Kelola dan pantau semua pemesanan properti Anda</p>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border-2 border-green-400 text-green-800 px-6 py-4 rounded-lg mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-2 border-red-400 text-red-800 px-6 py-4 rounded-lg mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Filter --}}
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" class="flex gap-3">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Lunas (Confirmed)</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-2 rounded-lg transition">
                    Filter
                </button>
                @if(request('status'))
                <a href="{{ route('penyewa.pemesanan.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg transition">
                    Reset
                </a>
                @endif
            </form>
        </div>

        {{-- Pemesanan List --}}
        @if($pemesanan->count() > 0)
            <div class="space-y-4">
                @foreach($pemesanan as $item)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="md:flex">
                        {{-- Image --}}
                        <div class="md:w-64 md:flex-shrink-0">
                            <div class="h-48 md:h-full w-full relative overflow-hidden bg-gray-200">
                                @if($item->properti->gambar)
                                    <img src="{{ asset($item->properti->gambar) }}" 
                                         alt="{{ $item->properti->nama }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-sky-50 to-sky-100">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $item->properti->nama }}</h3>
                                    <p class="text-sm text-gray-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $item->properti->alamat }}
                                    </p>
                                </div>
                                
                                {{-- Status Badge --}}
                                @if($item->status_pemesanan == 'confirmed')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Lunas
                                    </span>
                                @elseif($item->status_pemesanan == 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Pending
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Dibatalkan
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
                                <div>
                                    <p class="text-gray-600 mb-1">Order ID</p>
                                    <p class="font-semibold text-gray-900">#{{ $item->id_pemesanan }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 mb-1">Tanggal Mulai</p>
                                    <p class="font-semibold text-gray-900">{{ $item->tanggal_pemesanan->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 mb-1">Durasi</p>
                                    <p class="font-semibold text-gray-900">{{ $item->lama_sewa }} Bulan</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 mb-1">Total Harga</p>
                                    <p class="font-bold text-sky-600">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                {{-- Payment Status --}}
                                @if($item->status_pembayaran == 'sudah_bayar')
                                    <span class="px-3 py-1 bg-green-50 text-green-700 rounded text-xs font-medium border border-green-200">
                                        💳 Sudah Dibayar
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-50 text-red-700 rounded text-xs font-medium border border-red-200">
                                        💳 Belum Dibayar
                                    </span>
                                @endif

                                {{-- Action Buttons --}}
                                <div class="ml-auto flex gap-2">
                                    @if($item->status_pemesanan == 'pending')
                                        @if($item->status_pembayaran == 'belum_bayar')
                                            <a href="{{ route('penyewa.pemesanan.payment', $item->id_pemesanan) }}" 
                                               class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                                💳 Bayar Sekarang
                                            </a>
                                            <form action="{{ route('penyewa.pemesanan.cancel', $item->id_pemesanan) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                                                    ❌ Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($item->status_pemesanan == 'confirmed' && $item->status_pembayaran == 'sudah_bayar')
                                        <a href="{{ route('penyewa.kontrak.show', $item->id_pemesanan) }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                            📄 Lihat Kontrak
                                        </a>
                                        <a href="{{ route('penyewa.review.create', $item->id_pemesanan) }}" 
                                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                            ⭐ Beri Review
                                        </a>
                                    @elseif($item->status_pemesanan == 'cancelled')
                                        <form action="{{ route('penyewa.pemesanan.destroy', $item->id_pemesanan) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemesanan ini?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('penyewa.pemesanan.show', $item->id_pemesanan) }}" 
                                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                                        📋 Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($pemesanan->hasPages())
                <div class="mt-6">
                    {{ $pemesanan->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <div class="text-8xl mb-4">📦</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Pemesanan</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki pemesanan properti</p>
                <a href="{{ route('penyewa.browse') }}" class="inline-block bg-sky-600 hover:bg-sky-700 text-white px-6 py-3 rounded-lg font-medium transition">
                    🏠 Browse Properti
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    // Auto-dismiss alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);

    // Handle cancel form submission with loading state
    document.querySelectorAll('form[action*="cancel"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin h-4 w-4 text-white inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Membatalkan...';
        });
    });
</script>
@endsection
