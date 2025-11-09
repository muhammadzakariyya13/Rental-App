<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Properti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('pemilik.properti.index') }}" class="text-blue-500 hover:underline">
                            &larr; Kembali ke daftar properti
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold">Nama Properti</h3>
                                <p>{{ $property->nama }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold">Alamat</h3>
                                <p>{{ $property->alamat }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold">Tipe</h3>
                                <p>{{ $property->tipe }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold">Harga (per bulan)</h3>
                                <p>Rp {{ number_format($property->harga, 0, ',', '.') }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold">Status</h3>
                                <p>
                                    @if($property->status === 'tersedia')
                                        <span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Tersedia</span>
                                    @elseif($property->status === 'disewa')
                                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">Disewa</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-sm font-medium px-2.5 py-0.5 rounded">Tidak Tersedia</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold">Deskripsi</h3>
                        <div class="mt-2 p-4 bg-gray-50 rounded">
                            <p>{{ $property->deskripsi }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex space-x-4">
                        <a href="{{ route('pemilik.properti.edit', $property->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Edit Properti
                        </a>
                        
                        <form action="{{ route('pemilik.properti.destroy', $property->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus properti ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Hapus Properti
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
