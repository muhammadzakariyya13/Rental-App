@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-green-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Pembayaran Berhasil!
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Terima kasih, pembayaran Anda telah berhasil diproses.
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
                
                @if($payment->method)
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Metode:</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $payment->method)) }}</dd>
                </div>
                @endif
                
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Tanggal:</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : $payment->created_at->format('d/m/Y H:i') }} WIB</dd>
                </div>
            </dl>
        </div>
        @endif

        <div class="space-y-3">
            @if($payment && $payment->isPaid())
                <a href="{{ route('payment.receipt', $payment) }}"
                   class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Download Kwitansi
                </a>
            @endif
            
            <a href="{{ route('payment.history') }}"
               class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Lihat Riwayat Pembayaran
            </a>
            
            <a href="{{ route('dashboard') }}"
               class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection