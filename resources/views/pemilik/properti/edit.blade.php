<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Properti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-500">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('pemilik.properti.update', $property->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <x-input-label for="nama" :value="__('Nama Properti')" />
                            <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" 
                                :value="old('nama', $property->nama)" required autofocus />
                        </div>
                        
                        <div>
                            <x-input-label for="alamat" :value="__('Alamat')" />
                            <x-text-input id="alamat" name="alamat" type="text" class="mt-1 block w-full" 
                                :value="old('alamat', $property->alamat)" required />
                        </div>
                        
                        <div>
                            <x-input-label for="harga" :value="__('Harga (per bulan)')" />
                            <x-text-input id="harga" name="harga" type="number" class="mt-1 block w-full" 
                                :value="old('harga', $property->harga)" required />
                        </div>
                        
                        <div>
                            <x-input-label for="tipe" :value="__('Tipe Properti')" />
                            <x-text-input id="tipe" name="tipe" type="text" class="mt-1 block w-full" 
                                :value="old('tipe', $property->tipe)" required />
                        </div>
                        
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="tersedia" {{ old('status', $property->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="disewa" {{ old('status', $property->status) === 'disewa' ? 'selected' : '' }}>Disewa</option>
                                <option value="tidak tersedia" {{ old('status', $property->status) === 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                        </div>
                        
                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                            <textarea id="deskripsi" name="deskripsi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="5" required>{{ old('deskripsi', $property->deskripsi) }}</textarea>
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('pemilik.properti.index') }}" class="mr-2 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>