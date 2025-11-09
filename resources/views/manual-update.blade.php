@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Update Status Pemesanan Manual</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h5 class="mb-4">Daftar Pemesanan Terbaru</h5>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Properti</th>
                                    <th>Penyewa</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Status Pembayaran</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemesananList as $pemesanan)
                                    <tr>
                                        <td>{{ $pemesanan->id_pemesanan }}</td>
                                        <td>{{ $pemesanan->properti->nama_properti ?? 'Properti tidak ditemukan' }}</td>
                                        <td>{{ $pemesanan->penyewa->name ?? 'Penyewa tidak ditemukan' }}</td>
                                        <td>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($pemesanan->status == 'confirmed') bg-success
                                                @elseif($pemesanan->status == 'pending') bg-warning
                                                @elseif($pemesanan->status == 'cancelled') bg-danger
                                                @else bg-secondary @endif">
                                                {{ $pemesanan->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($pemesanan->payment_status == 'paid') bg-success
                                                @elseif($pemesanan->payment_status == 'pending') bg-warning
                                                @elseif($pemesanan->payment_status == 'failed' || $pemesanan->payment_status == 'cancelled') bg-danger
                                                @else bg-secondary @endif">
                                                {{ $pemesanan->payment_status }}
                                            </span>
                                        </td>
                                        <td>{{ $pemesanan->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#updateModal-{{ $pemesanan->id_pemesanan }}">
                                                Update Status
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data pemesanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk setiap pemesanan -->
@foreach($pemesananList as $pemesanan)
<div class="modal fade" id="updateModal-{{ $pemesanan->id_pemesanan }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Pemesanan #{{ $pemesanan->id_pemesanan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('manual.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_pemesanan" value="{{ $pemesanan->id_pemesanan }}">
                    
                    <div class="mb-3">
                        <label for="status-{{ $pemesanan->id_pemesanan }}" class="form-label">Status Pemesanan</label>
                        <select class="form-select" id="status-{{ $pemesanan->id_pemesanan }}" name="status">
                            <option value="pending" {{ $pemesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $pemesanan->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ $pemesanan->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ $pemesanan->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="payment_status-{{ $pemesanan->id_pemesanan }}" class="form-label">Status Pembayaran</label>
                        <select class="form-select" id="payment_status-{{ $pemesanan->id_pemesanan }}" name="payment_status">
                            <option value="unpaid" {{ $pemesanan->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="pending" {{ $pemesanan->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $pemesanan->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $pemesanan->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ $pemesanan->payment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <small>Catatan: Jika status pemesanan diubah menjadi "confirmed", status properti akan otomatis diubah menjadi "disewa".</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
