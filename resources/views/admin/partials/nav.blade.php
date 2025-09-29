@php
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
@endphp
@if($isAdmin)
<nav class="bg-slate-800 text-white">
  <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap gap-4 items-center">
    <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
    <a href="{{ route('admin.properti.index') }}" class="hover:underline">Properti</a>
    <a href="{{ route('admin.pemesanan.index') }}" class="hover:underline">Pemesanan</a>
    <a href="{{ route('admin.kontrak.index') }}" class="hover:underline">Kontrak</a>
    <a href="{{ route('admin.reviews.index') }}" class="hover:underline">Review</a>
    <a href="{{ route('admin.akun.index') }}" class="hover:underline">Akun</a>
  </div>
</nav>
@endif