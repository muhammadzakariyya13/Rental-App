<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium">{{ __('Your Bookings') }}</h3>
                        <a href="{{ route('penyewa.browse') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Browse Properties
                        </a>
                    </div>
                    
                    @if($bookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Property
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Booking Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Duration
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Amount
                                        </th>                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Booking Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Payment Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium">
                                                            {{ $booking->properti->nama }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $booking->properti->tipe }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm">
                                                    {{ date('d M Y', strtotime($booking->tanggal_mulai)) }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    to {{ date('d M Y', strtotime($booking->tanggal_selesai)) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm">
                                                    {{ $booking->durasi }} day(s)
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                                    Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                                </div>
                                            </td>                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($booking->status == 'confirmed')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Confirmed
                                                    </span>
                                                @elseif($booking->status == 'pending')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        Pending
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Cancelled
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($booking->payment_status == 'completed')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Completed
                                                    </span>
                                                @elseif($booking->payment_status == 'processing')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        Processing
                                                    </span>
                                                @elseif($booking->payment_status == 'failed')
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Failed
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('penyewa.pemesanan.show', $booking->id_pemesanan) }}" 
                                                        class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                                        Details
                                                    </a>
                                                    
                                                    @if($booking->status == 'pending')
                                                        <a href="{{ route('penyewa.pemesanan.cancel', $booking->id_pemesanan) }}" 
                                                            class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600"
                                                            onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                            Cancel
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0h10a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">No bookings found</h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">You haven't made any bookings yet.</p>
                            <div class="mt-6">
                                <a href="{{ route('penyewa.browse') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Browse Properties
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
