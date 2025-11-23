@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-red-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100">
                <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Pembayaran Dibatalkan
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Pembayaran Anda telah dibatalkan atau tidak dapat diproses.
            </p>
        </div>

        @if($payment)
        <div class="bg-white shadow rounded-lg p-6 text-left">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Pembayaran</h3>
            
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">ID Pembayaran:</dt>
                    <dd class="text-sm font-medium text-gray-900">#{{ $payment->id }}</dd>
                </div>
                
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">ID Pesanan:</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $payment->order_id }}</dd>
                </div>
                
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Jumlah:</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ $payment->formatted_amount }}</dd>
                </div>
                
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Status:</dt>
                    <dd class="text-sm font-medium">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </dd>
                </div>
                
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Tanggal:</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $payment->created_at->format('d/m/Y H:i') }} WIB</dd>
                </div>
            </dl>
        </div>
        @endif

        <div class="space-y-3">
            @if($payment && $payment->isPending())
                <a href="{{ route('payment.show', $payment) }}"
                   class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Coba Bayar Lagi
                </a>
            @else
                <a href="{{ route('payment.create') }}"
                   class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Buat Pembayaran Baru
                </a>
            @endif
            
            <a href="{{ route('payment.history') }}"
               class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Lihat Riwayat Pembayaran
            </a>
            
            <a href="{{ route('dashboard') }}"
               class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection