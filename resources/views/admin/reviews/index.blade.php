@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Daftar Review</h1>
  <div class="overflow-x-auto">
    <table class="min-w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">User</th>
          <th class="p-2 border">Properti</th>
          <th class="p-2 border">Rating</th>
          <th class="p-2 border">Komentar</th>
          <th class="p-2 border"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $it)
        <tr>
          <td class="p-2 border">{{ $it->id }}</td>
          <td class="p-2 border">{{ $it->akun?->nama }}</td>
          <td class="p-2 border">{{ $it->properti?->nama }}</td>
          <td class="p-2 border">{{ $it->rating }}</td>
          <td class="p-2 border max-w-md">{{ $it->komentar }}</td>
          <td class="p-2 border">
            <form method="POST" action="{{ route('admin.reviews.destroy',$it->id) }}">
              @csrf
              @method('DELETE')
              <button class="text-red-600" onclick="return confirm('Hapus review ini?')">Hapus</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection