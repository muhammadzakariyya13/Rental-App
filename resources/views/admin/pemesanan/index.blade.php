<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Pemesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($message = Session::get('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ $message }}
                        </div>
                    @endif

                    <!-- Filter Status -->
                    <div class="mb-4 flex gap-2">
                        <a href="{{ route('admin.pemesanan.index') }}" 
                           class="px-4 py-2 rounded {{ !request('status') || request('status') == 'semua' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Semua
                        </a>
                        <a href="{{ route('admin.pemesanan.index', ['status' => 'pending']) }}" 
                           class="px-4 py-2 rounded {{ request('status') == 'pending' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Pending
                        </a>
                        <a href="{{ route('admin.pemesanan.index', ['status' => 'sudah_bayar']) }}" 
                           class="px-4 py-2 rounded {{ request('status') == 'sudah_bayar' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Lunas
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3">ID</th>
                                    <th class="px-6 py-3">Penyewa</th>
                                    <th class="px-6 py-3">Properti</th>
                                    <th class="px-6 py-3">Tanggal</th>
                                    <th class="px-6 py-3">Total Harga</th>
                                    <th class="px-6 py-3">Biaya Admin</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($pemesanan as $pesan)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">{{ $pesan->id_pemesanan }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $pesan->penyewa->name ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $pesan->penyewa->email ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    @if($pesan->properti && $pesan->properti->gambar)
                                                        <img src="{{ asset($pesan->properti->gambar) }}" 
                                                             alt="{{ $pesan->properti->nama }}"
                                                             class="h-12 w-12 rounded object-cover">
                                                    @else
                                                        <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center text-gray-400 text-xs">
                                                            No Img
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium">{{ $pesan->properti->nama ?? '-' }}</div>
                                                    <div class="text-xs text-gray-500">{{ Str::limit($pesan->properti->alamat ?? '', 25) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm">{{ $pesan->tanggal_pemesanan->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $pesan->lama_sewa }} hari</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium">Rp {{ number_format($pesan->total_harga, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-orange-600">Rp {{ number_format($pesan->biaya_admin, 0, ',', '.') }}</div>
                                            <div class="text-xs text-gray-500">0.5%</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded text-xs font-medium 
                                                {{ $pesan->status_pembayaran == 'sudah_bayar' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $pesan->status_pembayaran == 'sudah_bayar' ? 'Lunas' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 space-x-2">
                                            <a href="{{ route('admin.pemesanan.show', $pesan->id_pemesanan) }}" 
                                               class="inline-block px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data pemesanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $pemesanan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
