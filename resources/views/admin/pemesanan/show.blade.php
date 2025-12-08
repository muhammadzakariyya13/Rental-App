<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Pemesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Info Pemesanan -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Pemesanan</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">ID Pemesanan</label>
                                <p class="text-lg">#{{ $pemesanan->id_pemesanan }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Booking</label>
                                <p class="text-lg">{{ $pemesanan->tanggal_pemesanan->format('d M Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Lama Sewa</label>
                                <p class="text-lg">{{ $pemesanan->lama_sewa }} hari</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Subtotal</label>
                                <p class="text-lg">Rp {{ number_format($pemesanan->total_harga - $pemesanan->biaya_admin, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Biaya Admin (0.5%)</label>
                                <p class="text-lg">Rp {{ number_format($pemesanan->biaya_admin, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Total Harga</label>
                                <p class="text-lg font-semibold text-blue-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Penyewa -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Penyewa</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama</label>
                                <p class="text-lg">{{ $pemesanan->penyewa->name ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                <p class="text-lg">{{ $pemesanan->penyewa->email ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">No. Telepon</label>
                                <p class="text-lg">{{ $pemesanan->penyewa->phone_number ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Properti -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Properti</h3>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                @if($pemesanan->properti && $pemesanan->properti->gambar)
                                    <img src="{{ asset($pemesanan->properti->gambar) }}" 
                                         alt="{{ $pemesanan->properti->nama }}"
                                         class="h-32 w-32 rounded object-cover">
                                @else
                                    <div class="h-32 w-32 rounded bg-gray-200 flex items-center justify-center text-gray-400">
                                        No Image
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-xl font-semibold mb-2">{{ $pemesanan->properti->nama ?? '-' }}</p>
                                <p class="text-gray-600 dark:text-gray-400 mb-1">{{ $pemesanan->properti->alamat ?? '-' }}</p>
                                <p class="text-sm text-gray-500">Harga per hari: Rp {{ number_format($pemesanan->properti->harga ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Pembayaran -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Status Pembayaran</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                                <span class="inline-block mt-1 px-3 py-1 rounded text-sm font-medium 
                                    {{ $pemesanan->status_pembayaran == 'sudah_bayar' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $pemesanan->status_pembayaran == 'sudah_bayar' ? 'Lunas' : 'Pending' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Metode Pembayaran</label>
                                <p class="text-lg">{{ $pemesanan->payment_type ?? $pemesanan->metode_pembayaran ?? '-' }}</p>
                            </div>
                            @if($pemesanan->paid_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dibayar Pada</label>
                                <p class="text-lg">{{ $pemesanan->paid_at->format('d M Y H:i') }}</p>
                            </div>
                            @endif
                            @if($pemesanan->transaction_id)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Transaction ID</label>
                                <p class="text-sm font-mono">{{ $pemesanan->transaction_id }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Info Kontrak (jika ada) -->
                    @if($pemesanan->kontrak)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Kontrak</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</label>
                                <p class="text-lg">{{ $pemesanan->kontrak->tanggal_mulai ? \Carbon\Carbon::parse($pemesanan->kontrak->tanggal_mulai)->format('d M Y') : '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</label>
                                <p class="text-lg">{{ $pemesanan->kontrak->tanggal_selesai ? \Carbon\Carbon::parse($pemesanan->kontrak->tanggal_selesai)->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex gap-3 mt-6">
                        <a href="{{ route('admin.pemesanan.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
