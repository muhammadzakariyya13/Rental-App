@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🏠 Daftar Properti</h1>
        
        <!-- Filter & Search -->
        <div class="flex space-x-4">
            <select class="border rounded px-3 py-2" onchange="filterByStatus(this.value)">
                <option value="">Semua Status</option>
                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="disewa" {{ request('status') == 'disewa' ? 'selected' : '' }}>Disewa</option>
            </select>
            
            <form method="GET" class="flex">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari properti..." 
                       class="border rounded-l px-3 py-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700">
                    Cari
                </button>
            </form>
        </div>
    </div>

    <!-- Properti Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($propertis as $properti)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                @if($properti->foto)
                    <img src="{{ asset('storage/' . $properti->foto) }}" alt="{{ $properti->nama }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                        <span class="text-blue-600 text-5xl">🏠</span>
                    </div>
                @endif
                
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 line-clamp-2">{{ $properti->nama }}</h3>
                        <span class="px-2 py-1 text-xs rounded-full {{ $properti->status == 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($properti->status) }}
                        </span>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($properti->deskripsi, 80) }}</p>
                    
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($properti->harga, 0, ',', '.') }}
                            <span class="text-sm text-gray-500 font-normal">/bulan</span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('properti.show', $properti->id_properti) }}" 
                           class="flex-1 bg-gray-600 text-white py-2 px-3 rounded text-center hover:bg-gray-700 transition text-sm">
                            Detail
                        </a>
                        
                        @if($properti->status == 'tersedia')
                            @auth
                                <a href="{{ route('properti.book', $properti->id_properti) }}" 
                                   class="flex-1 bg-blue-600 text-white py-2 px-3 rounded text-center hover:bg-blue-700 transition text-sm">
                                    🛒 Sewa
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="flex-1 bg-blue-600 text-white py-2 px-3 rounded text-center hover:bg-blue-700 transition text-sm">
                                    Login untuk Sewa
                                </a>
                            @endauth
                        @else
                            <button disabled 
                                    class="flex-1 bg-gray-300 text-gray-500 py-2 px-3 rounded text-center cursor-not-allowed text-sm">
                                Tidak Tersedia
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">🏠</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Properti</h3>
                <p class="text-gray-600">Properti akan ditampilkan di sini setelah ditambahkan</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination (jika menggunakan paginate) -->
    {{-- @if($propertis->hasPages())
        <div class="mt-8">
            {{ $propertis->withQueryString()->links() }}
        </div>
    @endif --}}
</div>

<script>
function filterByStatus(status) {
    const url = new URL(window.location);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location = url;
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
