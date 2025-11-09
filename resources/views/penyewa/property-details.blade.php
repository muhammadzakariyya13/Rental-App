<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Property Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ route('penyewa.browse') }}" class="text-blue-500 hover:underline flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back to Browse
                        </a>
                    </div>

                    <!-- Property Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Property Image -->
                        <div class="md:col-span-2 bg-gray-300 dark:bg-gray-600 rounded-lg flex items-center justify-center min-h-[300px]">
                            <svg class="w-24 h-24 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>

                        <!-- Property Details -->
                        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
                            <div class="flex justify-between items-start mb-4">
                                <h1 class="text-2xl font-bold">{{ $property->nama }}</h1>
                                <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $property->tipe }}
                                </span>
                            </div>                            <div class="mb-6">
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400 mb-1">
                                    Rp {{ number_format($property->harga * 0.0001, 0, ',', '.') }}<span class="text-base font-normal"> / day</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mb-2">
                                    (Property value: Rp {{ number_format($property->harga, 0, ',', '.') }})
                                </p>
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <p>{{ $property->alamat }}</p>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Status</h3>
                                <span class="px-3 py-1 rounded-full {{ $property->status === 'tersedia' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </div>                            @if($property->status === 'tersedia')
                                <a href="{{ route('penyewa.pemesanan.create', $property->id_properti) }}" class="w-full block text-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                    Book Now
                                </a>
                            @else
                                <button disabled class="w-full px-4 py-2 bg-gray-500 text-white rounded-md cursor-not-allowed">
                                    Not Available
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Property Description -->
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-4">Description</h2>
                        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
                            <p class="whitespace-pre-line">{{ $property->deskripsi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
