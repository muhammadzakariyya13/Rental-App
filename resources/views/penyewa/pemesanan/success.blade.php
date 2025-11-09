<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Booking Successful') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('info'))
                        <div class="mb-6 p-4 bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 rounded-md">
                            {{ session('info') }}
                        </div>
                    @endif
                      <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full mb-4">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold">Booking Successful!</h3>
                        
                        @php
                            $isSimulated = $booking->payment_transaction_id && (strpos($booking->payment_transaction_id, 'SIM-') === 0);
                            $isOfflinePayment = $booking->payment_transaction_id && (strpos($booking->payment_transaction_id, 'OFFLINE-') === 0);
                        @endphp
                        
                        @if(session('success'))
                            <p class="mt-2 text-green-600 dark:text-green-400 font-medium">{{ session('success') }}</p>
                        @elseif($isSimulated)
                            <p class="mt-2 text-green-600 dark:text-green-400 font-medium">Payment simulation was successful.</p>
                        @elseif($isOfflinePayment)
                            <p class="mt-2 text-blue-600 dark:text-blue-400 font-medium">Your booking is confirmed with offline payment option.</p>
                        @elseif($booking->payment_status === 'completed')
                            <p class="mt-2 text-green-600 dark:text-green-400 font-medium">Your payment was processed successfully!</p>
                        @else
                            <p class="mt-2 text-gray-600 dark:text-gray-400">Your booking has been confirmed successfully.</p>
                        @endif
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg mb-6">
                        <h4 class="text-lg font-semibold mb-4">Booking Details</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Booking ID:</p>
                                <p class="font-medium">{{ $booking->id_pemesanan }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Property:</p>
                                <p class="font-medium">{{ $booking->properti->nama }}</p>
                            </div>
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
                                <p class="text-sm text-gray-600 dark:text-gray-400">Payment Method:</p>
                                <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $booking->metode_pembayaran)) }}</p>
                            </div>                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Booking Status:</p>
                                <p class="font-medium">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </p>
                            </div>                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Payment Status:</p>
                                <p class="font-medium">
                                    @php
                                        $paymentStatusClass = match($booking->payment_status) {
                                            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'processing' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'failed', 'error' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                            default => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                        };
                                        
                                        $paymentStatusText = match($booking->payment_status) {
                                            'completed' => 'Completed',
                                            'processing' => 'Processing',
                                            'pending' => 'Pending',
                                            'failed' => 'Failed',
                                            'cancelled' => 'Cancelled',
                                            'error' => 'Error',
                                            default => 'Pending'
                                        };
                                          // Check transaction types
                                        $isOfflineTransaction = $booking->payment_transaction_id && (
                                            strpos($booking->payment_transaction_id, 'OFFLINE-') === 0 ||
                                            strpos($booking->payment_transaction_id, 'EMERGENCY-') === 0
                                        );
                                        
                                        $isSimulatedTransaction = $booking->payment_transaction_id && (
                                            strpos($booking->payment_transaction_id, 'SIM-') === 0
                                        );
                                    @endphp
                                    
                                    <span class="px-2 py-1 text-xs rounded-full {{ $paymentStatusClass }}">
                                        {{ $paymentStatusText }}
                                    </span>
                                    
                                    @if($isOfflineTransaction)
                                        <span class="px-2 py-1 ml-2 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            Offline Transaction
                                        </span>
                                    @elseif($isSimulatedTransaction)
                                        <span class="px-2 py-1 ml-2 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Simulated Payment
                                        </span>
                                    @endif
                                </p>
                                
                                @if($isOfflineTransaction)
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        Transaction ID: {{ $booking->payment_transaction_id }}
                                    </p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Amount:</p>
                                <p class="font-bold text-green-600 dark:text-green-400">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>                    <div class="mb-8 bg-blue-50 dark:bg-blue-900 p-4 rounded-md">
                        <h4 class="text-md font-semibold mb-2">What's Next?</h4>
                        <ul class="list-disc list-inside text-sm space-y-2">
                            <li>A confirmation email has been sent to your registered email address.</li>
                            <li>The property owner will be notified about your booking.</li>
                            
                            @php
                                $isSimulated = $booking->payment_transaction_id && (strpos($booking->payment_transaction_id, 'SIM-') === 0);
                                $isOfflinePayment = $booking->payment_transaction_id && (strpos($booking->payment_transaction_id, 'OFFLINE-') === 0);
                            @endphp
                            
                            @if($isOfflinePayment)
                                <li class="text-blue-700 dark:text-blue-300 font-medium">Please complete your payment at our office within 24 hours to finalize your booking.</li>
                                <li class="text-blue-700 dark:text-blue-300">Our office is located at Jl. Sudirman No. 123, Jakarta Pusat.</li>
                                <li class="text-blue-700 dark:text-blue-300">Office hours: 09.00 - 17.00 (Monday - Friday)</li>
                            @elseif($isSimulated)
                                <li class="text-green-700 dark:text-green-300 font-medium">This is a simulated payment for testing purposes. No real payment was processed.</li>
                            @elseif($booking->payment_status === 'completed')
                                <li class="text-green-700 dark:text-green-300 font-medium">Your payment has been processed and the property is now reserved for you.</li>
                            @elseif($booking->payment_status === 'pending')
                                <li class="text-yellow-700 dark:text-yellow-300 font-medium">Your payment is pending confirmation. We'll update you once it's processed.</li>
                            @endif
                            
                            <li>You can view all your bookings in the "My Bookings" section.</li>
                            <li>If you have any questions, please contact our customer service.</li>
                        </ul>
                    </div>

                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('penyewa.dashboard') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                            Go to Dashboard
                        </a>
                        <a href="{{ route('penyewa.pemesanan.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            View My Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
