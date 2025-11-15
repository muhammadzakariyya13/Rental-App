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

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3">ID</th>
                                    <th class="px-6 py-3">Properti</th>
                                    <th class="px-6 py-3">Penyewa</th>
                                    <th class="px-6 py-3">Tanggal Pemesanan</th>
                                    <th class="px-6 py-3">Lama Sewa</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Pembayaran</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($pemesanan as $pesan)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">{{ $pesan->id_pemesanan }}</td>
                                        <td class="px-6 py-4">{{ $pesan->properti->nama ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $pesan->penyewa->username ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $pesan->tanggal_pemesanan->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $pesan->lama_sewa }} hari</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded text-xs font-medium 
                                                {{ $pesan->status_pemesanan === 'diterima' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $pesan->status_pemesanan === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $pesan->status_pemesanan === 'ditolak' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ !in_array($pesan->status_pemesanan, ['diterima', 'pending', 'ditolak']) ? 'bg-blue-100 text-blue-800' : '' }}">
                                                {{ ucfirst($pesan->status_pemesanan) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded text-xs font-medium 
                                                {{ $pesan->status_pembayaran == 'sudah_bayar' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $pesan->status_pembayaran)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 space-x-2">
                                            <a href="{{ route('admin.pemesanan.show', $pesan->id_pemesanan) }}" class="text-blue-500 hover:text-blue-700">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.pemesanan.edit', $pesan->id_pemesanan) }}" class="text-yellow-500 hover:text-yellow-700">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.pemesanan.destroy', $pesan->id_pemesanan) }}" method="POST" class="inline" onclick="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data pemesanan</td>
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
