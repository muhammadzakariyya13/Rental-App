@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
	<h1 class="text-2xl font-bold mb-4">{{ $properti->nama }}</h1>
	<p class="mb-2">Harga: {{ $properti->harga }}</p>
	<p class="mb-6">{{ $properti->deskripsi }}</p>

	@auth
		<a href="{{ route('reviews.create', $properti->id_properti) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded">Tulis Review</a>
	@endauth
</div>
@endsection
