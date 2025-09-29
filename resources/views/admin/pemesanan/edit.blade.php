@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Edit Pemesanan #{{ $item->id_pemesanan }}</h1>
  <form method="POST" action="{{ route('admin.pemesanan.update', $item->id_pemesanan) }}" class="space-y-4">
    @csrf
    @method('PUT')
    <div>
      <label>Status Pemesanan</label>
      <select name="status_pemesanan" class="border rounded w-full p-2">
        <option value="pending" @selected(old('status_pemesanan',$item->status_pemesanan)==='pending')>pending</option>
        <option value="confirmed" @selected(old('status_pemesanan',$item->status_pemesanan)==='confirmed')>confirmed</option>
        <option value="cancelled" @selected(old('status_pemesanan',$item->status_pemesanan)==='cancelled')>cancelled</option>
      </select>
      @error('status_pemesanan')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label>Status Pembayaran</label>
      <select name="status_pembayaran" class="border rounded w-full p-2">
        <option value="belum_bayar" @selected(old('status_pembayaran',$item->status_pembayaran)==='belum_bayar')>belum_bayar</option>
        <option value="sudah_bayar" @selected(old('status_pembayaran',$item->status_pembayaran)==='sudah_bayar')>sudah_bayar</option>
      </select>
      @error('status_pembayaran')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
  </form>
</div>
@endsection