<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border-l-4 {{ $status === 'pending' ? 'border-yellow-500' : ($status === 'diterima' ? 'border-green-500' : 'border-red-500') }}">
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-1">
                    {{ $nama_properti }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    📍 {{ $lokasi }}
                </p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ 
                $status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                ($status === 'diterima' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') 
            }}">
                {{ $status === 'pending' ? '⏳ Menunggu' : ($status === 'diterima' ? '✅ Diterima' : '❌ Ditolak') }}
            </span>
        </div>

        <!-- Booking Details Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <div>
                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Check-in</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $check_in }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Check-out</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $check_out }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Durasi</p>
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $durasi }} hari</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Total</p>
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Rp {{ number_format($total_harga, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <a href="#" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                👁️ Detail
            </a>
            @if($status === 'pending')
                <button type="button" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                    🚫 Batalkan
                </button>
            @elseif($status === 'diterima')
                <a href="#" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors duration-200">
                    ⭐ Review
                </a>
            @endif
        </div>
    </div>
</div>
