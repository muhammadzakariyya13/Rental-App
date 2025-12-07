<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Properti') }}
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

                    <!-- Tombol Tambah -->
                    <div class="mb-6">
                        <a href="{{ route('admin.properti.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition mb-4 inline-block">
                            <i class="fas fa-plus mr-2"></i>Tambah Properti
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3">ID</th>
                                    <th class="px-6 py-3">Gambar</th>
                                    <th class="px-6 py-3">Nama Properti</th>
                                    <th class="px-6 py-3">Tipe</th>
                                    <th class="px-6 py-3">Lokasi</th>
                                    <th class="px-6 py-3">Kamar Tidur</th>
                                    <th class="px-6 py-3">Kamar Mandi</th>
                                    <th class="px-6 py-3">Luas Tanah (m²)</th>
                                    <th class="px-6 py-3">Harga/Bulan</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Pemilik</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($properti as $prop)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">{{ $prop->id_properti }}</td>
                                        <td class="px-6 py-4">
                                            @if($prop->gambar)
                                                <img src="{{ asset($prop->gambar) }}" alt="{{ $prop->nama }}" class="w-16 h-16 rounded object-cover">
                                            @else
                                                <span class="text-gray-500">Tidak ada gambar</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $prop->nama }}</td>
                                        <td class="px-6 py-4">{{ ucfirst($prop->tipe ?? '-') }}</td>
                                        <td class="px-6 py-4">{{ $prop->alamat }}</td>
                                        <td class="px-6 py-4">{{ $prop->kamar_tidur ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $prop->kamar_mandi ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $prop->luas_tanah ?? '-' }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($prop->harga, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded text-xs font-medium 
                                                {{ $prop->status == 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($prop->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $prop->pemilik->username ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2 justify-center items-center">
                                                <a href="{{ route('admin.properti.edit', $prop->id_properti) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs font-medium whitespace-nowrap">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.properti.destroy', $prop->id_properti) }}" method="POST" class="inline" onclick="return confirm('Yakin ingin menghapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium whitespace-nowrap">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data properti</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $properti->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
