<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Properti') }}
            </h2>
            <a href="{{ route('pemilik.properti.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Properti
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" id="alert-success">
                    <div class="flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button onclick="document.getElementById('alert-success').remove()" class="text-green-700">×</button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" id="alert-error">
                    <div class="flex justify-between items-center">
                        <span>{{ session('error') }}</span>
                        <button onclick="document.getElementById('alert-error').remove()" class="text-red-700">×</button>
                    </div>
                </div>
            @endif

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Properti -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg leading-6 font-medium text-white">{{ $totalProperti }}</div>
                                <div class="text-blue-100">Total Properti</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Properti Tersedia -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg leading-6 font-medium text-white">{{ $propertiTersedia }}</div>
                                <div class="text-green-100">Tersedia</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Properti Disewa -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg leading-6 font-medium text-white">{{ $propertiDisewa }}</div>
                                <div class="text-red-100">Disewa</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata Harga -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg leading-6 font-medium text-white">{{ number_format($rataRataHarga/1000000, 1) }}M</div>
                                <div class="text-yellow-100">Rata-rata Harga</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Form -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                        Filter & Pencarian
                    </h3>
                    
                    <form method="GET" action="{{ route('pemilik.properti') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                            <!-- Search -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Properti</label>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Nama atau alamat properti..." 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <!-- Filter Tipe -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                                <select name="tipe" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Semua Tipe</option>
                                    <option value="rumah" {{ request('tipe') == 'rumah' ? 'selected' : '' }}>Rumah</option>
                                    <option value="apartemen" {{ request('tipe') == 'apartemen' ? 'selected' : '' }}>Apartemen</option>
                                    <option value="kontrakan" {{ request('tipe') == 'kontrakan' ? 'selected' : '' }}>Kontrakan</option>
                                    <option value="vila" {{ request('tipe') == 'vila' ? 'selected' : '' }}>Vila</option>
                                </select>
                            </div>
                            
                            <!-- Filter Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Semua Status</option>
                                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="disewa" {{ request('status') == 'disewa' ? 'selected' : '' }}>Disewa</option>
                                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                            </div>
                            
                            <!-- Harga Min -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Min</label>
                                <input type="number" name="harga_min" value="{{ request('harga_min') }}" 
                                       placeholder="1000000"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <!-- Harga Max -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Max</label>
                                <input type="number" name="harga_max" value="{{ request('harga_max') }}" 
                                       placeholder="10000000"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div class="mt-4 flex space-x-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-medium inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('pemilik.properti') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md font-medium inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Actions -->
            @if($properti->count() > 0)
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Pilih Semua</span>
                            </label>
                            <div id="selectedCount" class="text-sm text-gray-500 hidden">
                                <span id="selectedCountNumber">0</span> item dipilih
                            </div>
                        </div>
                        <div id="bulkActions" class="hidden">
                            <form action="{{ route('pemilik.properti.bulk-delete') }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus properti yang dipilih?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" id="bulkDeleteIds" name="ids" value="">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus Terpilih
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- List Properti -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    @if($properti->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($properti as $item)
                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-3">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" class="properti-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" value="{{ $item->id_properti }}">
                                                <span class="ml-2 text-sm text-gray-600">#{{ $item->id_properti }}</span>
                                            </label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                                $item->status == 'tersedia' ? 'bg-green-100 text-green-800' : 
                                                ($item->status == 'disewa' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') 
                                            }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                        
                                        <h3 class="font-bold text-lg text-gray-900 mb-2">
                                            <a href="{{ route('pemilik.properti.show', $item->id_properti) }}" 
                                            class="hover:text-blue-600 transition-colors duration-200">
                                                {{ $item->nama }}
                                            </a>
                                        </h3>
                                        <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $item->alamat }}</p>
                                        <p class="text-green-600 font-bold text-lg mb-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                        
                                        <div class="grid grid-cols-3 gap-2 text-sm text-gray-500 mb-3">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                {{ $item->tipe }}
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                                                </svg>
                                                {{ $item->kamar_tidur }}KT
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                                </svg>
                                                {{ $item->kamar_mandi }}KM
                                            </div>
                                        </div>
                                        
                                        @if($item->deskripsi)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($item->deskripsi, 100) }}</p>
                                        @endif
                                        
                                        <div class="flex space-x-2">
                                            <a href="{{ route('pemilik.properti.show', $item->id_properti) }}" 
                                            class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded text-sm text-center inline-flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            Detail
                                            <a href="{{ route('pemilik.properti.edit', $item->id_properti) }}" 
                                               class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-3 rounded text-sm text-center inline-flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('pemilik.properti.destroy', $item->id_properti) }}" method="POST" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Yakin ingin hapus properti {{ $item->nama }}?')"
                                                        class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-3 rounded text-sm inline-flex items-center justify-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $properti->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">
                                @if(request()->hasAny(['search', 'tipe', 'status', 'harga_min', 'harga_max']))
                                    Tidak ada properti yang sesuai filter
                                @else
                                    Belum Ada Properti
                                @endif
                            </h3>
                            <p class="mt-1 text-gray-500">
                                @if(request()->hasAny(['search', 'tipe', 'status', 'harga_min', 'harga_max']))
                                    Coba ubah kriteria pencarian atau filter Anda
                                @else
                                    Mulai dengan menambahkan properti pertama Anda
                                @endif
                            </p>
                            <div class="mt-6">
                                @if(request()->hasAny(['search', 'tipe', 'status', 'harga_min', 'harga_max']))
                                    <a href="{{ route('pemilik.properti') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Reset Filter
                                    </a>
                                @else
                                    <a href="{{ route('pemilik.properti.create') }}" 
                                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Tambah Properti Pertama
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Bulk Actions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const propertiCheckboxes = document.querySelectorAll('.properti-checkbox');
            const selectedCount = document.getElementById('selectedCount');
            const selectedCountNumber = document.getElementById('selectedCountNumber');
            const bulkActions = document.getElementById('bulkActions');
            const bulkDeleteIds = document.getElementById('bulkDeleteIds');

            function updateBulkActions() {
                const checkedBoxes = document.querySelectorAll('.properti-checkbox:checked');
                const checkedCount = checkedBoxes.length;

                if (checkedCount > 0) {
                    selectedCount.classList.remove('hidden');
                    bulkActions.classList.remove('hidden');
                    selectedCountNumber.textContent = checkedCount;
                    
                    // Update hidden input with selected IDs
                    const ids = Array.from(checkedBoxes).map(cb => cb.value);
                    bulkDeleteIds.value = ids.join(',');
                } else {
                    selectedCount.classList.add('hidden');
                    bulkActions.classList.add('hidden');
                    bulkDeleteIds.value = '';
                }
            }

            // Select All functionality
            selectAllCheckbox.addEventListener('change', function() {
                propertiCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActions();
            });

            // Individual checkbox change
            propertiCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // Update select all checkbox
                    const checkedCount = document.querySelectorAll('.properti-checkbox:checked').length;
                    selectAllCheckbox.checked = checkedCount === propertiCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < propertiCheckboxes.length;
                    
                    updateBulkActions();
                });
            });
        });
    </script>

    <style>
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .line-clamp-3 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
    </style>
</x-app-layout>