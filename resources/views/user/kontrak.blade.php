@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Kontrak Saya</h1>
    <div class="space-y-3">
        @forelse($list as $k)
            <div class="border rounded p-3">
                <div><strong>ID:</strong> {{ $k->id_kontrak }}</div>
                <div><strong>Mulai:</strong> {{ $k->tgl_mulai_sewa }}</div>
                <div><strong>Akhir:</strong> {{ $k->tgl_akhir_sewa }}</div>
            </div>
        @empty
            <div>Tidak ada kontrak.</div>
        @endforelse
    </div>
</div>
@endsection
