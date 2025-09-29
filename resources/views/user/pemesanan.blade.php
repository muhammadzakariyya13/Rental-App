@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Pemesanan Saya</h1>
    <div class="space-y-3">
        @forelse($list as $p)
            <div class="border rounded p-3">
                <div><strong>ID:</strong> {{ $p->id_pemesanan }}</div>
                <div><strong>Tanggal:</strong> {{ $p->tanggal_pemesanan }}</div>
                <div><strong>Status:</strong> {{ $p->status_pemesanan }}</div>
            </div>
        @empty
            <div>Tidak ada pemesanan.</div>
        @endforelse
    </div>
</div>
@endsection
