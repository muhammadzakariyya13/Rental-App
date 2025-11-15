<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Pemesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">ID Pemesanan</label>
                            <p class="text-lg">{{ $pemesanan->id_pemesanan }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Properti</label>
                            <p class="text-lg">{{ $pemesanan->properti->nama ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Penyewa</label>
                            <p class="text-lg">{{ $pemesanan->penyewa->username ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Tanggal Pemesanan</label>
                            <p class="text-lg">{{ $pemesanan->tanggal_pemesanan->format('d M Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Lama Sewa</label>
                            <p class="text-lg">{{ $pemesanan->lama_sewa }} hari</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Metode Pembayaran</label>
                            <p class="text-lg">{{ $pemesanan->metode_pembayaran }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Status Pemesanan</label>
                            <span class="px-3 py-1 rounded text-sm font-medium 
                                {{ $pemesanan->status_pemesanan === 'diterima' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $pemesanan->status_pemesanan === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $pemesanan->status_pemesanan === 'ditolak' ? 'bg-red-100 text-red-800' : '' }}
                                {{ !in_array($pemesanan->status_pemesanan, ['diterima', 'pending', 'ditolak']) ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ ucfirst($pemesanan->status_pemesanan) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Status Pembayaran</label>
                            <span class="px-3 py-1 rounded text-sm font-medium {{ $pemesanan->status_pembayaran == 'sudah_bayar' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $pemesanan->status_pembayaran)) }}
                            </span>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('admin.pemesanan.edit', $pemesanan->id_pemesanan) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded transition">
                                Edit
                            </a>
                            <a href="{{ route('admin.pemesanan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
