<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Properti Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('pemilik.properti.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Tambah Properti Baru
                        </a>
                    </div>

                    @if($properties->count() > 0)
                        <div class="overflow-x-auto relative">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">Nama Properti</th>
                                        <th scope="col" class="py-3 px-6">Alamat</th>
                                        <th scope="col" class="py-3 px-6">Tipe</th>
                                        <th scope="col" class="py-3 px-6">Harga</th>
                                        <th scope="col" class="py-3 px-6">Status</th>
                                        <th scope="col" class="py-3 px-6">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($properties as $property)
                                        <tr class="bg-white border-b">
                                            <td class="py-4 px-6">{{ $property->nama }}</td>
                                            <td class="py-4 px-6">{{ $property->alamat }}</td>
                                            <td class="py-4 px-6">{{ $property->tipe }}</td>
                                            <td class="py-4 px-6">Rp {{ number_format($property->harga, 0, ',', '.') }}</td>
                                            <td class="py-4 px-6">
                                                @if($property->status === 'tersedia')
                                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Tersedia</span>
                                                @elseif($property->status === 'disewa')
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Disewa</span>
                                                @else
                                                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">Tidak Tersedia</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 flex">
                                                <a href="{{ route('pemilik.properti.show', $property->id) }}" class="mr-2 font-medium text-blue-600 hover:underline">
                                                    Detail
                                                </a>
                                                <a href="{{ route('pemilik.properti.edit', $property->id) }}" class="mr-2 font-medium text-yellow-600 hover:underline">
                                                    Edit
                                                </a>
                                                <form action="{{ route('pemilik.properti.destroy', $property->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus properti ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-gray-500">Anda belum memiliki properti yang terdaftar.</p>
                            <a href="{{ route('pemilik.properti.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Tambah Properti Baru
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
