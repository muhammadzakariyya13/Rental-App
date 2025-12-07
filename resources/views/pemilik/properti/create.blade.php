<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Properti Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('pemilik.properti.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Gambar Properti -->
                            <div class="col-span-2">
                                <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar Properti</label>
                                <input type="file" name="gambar" id="gambar" accept="image/*"
                                       class="mt-1 block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-md file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100">
                                <p class="mt-1 text-sm text-gray-500">Upload gambar utama properti (JPG, PNG, max 10MB)</p>
                                @error('gambar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama Properti -->
                            <div class="col-span-2">
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Properti</label>
                                <input type="text" name="nama" id="nama" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Alamat -->
                            <div class="col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                          required></textarea>
                            </div>

                            <!-- Harga -->
                            <div>
                                <label for="harga" class="block text-sm font-medium text-gray-700">Harga per Bulan</label>
                                <input type="number" name="harga" id="harga" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Tipe Properti -->
                            <div>
                                <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe Properti</label>
                                <select name="tipe" id="tipe" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="rumah">Rumah</option>
                                    <option value="apartemen">Apartemen</option>
                                    <option value="kontrakan">Kontrakan</option>
                                    <option value="vila">Vila</option>
                                </select>
                            </div>

                            <!-- Kamar Tidur -->
                            <div>
                                <label for="kamar_tidur" class="block text-sm font-medium text-gray-700">Kamar Tidur</label>
                                <input type="number" name="kamar_tidur" id="kamar_tidur" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Kamar Mandi -->
                            <div>
                                <label for="kamar_mandi" class="block text-sm font-medium text-gray-700">Kamar Mandi</label>
                                <input type="number" name="kamar_mandi" id="kamar_mandi" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Luas Tanah -->
                            <div>
                                <label for="luas_tanah" class="block text-sm font-medium text-gray-700">Luas Tanah (m²)</label>
                                <input type="number" name="luas_tanah" id="luas_tanah" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Luas Bangunan -->
                            <div>
                                <label for="luas_bangunan" class="block text-sm font-medium text-gray-700">Luas Bangunan (m²)</label>
                                <input type="number" name="luas_bangunan" id="luas_bangunan" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="disewa">Disewa</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-span-2 flex justify-end space-x-3">
                                <a href="{{ route('pemilik.properti') }}" 
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Simpan Properti
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>