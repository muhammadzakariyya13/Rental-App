@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-xl font-semibold mb-4">Tambah Review untuk: {{ $properti->nama }}</h1>

    <form method="POST" action="{{ route('reviews.store', $properti->id_properti) }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Rating (1-5)</label>
            <input type="number" min="1" max="5" name="rating" class="mt-1 block w-full border rounded p-2" required />
            @error('rating')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Komentar</label>
            <textarea name="komentar" rows="4" class="mt-1 block w-full border rounded p-2" required></textarea>
            @error('komentar')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kirim Review</button>
    </form>
</div>
@endsection
