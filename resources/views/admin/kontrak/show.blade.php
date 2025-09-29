@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Detail Kontrak #{{ $item->id_kontrak }}</h1>
  <div class="space-y-2">
    <div><strong>Properti:</strong> {{ $item->properti?->nama }}</div>
    <div><strong>Pemesanan:</strong> #{{ $item->pemesanan?->id_pemesanan }}</div>
    <div><strong>Mulai:</strong> {{ $item->tgl_mulai_sewa }}</div>
    <div><strong>Akhir:</strong> {{ $item->tgl_akhir_sewa }}</div>
    <div><strong>Harga Sewa:</strong> {{ $item->harga_sewa }}</div>
  </div>
</div>
@endsection