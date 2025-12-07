@extends('layouts.penyewa')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 md:px-8">
        
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            
            {{-- Success Icon --}}
            <div class="text-center mb-8">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Berhasil! 🎉</h1>
                <p class="text-gray-600">Terima kasih telah melakukan pembayaran</p>
            </div>

            {{-- Order Details --}}
            <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Detail Pesanan
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order ID:</span>
                        <span class="font-bold text-gray-900">#{{ $pemesanan->id_pemesanan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Transaction ID:</span>
                        <span class="font-mono text-sm text-gray-900">{{ $pemesanan->transaction_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Properti:</span>
                        <span class="font-semibold text-gray-900 text-right">{{ $pemesanan->properti->nama }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Mulai:</span>
                        <span class="font-semibold text-gray-900">{{ $pemesanan->tanggal_pemesanan->format('d F Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Durasi:</span>
                        <span class="font-semibold text-gray-900">{{ $pemesanan->lama_sewa }} Bulan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-3 py-1 bg-green-200 text-green-800 rounded-full text-sm font-semibold">
                            ✓ {{ ucfirst($pemesanan->status_pemesanan) }}
                        </span>
                    </div>
                    @if($pemesanan->paid_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibayar pada:</span>
                        <span class="font-semibold text-gray-900">{{ $pemesanan->paid_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    <div class="border-t border-green-300 pt-3 mt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total Dibayar:</span>
                            <span class="text-2xl font-bold text-green-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Next Steps --}}
            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Langkah Selanjutnya
                </h3>
                <ol class="space-y-2 text-gray-700">
                    <li class="flex items-start gap-2">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                        <span>Pemilik properti akan menghubungi Anda dalam 1x24 jam</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                        <span>Koordinasi jadwal untuk proses serah terima kunci</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</span>
                        <span>Silakan lihat detail pesanan di menu "Pemesanan Saya"</span>
                    </li>
                </ol>
            </div>

            {{-- Contact Info --}}
            @if($pemesanan->properti->pemilik)
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Kontak Pemilik</h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-sky-400 to-sky-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ substr($pemesanan->properti->pemilik->nama ?? 'P', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $pemesanan->properti->pemilik->nama ?? 'Pemilik' }}</p>
                        <p class="text-sm text-gray-600">{{ $pemesanan->properti->pemilik->email ?? '' }}</p>
                        @if($pemesanan->properti->pemilik->telepon)
                        <p class="text-sm text-gray-600">{{ $pemesanan->properti->pemilik->telepon }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="space-y-3">
                <a href="{{ route('penyewa.kontrak.show', $pemesanan->id_pemesanan) }}" 
                   class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg transition shadow-lg text-center">
                    📄 Lihat & Download Kontrak
                </a>
                <a href="{{ route('penyewa.pemesanan.index') }}" 
                   class="block w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-4 rounded-lg transition shadow-lg text-center">
                    📋 Lihat Semua Pesanan
                </a>
                <a href="{{ route('penyewa.browse') }}" 
                   class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg transition text-center">
                    🏠 Browse Properti Lainnya
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
