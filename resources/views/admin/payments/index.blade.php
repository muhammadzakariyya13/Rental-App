@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Monitoring Pembayaran</h1>
        
        <!-- Filters -->
        <div class="flex space-x-2">
            <select onchange="filterByStatus(this.value)" class="border rounded px-3 py-1">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-600">Total Pembayaran</div>
            <div class="text-xl font-bold">{{ number_format($stats['total_count']) }}</div>
            <div class="text-xs text-gray-500">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-green-600">Berhasil</div>
            <div class="text-xl font-bold text-green-700">{{ number_format($stats['paid_count']) }}</div>
            <div class="text-xs text-green-600">Rp {{ number_format($stats['paid_amount'], 0, ',', '.') }}</div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-yellow-600">Pending</div>
            <div class="text-xl font-bold text-yellow-700">{{ number_format($stats['pending_count']) }}</div>
            <div class="text-xs text-yellow-600">Rp {{ number_format($stats['pending_amount'], 0, ',', '.') }}</div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-blue-600">Success Rate</div>
            <div class="text-xl font-bold text-blue-700">
                {{ $stats['total_count'] > 0 ? number_format(($stats['paid_count'] / $stats['total_count']) * 100, 1) : 0 }}%
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-600">Hari Ini</div>
            <div class="text-xl font-bold">
                {{ \App\Models\Payment::whereDate('created_at', today())->count() }}
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-600">Bulan Ini</div>
            <div class="text-xl font-bold">
                {{ \App\Models\Payment::whereMonth('created_at', now()->month)->count() }}
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Search -->
        <div class="p-4 border-b bg-gray-50">
            <form method="GET" class="flex space-x-4">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari ID pesanan, nama, email..." 
                       class="flex-1 border rounded px-3 py-2">
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="border rounded px-3 py-2">
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="border rounded px-3 py-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Cari
                </button>
                <a href="{{ route('admin.payments.index') }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Reset
                </a>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $payment->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $payment->order_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->customer_email }}</div>
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
                                <div>{{ $payment->created_at->format('d/m/Y H:i') }}</div>
                                @if($payment->paid_at)
                                    <div class="text-xs text-green-600">Paid: {{ $payment->paid_at->format('d/m/Y H:i') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.payments.show', $payment) }}" 
                                   class="text-blue-600 hover:text-blue-900">Detail</a>
                                
                                @if($payment->isPaid())
                                    <a href="{{ route('payment.receipt', $payment) }}" 
                                       class="text-green-600 hover:text-green-900">Receipt</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada data pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payments->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function filterByStatus(status) {
    const url = new URL(window.location);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location = url;
}
</script>
@endsection