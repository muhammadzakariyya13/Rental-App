@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Daftar Akun</h1>
  <div class="overflow-x-auto">
    <table class="min-w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Nama</th>
          <th class="p-2 border">Email</th>
          <th class="p-2 border">Username</th>
          <th class="p-2 border">Role</th>
          <th class="p-2 border"></th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $it)
        <tr>
          <td class="p-2 border">{{ $it->id }}</td>
          <td class="p-2 border">{{ $it->nama }}</td>
          <td class="p-2 border">{{ $it->email }}</td>
          <td class="p-2 border">{{ $it->username }}</td>
          <td class="p-2 border">{{ $it->role?->nama ?? '-' }}</td>
          <td class="p-2 border"><a href="{{ route('admin.akun.edit',$it->id) }}" class="text-blue-600">Edit</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection