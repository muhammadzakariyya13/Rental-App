<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Properti
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Data Properti</h3>
                    
                    <form action="{{ route('pemilik.properti.update', $properti->id_properti) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Properti -->
                            <div class="col-span-2">
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Properti</label>
                                <input type="text" name="nama" id="nama" 
                                       value="{{ $properti->nama }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                       required>
                            </div>

                            <!-- Alamat -->
                            <div class="col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                          required>{{ $properti->alamat }}</textarea>
                            </div>

                            <!-- Harga -->
                            <div>
                                <label for="harga" class="block text-sm font-medium text-gray-700">Harga per Bulan</label>
                                <input type="number" name="harga" id="harga" 
                                       value="{{ $properti->harga }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                       required>
                            </div>

                            <!-- Tipe -->
                            <div>
                                <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe Properti</label>
                                <select name="tipe" id="tipe" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="rumah" {{ $properti->tipe == 'rumah' ? 'selected' : '' }}>Rumah</option>
                                    <option value="apartemen" {{ $properti->tipe == 'apartemen' ? 'selected' : '' }}>Apartemen</option>
                                    <option value="kontrakan" {{ $properti->tipe == 'kontrakan' ? 'selected' : '' }}>Kontrakan</option>
                                    <option value="vila" {{ $properti->tipe == 'vila' ? 'selected' : '' }}>Vila</option>
                                </select>
                            </div>

                            <!-- Kamar Tidur -->
                            <div>
                                <label for="kamar_tidur" class="block text-sm font-medium text-gray-700">Kamar Tidur</label>
                                <input type="number" name="kamar_tidur" id="kamar_tidur" 
                                       value="{{ $properti->kamar_tidur }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <!-- Kamar Mandi -->
                            <div>
                                <label for="kamar_mandi" class="block text-sm font-medium text-gray-700">Kamar Mandi</label>
                                <input type="number" name="kamar_mandi" id="kamar_mandi" 
                                       value="{{ $properti->kamar_mandi }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="tersedia" {{ $properti->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="disewa" {{ $properti->status == 'disewa' ? 'selected' : '' }}>Disewa</option>
                                    <option value="maintenance" {{ $properti->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ $properti->deskripsi }}</textarea>
                            </div>

                            <!-- Foto Properti -->
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Foto Properti
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="foto" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                                    <span>Upload files</span>
                                                    <input id="foto" name="foto[]" type="file" class="sr-only" multiple accept="image/*">
                                                </label>
                                                <p class="pl-1">atau drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 10MB</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Preview existing photos -->
                                    <div id="photo-preview" class="grid grid-cols-3 gap-4 mt-4"></div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-span-2 flex justify-end space-x-3">
                                <a href="{{ route('pemilik.properti') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</a>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Properti</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>