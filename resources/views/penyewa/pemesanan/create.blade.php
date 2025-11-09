<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Book Property') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <a href="{{ route('penyewa.property.show', $property->id_properti) }}" class="text-blue-500 hover:underline flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back to Property Details
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Property Overview -->
                        <div class="md:col-span-1 bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-xl font-semibold mb-4">Property Overview</h3>
                            <div class="mb-4">
                                <h4 class="text-lg font-medium">{{ $property->nama }}</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $property->tipe }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <p class="text-gray-600 dark:text-gray-400 text-sm">
                                    {{ $property->alamat }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-green-600 dark:text-green-400">
                                    Rp {{ number_format($property->harga * 0.0001, 0, ',', '.') }}/day
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500">
                                    (Rental rate: 0.01% of property value per day)
                                </p>
                            </div>
                        </div>

                        <!-- Booking Form -->
                        <div class="md:col-span-2">
                            <h3 class="text-xl font-semibold mb-4">Booking Information</h3>
                            
                            <form method="POST" action="{{ route('penyewa.pemesanan.store', $property->id_properti) }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                                        min="{{ date('Y-m-d') }}"
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="durasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration (days)</label>
                                    <input type="number" id="durasi" name="durasi" min="1" value="1" 
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        required>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price Calculation</label>
                                    <div class="bg-gray-100 dark:bg-gray-600 p-4 rounded-md">
                                        <div class="flex justify-between mb-2">
                                            <span>Daily rate:</span>
                                            <span id="dailyRate">Rp {{ number_format($property->harga * 0.0001, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between mb-2">
                                            <span>Duration:</span>
                                            <span id="durationDisplay">1 day(s)</span>
                                        </div>
                                        <div class="flex justify-between font-bold border-t border-gray-200 dark:border-gray-500 pt-2 mt-2">
                                            <span>Total:</span>
                                            <span id="totalPrice">Rp {{ number_format($property->harga * 0.0001 * 1, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method</label>
                                    <select id="metode_pembayaran" name="metode_pembayaran" 
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        required>
                                        <option value="transfer">Bank Transfer</option>
                                        <option value="kartu_kredit">Credit Card</option>
                                        <option value="tunai">Cash</option>
                                    </select>
                                </div>
                                
                                <div class="mb-6">
                                    <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (Optional)</label>
                                    <textarea id="catatan" name="catatan" rows="3" 
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Any special requests or notes for your booking..."></textarea>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                        Proceed to Payment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Price calculation JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const durasiInput = document.getElementById('durasi');
            const durationDisplay = document.getElementById('durationDisplay');
            const totalPriceDisplay = document.getElementById('totalPrice');
            const dailyRate = {{ $property->harga * 0.0001 }};
            
            durasiInput.addEventListener('change', updatePrice);
            durasiInput.addEventListener('keyup', updatePrice);
            
            function updatePrice() {
                const duration = parseInt(durasiInput.value) || 1;
                durationDisplay.textContent = `${duration} day(s)`;
                
                const total = dailyRate * duration;
                totalPriceDisplay.textContent = `Rp ${formatNumber(total)}`;
            }
            
            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        });
    </script>
</x-app-layout>