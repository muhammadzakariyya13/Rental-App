@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Edit Properti</h1>
  <form method="POST" action="{{ route('admin.properti.update', $item->id_properti) }}" class="space-y-4">
    @csrf
    @method('PUT')
    <div>
      <label>Nama</label>
      <input name="nama" class="border rounded w-full p-2" value="{{ old('nama',$item->nama) }}" required>
      @error('nama')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Harga</label>
      <input name="harga" type="number" class="border rounded w-full p-2" value="{{ old('harga',$item->harga) }}" required>
      @error('harga')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="border rounded w-full p-2" rows="4" required>{{ old('deskripsi',$item->deskripsi) }}</textarea>
      @error('deskripsi')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Foto (URL)</label>
      <input name="foto" class="border rounded w-full p-2" value="{{ old('foto',$item->foto) }}">
      @error('foto')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Status</label>
      <select name="status" class="border rounded w-full p-2" required>
        <option value="tersedia" @selected(old('status',$item->status)==='tersedia')>Tersedia</option>
        <option value="disewa" @selected(old('status',$item->status)==='disewa')>Disewa</option>
      </select>
      @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
  </form>
</div>
@endsection