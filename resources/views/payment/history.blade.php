@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Riwayat Pembayaran</h1>
        
        @if($payments->count() > 0)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $payment->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->order_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $payment->formatted_amount }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                              {{ $payment->isPaid() ? 'bg-green-100 text-green-800' : 
                                                 ($payment->isFailed() || $payment->isExpired() ? 'bg-red-100 text-red-800' : 
                                                 ($payment->isCancelled() ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->method ? ucwords(str_replace('_', ' ', $payment->method)) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('payment.show', $payment) }}" 
                                           class="text-blue-600 hover:text-blue-900">Detail</a>
                                        
                                        @if($payment->isPaid())
                                            <a href="{{ route('payment.receipt', $payment) }}" 
                                               class="text-green-600 hover:text-green-900">Kwitansi</a>
                                        @endif
                                        
                                        @if($payment->isPending() && $payment->payment_url)
                                            <a href="{{ $payment->payment_url }}" 
                                               class="text-orange-600 hover:text-orange-900">Bayar</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $payments->links() }}
                </div>
            </div>
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                @php
                    $totalPaid = $payments->where('status', 'paid')->sum('amount');
                    $totalPending = $payments->where('status', 'pending')->sum('amount');
                    $paidCount = $payments->where('status', 'paid')->count();
                    $totalCount = $payments->total();
                @endphp
                
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-600">Total Dibayar</div>
                    <div class="text-xl font-bold text-green-600">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-600">Menunggu Bayar</div>
                    <div class="text-xl font-bold text-yellow-600">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-600">Berhasil</div>
                    <div class="text-xl font-bold text-blue-600">{{ $paidCount }} transaksi</div>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-600">Total</div>
                    <div class="text-xl font-bold text-gray-600">{{ $totalCount }} transaksi</div>
                </div>
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pembayaran</h3>
                <p class="text-gray-500 mb-4">Anda belum memiliki riwayat pembayaran.</p>
                <a href="{{ route('payment.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                    Buat Pembayaran Baru
                </a>
            </div>
        @endif
    </div>
</div>
@endsection