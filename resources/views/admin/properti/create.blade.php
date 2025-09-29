@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Tambah Properti</h1>
  <form method="POST" action="{{ route('admin.properti.store') }}" class="space-y-4">
    @csrf
    <div>
      <label>Nama</label>
      <input name="nama" class="border rounded w-full p-2" required>
      @error('nama')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Harga</label>
      <input name="harga" type="number" class="border rounded w-full p-2" required>
      @error('harga')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="border rounded w-full p-2" rows="4" required></textarea>
      @error('deskripsi')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Foto (URL)</label>
      <input name="foto" class="border rounded w-full p-2">
      @error('foto')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Status</label>
      <select name="status" class="border rounded w-full p-2" required>
        <option value="tersedia">Tersedia</option>
        <option value="disewa">Disewa</option>
      </select>
      @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
  </form>
</div>
@endsection