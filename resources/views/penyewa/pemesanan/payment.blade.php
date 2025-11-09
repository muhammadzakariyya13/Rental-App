<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Booking Summary -->
                        <div class="md:col-span-1 bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-xl font-semibold mb-4">Booking Summary</h3>
                            
                            <div class="mb-4">
                                <h4 class="text-lg font-medium">{{ $booking->properti->nama }}</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $booking->properti->tipe }}
                                </span>
                            </div>
                            
                            <div class="mb-6">
                                <p class="text-gray-600 dark:text-gray-400 text-sm">
                                    {{ $booking->properti->alamat }}
                                </p>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Booking ID:</span>
                                    <span class="text-sm font-medium">{{ $booking->id_pemesanan }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Start Date:</span>
                                    <span class="text-sm font-medium">{{ date('d M Y', strtotime($booking->tanggal_mulai)) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">End Date:</span>
                                    <span class="text-sm font-medium">{{ date('d M Y', strtotime($booking->tanggal_selesai)) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Duration:</span>
                                    <span class="text-sm font-medium">{{ $booking->durasi }} day(s)</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Payment Method:</span>
                                    <span class="text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $booking->metode_pembayaran)) }}</span>
                                </div>
                                
                                <div class="flex justify-between pt-4 mt-4 border-t border-gray-200 dark:border-gray-600">
                                    <span class="font-semibold">Total Amount:</span>
                                    <span class="font-bold text-green-600 dark:text-green-400">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        <div class="md:col-span-2">
                            <h3 class="text-xl font-semibold mb-4">
                                @if($booking->metode_pembayaran == 'transfer')
                                    Bank Transfer Payment
                                @elseif($booking->metode_pembayaran == 'kartu_kredit')
                                    Credit Card Payment
                                @else
                                    Cash Payment
                                @endif
                            </h3>
                              @if(session('error'))
                                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                                    <p class="font-medium">Error:</p>
                                    <p>{{ session('error') }}</p>
                                </div>
                            @endif

                            <!-- Payment Gateway Info -->                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-md mb-6">
                                <h4 class="font-medium mb-2">Payment Processing</h4>
                                <p class="text-sm mb-3">Your payment will be processed securely using our payment gateway:</p>
                                <p class="text-xs mb-1">Gateway: <span class="font-bold">Doovera Payment</span></p>
                                <p class="text-xs mb-1">Website: <a href="https://payment-dummy.doovera.com" class="text-blue-500 hover:underline" target="_blank">payment-dummy.doovera.com</a></p>
                                
                                @php
                                    $gatewayAccessible = cache()->get('payment_gateway_accessible', null);
                                @endphp
                                
                                @if($gatewayAccessible === true)
                                    <div class="mt-2 px-2 py-1 text-xs inline-flex items-center rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Gateway Online
                                    </div>
                                @elseif($gatewayAccessible === false)
                                    <div class="mt-2 px-2 py-1 text-xs inline-flex items-center rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        Gateway Offline (Offline Payment Available)
                                    </div>
                                @else
                                    <div class="mt-2 px-2 py-1 text-xs inline-flex items-center rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Gateway Status Unknown
                                    </div>
                                @endif
                            </div>
                            
                            <form method="POST" action="{{ route('penyewa.payment.process', $booking->id_pemesanan) }}">
                                @csrf
                                
                                @if($booking->metode_pembayaran == 'transfer')
                                    <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-md mb-6">
                                        <h4 class="font-medium mb-2">Bank Transfer Instructions</h4>
                                        <p class="text-sm mb-3">Please transfer the payment to the following bank account:</p>
                                        
                                        <div class="space-y-2 mb-4">
                                            <div class="flex justify-between">
                                                <span class="text-sm font-medium">Bank:</span>
                                                <span class="text-sm">Bank Central Asia (BCA)</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm font-medium">Account Number:</span>
                                                <span class="text-sm">1234567890</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm font-medium">Account Name:</span>
                                                <span class="text-sm">PT. Rental App Indonesia</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm font-medium">Amount:</span>
                                                <span class="text-sm font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        
                                        <p class="text-sm text-blue-600 dark:text-blue-300">
                                            After completing the transfer, please click the "Confirm Payment" button below.
                                        </p>
                                    </div>
                                    
                                    <div class="mb-6">
                                        <label for="bukti_transfer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Upload Payment Proof (Optional)
                                        </label>
                                        <input type="file" id="bukti_transfer" name="bukti_transfer" 
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                            file:rounded-md file:border-0 file:text-sm file:font-semibold
                                            file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100
                                            dark:file:bg-blue-900 dark:file:text-blue-200 dark:text-gray-400">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            You can upload a screenshot or photo of your transfer receipt (PNG or JPG, max 2MB)
                                        </p>
                                    </div>
                                @elseif($booking->metode_pembayaran == 'kartu_kredit')
                                    <div class="mb-4">
                                        <label for="card_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Card Number</label>
                                        <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456"
                                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            required>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expiry Date</label>
                                            <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY"
                                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                required>
                                        </div>
                                        <div>
                                            <label for="cvv" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CVV</label>
                                            <input type="text" id="cvv" name="cvv" placeholder="123"
                                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-6">
                                        <label for="card_holder" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Card Holder Name</label>
                                        <input type="text" id="card_holder" name="card_holder" placeholder="John Doe"
                                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            required>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-md mb-6">
                                        <h4 class="font-medium mb-2">Cash Payment Instructions</h4>
                                        <p class="text-sm mb-3">Please pay the cash directly to our office at:</p>
                                        
                                        <div class="mb-4">
                                            <p class="text-sm">Rental App Office</p>
                                            <p class="text-sm">Jl. Sudirman No. 123, Jakarta Pusat</p>
                                            <p class="text-sm">Opening Hours: 09.00 - 17.00 (Monday - Friday)</p>
                                        </div>
                                        
                                        <p class="text-sm font-medium">Amount to Pay: <span class="font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span></p>
                                        <p class="text-sm text-yellow-600 dark:text-yellow-300 mt-2">
                                            After making the cash payment, our staff will provide you with a receipt.
                                        </p>
                                    </div>
                                @endif
                                
                                <div class="mb-6">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="terms" name="terms" required
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="terms" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            I agree to the <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">terms and conditions</a> and <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">cancellation policy</a>.
                                        </label>
                                    </div>
                                </div>                                <!-- Alternative Payment Options -->
                                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                    <h4 class="font-medium mb-3 text-sm">Payment Options</h4>
                                    
                                    <!-- Regular Payment Option -->
                                    <div class="flex items-center mb-3">
                                        <input type="radio" id="regular_payment" name="payment_option" value="regular" checked
                                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="regular_payment" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            Regular payment process through gateway
                                        </label>
                                    </div>
                                    
                                    <!-- Demo Mode - Only in local or development environment -->
                                    @if(app()->environment(['local', 'development']))
                                        <div class="flex items-center mb-3">
                                            <input type="radio" id="demo_success" name="payment_option" value="demo"
                                                onclick="document.getElementById('demo_success_checkbox').checked = true; document.getElementById('offline_payment_checkbox').checked = false;"
                                                class="rounded-full border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="demo_success" class="ml-2 text-sm text-green-700 dark:text-green-300 font-medium">
                                                Simulate successful payment
                                            </label>
                                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                Test Only
                                            </span>
                                        </div>
                                        <div class="ml-6 pl-1 mb-3 text-xs text-gray-500 dark:text-gray-400">
                                            This option bypasses the payment gateway and immediately marks the payment as successful.
                                            <input type="checkbox" id="demo_success_checkbox" name="demo_success" value="1" class="hidden">
                                        </div>
                                    @endif
                                    
                                    <!-- Offline Payment Option - Always available -->
                                    <div class="flex items-center mb-2">
                                        <input type="radio" id="offline_payment_radio" name="payment_option" value="offline"
                                            onclick="document.getElementById('offline_payment_checkbox').checked = true; document.getElementById('demo_success_checkbox').checked = false;"
                                            class="rounded-full border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="offline_payment_radio" class="ml-2 text-sm text-blue-700 dark:text-blue-300 font-medium">
                                            Pay later in person (offline payment)
                                        </label>
                                        <input type="checkbox" id="offline_payment_checkbox" name="offline_payment" value="1" class="hidden">
                                    </div>
                                    <div class="ml-6 pl-1 text-xs text-gray-500 dark:text-gray-400">
                                        Your booking will be confirmed, but you'll need to pay at our office within 24 hours.
                                    </div>
                                </div>
                                
                                <div class="flex justify-end space-x-4">
                                    <a href="{{ route('penyewa.pemesanan.cancel', $booking->id_pemesanan) }}" 
                                        class="px-4 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Cancel Booking
                                    </a>
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                        Confirm Payment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
