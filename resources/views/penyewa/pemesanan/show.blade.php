<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Booking Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <a href="{{ route('penyewa.pemesanan.index') }}" class="text-blue-500 hover:underline flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back to My Bookings
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Booking Status Card -->
                        <div class="md:col-span-1">
                            <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
                                <h3 class="text-xl font-semibold mb-4">Booking Status</h3>
                                
                                <div class="flex flex-col space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Status:</p>
                                        @if($booking->status == 'confirmed')
                                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Confirmed
                                            </span>
                                        @elseif($booking->status == 'pending')
                                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Pending
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Cancelled
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Booking ID:</p>
                                        <p class="font-medium">{{ $booking->id_pemesanan }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Booking Date:</p>
                                        <p class="font-medium">{{ date('d M Y, H:i', strtotime($booking->created_at)) }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Payment Method:</p>
                                        <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $booking->metode_pembayaran)) }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Amount:</p>
                                        <p class="text-lg font-bold text-green-600 dark:text-green-400">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    @if($booking->status == 'pending')
                                        <a href="{{ route('penyewa.pemesanan.cancel', $booking->id_pemesanan) }}" 
                                            class="w-full block text-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600"
                                            onclick="return confirm('Are you sure you want to cancel this booking?')">
                                            Cancel Booking
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            @if($booking->catatan)
                                <div class="mt-6 bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
                                    <h3 class="text-lg font-semibold mb-4">Booking Notes</h3>
                                    <p class="text-sm">{{ $booking->catatan }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Property and Rental Details -->
                        <div class="md:col-span-2">
                            <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg mb-6">
                                <h3 class="text-xl font-semibold mb-4">Property Information</h3>
                                
                                <div class="mb-4">
                                    <h4 class="text-lg font-medium">{{ $booking->properti->nama }}</h4>
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $booking->properti->tipe }}
                                    </span>
                                </div>
                                
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Address:</p>
                                    <p class="font-medium">{{ $booking->properti->alamat }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Description:</p>
                                    <p class="text-sm">{{ Str::limit($booking->properti->deskripsi, 200) }}</p>
                                </div>
                                
                                <div>
                                    <a href="{{ route('penyewa.property.show', $booking->properti->id_properti) }}" class="text-blue-500 hover:underline">
                                        View Property Details
                                    </a>
                                </div>
                            </div>
                            
                            <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
                                <h3 class="text-xl font-semibold mb-4">Rental Details</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Check-in Date:</p>
                                        <p class="font-medium">{{ date('d M Y', strtotime($booking->tanggal_mulai)) }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Check-out Date:</p>
                                        <p class="font-medium">{{ date('d M Y', strtotime($booking->tanggal_selesai)) }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Duration:</p>
                                        <p class="font-medium">{{ $booking->durasi }} day(s)</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Daily Rate:</p>
                                        <p class="font-medium">Rp {{ number_format($booking->total_harga / $booking->durasi, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <div class="flex justify-between">
                                        <p class="font-semibold">Total Amount:</p>
                                        <p class="font-bold text-green-600 dark:text-green-400">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($booking->status == 'confirmed')
                                <div class="mt-6">
                                    <a href="#" class="block text-center w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                        Write a Review
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
