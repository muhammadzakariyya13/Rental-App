<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Penyewa Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('Tenant Control Panel') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">Browse Properties</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Browse available rental properties</p>
                            <a href="{{ route('penyewa.browse') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Jelajahi Properti</a>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">My Bookings</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Manage your property bookings</p>
                            <a href="{{ route('penyewa.pemesanan.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Pemesanan Saya</a>
                        </div>
                          <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">My Reviews</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Manage your property reviews</p>
                            <a href="{{ route('penyewa.review') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Reviews</a>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-medium mb-2">Payment History</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">View your payment history</p>
                            <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Riwayat Pembayaran</a>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-4">{{ __('Booking Statistics') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg shadow">
                                <h4 class="font-medium mb-2">Available Properties</h4>
                                <p class="text-3xl font-bold">10</p>
                                <a href="{{ route('penyewa.browse') }}" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">Browse Properties</a>
                            </div>
                            
                            <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg shadow">
                                <h4 class="font-medium mb-2">My Bookings</h4>
                                <p class="text-3xl font-bold">2</p>
                                <a href="{{ route('penyewa.pemesanan.index') }}" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">View Bookings</a>
                            </div>
                            
                            <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg shadow">
                                <h4 class="font-medium mb-2">My Reviews</h4>
                                <p class="text-3xl font-bold">1</p>
                                <a href="{{ route('penyewa.review') }}" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">View Reviews</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-4">{{ __('Recent Activity') }}</h3>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <p class="text-sm text-gray-600 dark:text-gray-300">No recent activity to show.</p>
                        </div>                    </div>                </div>
            </div>
        </div>
    </div>
</x-app-layout>
