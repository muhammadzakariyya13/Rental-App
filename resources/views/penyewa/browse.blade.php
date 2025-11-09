<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Browse Properties') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('Available Properties') }}</h3>
                    
                    <div class="mt-4">
                        <!-- Search and filter form -->
                        <form method="GET" action="{{ route('penyewa.browse') }}" class="mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <input 
                                        type="text" 
                                        name="search" 
                                        placeholder="Search by name, address, or description" 
                                        value="{{ request('search') }}" 
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    >
                                </div>
                                <div>
                                    <select 
                                        name="tipe" 
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    >
                                        <option value="all">All Property Types</option>
                                        @foreach($propertyTypes as $type)
                                            <option value="{{ $type }}" {{ request('tipe') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 w-full">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Sort options -->
                        <div class="flex justify-end mb-4">
                            <form method="GET" action="{{ route('penyewa.browse') }}" class="flex items-center">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="tipe" value="{{ request('tipe') }}">
                                <label for="sort" class="mr-2 text-sm">Sort by:</label>
                                <select 
                                    name="sort" 
                                    id="sort" 
                                    class="rounded-md text-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    onchange="this.form.submit()"
                                >
                                    <option value="harga" {{ request('sort', 'harga') == 'harga' ? 'selected' : '' }}>Price</option>
                                    <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Name</option>
                                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest</option>
                                </select>
                                <select 
                                    name="direction" 
                                    class="ml-2 rounded-md text-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    onchange="this.form.submit()"
                                >
                                    <option value="asc" {{ request('direction', 'asc') == 'asc' ? 'selected' : '' }}>Low to High</option>
                                    <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>High to Low</option>
                                </select>
                            </form>
                        </div>
                        
                        <!-- Properties grid -->
                        @if($properties->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($properties as $property)
                                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden transition-transform duration-300 hover:scale-105 hover:shadow-lg">
                                        <div class="h-48 bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                        <div class="p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h3 class="text-lg font-semibold line-clamp-2">{{ $property->nama }}</h3>
                                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ $property->tipe }}</span>
                                            </div>
                                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-2 mb-2">{{ $property->alamat }}</p>
                                            <p class="text-gray-700 dark:text-gray-400 text-sm line-clamp-3 mb-3">{{ $property->deskripsi }}</p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                                    Rp {{ number_format($property->harga, 0, ',', '.') }}
                                                </span>
                                                <a href="{{ route('penyewa.property.show', $property->id_properti) }}" class="px-3 py-1 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $properties->withQueryString()->links() }}
                            </div>
                        @else
                            <div class="text-center py-10">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">No properties found</h3>
                                <p class="mt-1 text-gray-500 dark:text-gray-400">Try adjusting your search or filter to find properties.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
