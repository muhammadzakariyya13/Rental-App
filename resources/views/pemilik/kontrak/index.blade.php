<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📄 Kelola Kontrak
            </h2>
            <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                {{ $kontrak->total() }} Total
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                {{-- Total Kontrak --}}
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Total Kontrak</dt>
                                    <dd class="text-lg font-medium text-white">{{ $stats['total'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Izin Diberikan --}}
                <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Izin Diberikan</dt>
                                    <dd class="text-lg font-medium text-white">{{ $stats['izin_diberikan'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Belum Diberi Izin --}}
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 overflow-hidden shadow rounded-lg">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-white truncate">Belum Diberi Izin</dt>
                                    <dd class="text-lg font-medium text-white">{{ $stats['belum_izin'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Filters -->
            <div class="mb-6 bg-white p-4 rounded-lg shadow">
                <form method="GET" action="{{ route('pemilik.kontrak.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Penyewa</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Nama atau email..." 
                               class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Properti</label>
                        <select name="properti" class="w-full rounded-md border-gray-300 text-sm">
                            <option value="">Semua Properti</option>
                            @foreach($propertiList as $prop)
                                <option value="{{ $prop->id_properti }}" {{ request('properti') == $prop->id_properti ? 'selected' : '' }}>
                                    {{ $prop->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Izin</label>
                        <select name="izin" class="w-full rounded-md border-gray-300 text-sm">
                            <option value="">Semua Status Izin</option>
                            <option value="diberikan" {{ request('izin') == 'diberikan' ? 'selected' : '' }}>Izin Diberikan</option>
                            <option value="belum" {{ request('izin') == 'belum' ? 'selected' : '' }}>Belum Diberi Izin</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600 transition">
                            🔍 Filter
                        </button>
                        @if(request()->hasAny(['search', 'properti', 'izin']))
                            <a href="{{ route('pemilik.kontrak.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600 transition">
                                🔄 Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    @if($kontrak->count() > 0)
                        <div class="space-y-4">
                            @foreach($kontrak as $item)
                    @php
                        $tanggal_mulai = \Carbon\Carbon::parse($item->tanggal_pemesanan);
                        $tanggal_selesai = \Carbon\Carbon::parse($item->tanggal_pemesanan)->addMonths($item->lama_sewa);
                    @endphp

                    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                        <div class="md:flex">
                            {{-- Image --}}
                            <div class="md:w-64 h-48 md:h-auto bg-gray-200 flex-shrink-0">
                                @if($item->properti->gambar)
                                    <img src="{{ asset($item->properti->gambar) }}" 
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
                                        <p class="text-gray-600 text-sm flex items-center mb-2">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <strong>Penyewa:</strong>&nbsp;{{ $item->akun->username ?? $item->akun->email }}
                                        </p>
                                        <p class="text-gray-600 text-sm flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $item->akun->email }}
                                        </p>
                                    </div>
                                    
                                    {{-- Izin Status Badge --}}
                                    @if($item->download_izin)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                            Izin Diberikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm font-medium">
                                            <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                                            Belum Diberi Izin
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

                                {{-- Price & Actions --}}
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div>
                                        <p class="text-sm text-gray-600">Total Pembayaran</p>
                                        <p class="text-2xl font-bold text-sky-600">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <a href="{{ route('pemilik.kontrak.show', $item->id_pemesanan) }}" 
                                           class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition text-center">
                                            📋 Lihat Detail
                                        </a>
                                        <a href="{{ route('pemilik.kontrak.download', $item->id_pemesanan) }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition text-center">
                                            📥 Download PDF
                                        </a>
                                        
                                        {{-- Toggle Permission Button --}}
                                        <form action="{{ route('pemilik.kontrak.toggle-permission', $item->id_pemesanan) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin ingin {{ $item->download_izin ? 'mencabut' : 'memberikan' }} izin download untuk penyewa ini?')">
                                            @csrf
                                            @if($item->download_izin)
                                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                                    🚫 Cabut Izin
                                                </button>
                                            @else
                                                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                                    ✅ Beri Izin
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($kontrak->hasPages())
                <div class="mt-6">
                    {{ $kontrak->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada kontrak</h3>
                <p class="mt-1 text-sm text-gray-500">Kontrak akan muncul setelah penyewa melakukan pembayaran dan booking dikonfirmasi.</p>
            </div>
        @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
