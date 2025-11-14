<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sewa;
use App\Models\Properti;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Sewa::with(['penyewa', 'properti'])
            ->whereHas('properti', function($q){
                $q->where('pemilik_id', Auth::id());
            });

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('penyewa', function($q) use ($search) {
                $q->where('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status == 'expired') {
                $query->where('tanggal_selesai', '<', now())
                      ->where('status', 'diterima');
            } else {
                $query->where('status', $request->status);
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Properti filter
        if ($request->filled('properti')) {
            $query->where('id_properti', $request->properti);
        }

        $bookings = $query->orderBy('created_at','desc')->paginate(10);

        // Stats untuk dashboard
        $totalBooking = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->count();

        $bookingPending = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('status', 'pending')->count();

        $bookingDiterima = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('status', 'diterima')->count();

        $bookingDitolak = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('status', 'ditolak')->count();

        $bookingExpired = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('tanggal_selesai', '<', now())
          ->where('status', 'diterima')->count();

        $bookingBulanIni = Sewa::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->whereMonth('created_at', now()->month)
          ->whereYear('created_at', now()->year)
          ->count();

        // List properti untuk filter
        $propertiList = Properti::where('pemilik_id', Auth::id())->get();

        return view('pemilik.bookings.index', compact(
            'bookings',
            'totalBooking',
            'bookingPending', 
            'bookingDiterima',
            'bookingDitolak',
            'bookingExpired',
            'bookingBulanIni',
            'propertiList'
        ));
    }

    public function show($id)
    {
        $booking = Sewa::with(['penyewa','properti'])->findOrFail($id);

        // pastikan pemilik punya akses
        if ($booking->properti->pemilik_id !== Auth::id()) {
            abort(403);
        }

        return view('pemilik.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:diterima,ditolak,dibatalkan']);

        $booking = Sewa::with('properti')->findOrFail($id);
        if ($booking->properti->pemilik_id !== Auth::id()) abort(403);

        $booking->status = $request->status;
        if ($request->status === 'diterima') {
            $booking->tanggal_diterima = now();
        }
        $booking->save();

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|in:diterima,ditolak,dibatalkan'
        ]);

        $ids = $request->ids;
        $status = $request->status;
        
        $updated = Sewa::whereIn('id_sewa', $ids)
            ->whereHas('properti', function($query) {
                $query->where('pemilik_id', Auth::id());
            })
            ->update([
                'status' => $status,
                'tanggal_diterima' => $status === 'diterima' ? now() : null,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true, 
            'message' => "Berhasil mengupdate {$updated} booking ke status {$status}",
            'updated_count' => $updated
        ]);
    }

    public function exportExcel(Request $request)
    {
        $bookings = Sewa::with(['penyewa', 'properti'])
            ->whereHas('properti', function($q){
                $q->where('pemilik_id', Auth::id());
            })
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('date_from'), function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->get();

        $filename = 'booking_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Pemesan', 'Email', 'Properti', 'Check-in', 'Check-out', 'Total', 'Status', 'Tanggal Booking']);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id_sewa,
                    $booking->penyewa->username ?? 'User',
                    $booking->penyewa->email ?? '',
                    $booking->properti->nama,
                    $booking->tanggal_mulai->format('d/m/Y'),
                    $booking->tanggal_selesai->format('d/m/Y'),
                    'Rp ' . number_format($booking->total_harga, 0, ',', '.'),
                    ucfirst($booking->status),
                    $booking->created_at->format('d/m/Y H:i')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getStats()
    {
        $stats = [
            'total' => Sewa::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->count(),
            'pending' => Sewa::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->where('status', 'pending')->count(),
            'diterima' => Sewa::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->where('status', 'diterima')->count(),
            'ditolak' => Sewa::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->where('status', 'ditolak')->count(),
        ];

        return response()->json($stats);
    }
}