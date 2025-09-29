@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Daftar Kontrak</h1>
  <div class="overflow-x-auto">
    <table class="min-w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Properti</th>
          <th class="p-2 border">Mulai</th>
          <th class="p-2 border">Akhir</th>
          <th class="p-2 border"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $it)
        <tr>
          <td class="p-2 border">{{ $it->id_kontrak }}</td>
          <td class="p-2 border">{{ $it->properti?->nama }}</td>
          <td class="p-2 border">{{ $it->tgl_mulai_sewa }}</td>
          <td class="p-2 border">{{ $it->tgl_akhir_sewa }}</td>
          <td class="p-2 border"><a class="text-blue-600" href="{{ route('admin.kontrak.show',$it->id_kontrak) }}">Detail</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection