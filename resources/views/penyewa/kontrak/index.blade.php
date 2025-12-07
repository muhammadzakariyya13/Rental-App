@extends('layouts.penyewa')

@section('title', 'Kontrak Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">📄 Kontrak Saya</h1>
            <p class="text-gray-600">Kelola dan unduh semua kontrak sewa properti Anda</p>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Filter --}}
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('penyewa.kontrak.index') }}" class="flex flex-wrap gap-3">
                <select name="filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    <option value="">Semua Kontrak</option>
                    <option value="aktif" {{ request('filter') == 'aktif' ? 'selected' : '' }}>Kontrak Aktif</option>
                    <option value="expired" {{ request('filter') == 'expired' ? 'selected' : '' }}>Kontrak Berakhir</option>
                </select>
                
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-2 rounded-lg transition font-medium">
                    Filter
                </button>

                @if(request()->hasAny(['filter']))
                    <a href="{{ route('penyewa.kontrak.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition font-medium">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Kontrak List --}}
        @if($kontrak->count() > 0)
            <div class="space-y-4">
                @foreach($kontrak as $item)
                    @php
                        $tanggal_mulai = \Carbon\Carbon::parse($item->tanggal_pemesanan);
                        $tanggal_selesai = \Carbon\Carbon::parse($item->tanggal_pemesanan)->addMonths($item->lama_sewa);
                        $sekarang = \Carbon\Carbon::now();
                        $is_aktif = $tanggal_selesai->greaterThanOrEqualTo($sekarang);
                        $sisa_hari = $is_aktif ? (int) $sekarang->diffInDays($tanggal_selesai, false) : 0;
                        if ($sisa_hari < 0) $sisa_hari = 0;
                    @endphp

                    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                        <div class="md:flex">
                            {{-- Image --}}
                            <div class="md:w-64 h-48 md:h-auto bg-gray-200 flex-shrink-0">
                                @if($item->properti->gambar)
                                    <img src="data:image/jpeg;base64,{{ $item->properti->gambar }}" 
                                         alt="{{ $item->properti->nama }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-sky-50 to-sky-100">
                                        <span class="text-6xl">🏠</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 p-6">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4">
                                    <div class="mb-4 md:mb-0">
                                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $item->properti->nama }}</h3>
                                        <p class="text-gray-600 text-sm flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $item->properti->alamat }}
                                        </p>
                                    </div>
                                    
                                    @if($is_aktif)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                                            <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                                            Berakhir
                                        </span>
                                    @endif
                                </div>

                                {{-- Info Grid --}}
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 pb-4 border-b">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Nomor Kontrak</p>
                                        <p class="font-semibold text-gray-900">#{{ $item->id_pemesanan }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                                        <p class="font-semibold text-gray-900">{{ $tanggal_mulai->format('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Tanggal Berakhir</p>
                                        <p class="font-semibold text-gray-900">{{ $tanggal_selesai->format('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Durasi</p>
                                        <p class="font-semibold text-gray-900">{{ $item->lama_sewa }} Bulan</p>
                                    </div>
                                </div>

                                {{-- Status Bar --}}
                                @if($is_aktif)
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">Sisa waktu kontrak</span>
                                            <span class="font-semibold text-sky-600">{{ $sisa_hari }} hari</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            @php
                                                $total_hari = (int) $tanggal_mulai->diffInDays($tanggal_selesai, false);
                                                if ($total_hari < 0) $total_hari = abs($total_hari);
                                                $progress = $total_hari > 0 ? (($sisa_hari / $total_hari) * 100) : 0;
                                                if ($progress > 100) $progress = 100;
                                                if ($progress < 0) $progress = 0;
                                            @endphp
                                            <div class="bg-sky-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Price & Actions --}}
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div>
                                        <p class="text-sm text-gray-600">Total Pembayaran</p>
                                        <p class="text-2xl font-bold text-sky-600">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <a href="{{ route('penyewa.kontrak.show', $item->id_pemesanan) }}" 
                                           class="flex-1 md:flex-none bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition text-center">
                                            📋 Lihat Kontrak
                                        </a>
                                        <a href="{{ route('penyewa.kontrak.download', $item->id_pemesanan) }}" 
                                           class="flex-1 md:flex-none bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition text-center">
                                            📥 Download PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($kontrak->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $kontrak->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <div class="text-8xl mb-4">📄</div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Belum Ada Kontrak</h3>
                <p class="text-gray-500 mb-6">Anda belum memiliki kontrak sewa yang bisa diakses.</p>
                <p class="text-gray-600 mb-6">Kontrak akan muncul setelah:</p>
                <ul class="text-left max-w-md mx-auto text-gray-700 space-y-2 mb-6">
                    <li class="flex items-start">
                        <span class="text-green-500 mr-2">✓</span>
                        <span>Anda melakukan pemesanan properti</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-500 mr-2">✓</span>
                        <span>Pembayaran berhasil dilakukan</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-500 mr-2">✓</span>
                        <span>Pemilik properti memberikan izin akses kontrak</span>
                    </li>
                </ul>
                <a href="{{ route('penyewa.browse') }}" class="inline-block bg-sky-600 hover:bg-sky-700 text-white px-6 py-3 rounded-lg transition font-medium">
                    🏠 Browse Properti
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
