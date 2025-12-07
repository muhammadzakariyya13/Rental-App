@extends('layouts.penyewa')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Riwayat Pembayaran</h1>
            <p class="text-gray-600">Daftar semua transaksi pembayaran Anda</p>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('penyewa.riwayat-pembayaran.index') }}" class="flex flex-wrap gap-4 items-end" id="filterForm">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                    <select name="status_pemesanan" id="statusPemesanan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="confirmed" {{ request('status_pemesanan') == 'confirmed' ? 'selected' : '' }}>Lunas (Confirmed)</option>
                        <option value="pending" {{ request('status_pemesanan') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="cancelled" {{ request('status_pemesanan') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                    <select name="payment_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">Semua Metode</option>
                        <option value="credit_card" {{ request('payment_type') == 'credit_card' ? 'selected' : '' }}>Kartu Kredit</option>
                        <option value="bank_transfer" {{ request('payment_type') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="gopay" {{ request('payment_type') == 'gopay' ? 'selected' : '' }}>GoPay</option>
                        <option value="shopeepay" {{ request('payment_type') == 'shopeepay' ? 'selected' : '' }}>ShopeePay</option>
                        <option value="qris" {{ request('payment_type') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="bca_va" {{ request('payment_type') == 'bca_va' ? 'selected' : '' }}>BCA Virtual Account</option>
                        <option value="bni_va" {{ request('payment_type') == 'bni_va' ? 'selected' : '' }}>BNI Virtual Account</option>
                        <option value="bri_va" {{ request('payment_type') == 'bri_va' ? 'selected' : '' }}>BRI Virtual Account</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['status_pemesanan', 'status_pembayaran', 'payment_type']))
                    <a href="{{ route('penyewa.riwayat-pembayaran.index') }}" class="ml-2 px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Transaksi</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $riwayat->total() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Dibayar</p>
                        <h3 class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPaid, 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Menunggu Pembayaran</p>
                        <h3 class="text-2xl font-bold text-orange-600">Rp {{ number_format($totalPending, 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Pembayaran Table --}}
        @if($riwayat->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Riwayat Pembayaran</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki transaksi pembayaran</p>
                <a href="{{ route('penyewa.browse') }}" class="inline-block px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg transition">
                    Mulai Cari Properti
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Properti</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($riwayat as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->transaction_id ?? '#' . $item->id_pemesanan }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Pemesanan #{{ $item->id_pemesanan }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($item->properti && $item->properti->gambar)
                                            <img src="{{ asset($item->properti->gambar) }}" alt="{{ $item->properti->nama }}" class="w-16 h-16 rounded-lg object-cover mr-3 shadow-sm">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->properti->nama }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->lama_sewa }} Bulan</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->paid_at)
                                    <div class="text-sm text-gray-900">{{ $item->paid_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->paid_at->format('H:i') }} WIB</div>
                                    @else
                                    <div class="text-sm text-gray-900">{{ $item->tanggal_pemesanan->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">Belum dibayar</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->formatted_payment_type }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->status_pemesanan == 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Dibatalkan
                                    </span>
                                    @elseif($item->status_pemesanan == 'confirmed' && $item->status_pembayaran == 'sudah_bayar')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Lunas
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Pending
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($item->status_pemesanan == 'cancelled')
                                    <span class="text-gray-400">Dibatalkan</span>
                                    @elseif($item->status_pemesanan == 'confirmed' && $item->status_pembayaran == 'sudah_bayar')
                                    <a href="{{ route('penyewa.kontrak.show', $item->id_pemesanan) }}" class="text-green-600 hover:text-green-700 font-medium">
                                        Lihat Kontrak
                                    </a>
                                    @elseif($item->status_pemesanan == 'pending' && $item->status_pembayaran == 'belum_bayar')
                                    <a href="{{ route('penyewa.pemesanan.payment', $item->id_pemesanan) }}" class="text-orange-600 hover:text-orange-700 font-medium">
                                        Bayar Sekarang
                                    </a>
                                    @else
                                    <a href="{{ route('penyewa.pemesanan.show', $item->id_pemesanan) }}" class="text-sky-600 hover:text-sky-700 font-medium">
                                        Lihat Detail
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($riwayat->hasPages())
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    {{ $riwayat->links() }}
                </div>
                @endif
            </div>
        @endif
    </div>
</div>

@endsection
