<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemesanan;
use App\Models\Properti;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemesanan::with(['penyewa', 'properti'])
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
                // Hitung tanggal selesai berdasarkan tanggal_pemesanan + lama_sewa (dalam bulan)
                $query->whereRaw('DATE_ADD(tanggal_pemesanan, INTERVAL lama_sewa MONTH) < NOW()')
                      ->where('status_pemesanan', 'confirmed');
            } else {
                $query->where('status_pemesanan', $request->status);
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pemesanan', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pemesanan', '<=', $request->date_to);
        }

        // Properti filter
        if ($request->filled('properti')) {
            $query->where('id_properti', $request->properti);
        }

        $bookings = $query->orderBy('created_at','desc')->paginate(10);

        // Stats untuk dashboard
        $totalBooking = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->count();

        $bookingPending = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('status_pemesanan', 'pending')->count();

        $bookingDiterima = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('status_pemesanan', 'confirmed')->count();

        $bookingDitolak = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('status_pemesanan', 'cancelled')->count();

        $bookingExpired = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->whereRaw('DATE_ADD(tanggal_pemesanan, INTERVAL lama_sewa MONTH) < NOW()')
          ->where('status_pemesanan', 'confirmed')->count();

        $bookingBulanIni = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->whereMonth('created_at', now()->month)
          ->whereYear('created_at', now()->year)
          ->count();

        // Hitung Total Pendapatan BERSIH (total_harga - biaya_admin)
        // Karena biaya admin untuk platform
        $totalPendapatan = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('status_pemesanan', 'confirmed')
          ->where('status_pembayaran', 'sudah_bayar')
          ->selectRaw('SUM(total_harga - biaya_admin) as pendapatan_bersih')
          ->value('pendapatan_bersih') ?? 0;

        // Pendapatan Bersih Bulan Ini
        $pendapatanBulanIni = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('status_pemesanan', 'confirmed')
          ->where('status_pembayaran', 'sudah_bayar')
          ->whereMonth('paid_at', now()->month)
          ->whereYear('paid_at', now()->year)
          ->selectRaw('SUM(total_harga - biaya_admin) as pendapatan_bersih')
          ->value('pendapatan_bersih') ?? 0;

        // Pendapatan Bersih Hari Ini
        $pendapatanHariIni = Pemesanan::whereHas('properti', function($q) {
            $q->where('pemilik_id', Auth::id());
        })->where('status_pemesanan', 'confirmed')
          ->where('status_pembayaran', 'sudah_bayar')
          ->whereDate('paid_at', today())
          ->selectRaw('SUM(total_harga - biaya_admin) as pendapatan_bersih')
          ->value('pendapatan_bersih') ?? 0;

        // List properti untuk filter
        $propertiList = Properti::where('pemilik_id', Auth::id())->get();

        return view('pemilik.pemesanan.index', compact(
            'bookings',
            'totalBooking',
            'bookingPending', 
            'bookingDiterima',
            'bookingDitolak',
            'bookingExpired',
            'bookingBulanIni',
            'totalPendapatan',
            'pendapatanBulanIni',
            'pendapatanHariIni',
            'propertiList'
        ));
    }

    public function show($id)
    {
        $booking = Pemesanan::with(['penyewa','properti'])->findOrFail($id);

        // pastikan pemilik punya akses
        if ($booking->properti->pemilik_id !== Auth::id()) {
            abort(403);
        }

        return view('pemilik.pemesanan.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:confirmed,cancelled,pending']);

        $booking = Pemesanan::with('properti')->findOrFail($id);
        if ($booking->properti->pemilik_id !== Auth::id()) abort(403);

        $oldStatus = $booking->status_pemesanan;
        $booking->status_pemesanan = $request->status;
        
        // Jika status diubah menjadi cancelled dan pembayaran sudah dilakukan, lakukan refund
        if ($request->status === 'cancelled' && $booking->status_pembayaran === 'sudah_bayar' && $booking->transaction_id) {
            try {
                // Konfigurasi Midtrans
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                \Midtrans\Config::$isSanitized = true;
                \Midtrans\Config::$is3ds = true;

                // Lakukan refund
                $refund_params = [
                    'refund_key' => 'refund-' . $booking->id_pemesanan . '-' . time(),
                    'amount' => (int) $booking->total_harga,
                    'reason' => 'Booking dibatalkan oleh pemilik properti'
                ];

                $refund = \Midtrans\Transaction::refund($booking->transaction_id, $refund_params);
                
                // Update status pembayaran
                $booking->status_pembayaran = 'refunded';
                $booking->refund_date = now();
                
                $booking->save();

                // Check if there are any other confirmed bookings for this property
                $hasActiveBooking = Pemesanan::where('id_properti', $booking->id_properti)
                    ->where('status_pemesanan', 'confirmed')
                    ->where('status_pembayaran', 'sudah_bayar')
                    ->where('id_pemesanan', '!=', $booking->id_pemesanan)
                    ->exists();

                // If no active bookings, change property status back to 'tersedia'
                if (!$hasActiveBooking) {
                    $booking->properti->update(['status' => 'tersedia']);
                }
                
                return back()->with('success', 'Status booking berhasil diperbarui dan refund telah diproses ke Midtrans.');
                
            } catch (\Exception $e) {
                // Jika refund gagal, tetap update status tapi beri notifikasi
                $booking->save();

                // Check if there are any other confirmed bookings for this property
                $hasActiveBooking = Pemesanan::where('id_properti', $booking->id_properti)
                    ->where('status_pemesanan', 'confirmed')
                    ->where('status_pembayaran', 'sudah_bayar')
                    ->where('id_pemesanan', '!=', $booking->id_pemesanan)
                    ->exists();

                // If no active bookings, change property status back to 'tersedia'
                if (!$hasActiveBooking) {
                    $booking->properti->update(['status' => 'tersedia']);
                }

                return back()->with('warning', 'Status booking berhasil diperbarui, namun refund gagal: ' . $e->getMessage() . '. Silakan lakukan refund manual di dashboard Midtrans.');
            }
        }

        // Handle status change to cancelled (without refund)
        if ($request->status === 'cancelled') {
            // Check if there are any other confirmed bookings for this property
            $hasActiveBooking = Pemesanan::where('id_properti', $booking->id_properti)
                ->where('status_pemesanan', 'confirmed')
                ->where('status_pembayaran', 'sudah_bayar')
                ->where('id_pemesanan', '!=', $booking->id_pemesanan)
                ->exists();

            // If no active bookings, change property status back to 'tersedia'
            if (!$hasActiveBooking) {
                $booking->properti->update(['status' => 'tersedia']);
            }
        }
        
        $booking->save();
        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $booking = Pemesanan::with('properti')->findOrFail($id);
        
        // Pastikan pemilik punya akses
        if ($booking->properti->pemilik_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus booking ini.');
        }
        
        // Hanya bisa hapus booking yang statusnya cancelled
        if ($booking->status_pemesanan !== 'cancelled') {
            return back()->with('error', 'Hanya booking yang dibatalkan yang bisa dihapus.');
        }
        
        $booking->delete();
        
        return back()->with('success', 'Booking berhasil dihapus.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|in:confirmed,cancelled,pending'
        ]);

        $ids = $request->ids;
        $status = $request->status;
        
        $updated = Pemesanan::whereIn('id_pemesanan', $ids)
            ->whereHas('properti', function($query) {
                $query->where('pemilik_id', Auth::id());
            })
            ->update([
                'status_pemesanan' => $status,
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
        $bookings = Pemesanan::with(['penyewa', 'properti'])
            ->whereHas('properti', function($q){
                $q->where('pemilik_id', Auth::id());
            })
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status_pemesanan', $request->status);
            })
            ->when($request->filled('date_from'), function($q) use ($request) {
                $q->whereDate('tanggal_pemesanan', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($q) use ($request) {
                $q->whereDate('tanggal_pemesanan', '<=', $request->date_to);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total pendapatan
        $totalPendapatan = $bookings->where('status_pemesanan', 'confirmed')
                                    ->where('status_pembayaran', 'sudah_bayar')
                                    ->sum('total_harga');

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $sheet->setTitle('Laporan Booking');
        
        // Header Laporan (Merged cells)
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'LAPORAN BOOKING & PENDAPATAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->mergeCells('A2:B2');
        $sheet->setCellValue('A2', 'Tanggal Export:');
        $sheet->setCellValue('C2', date('d/m/Y H:i:s'));
        
        $sheet->mergeCells('A3:B3');
        $sheet->setCellValue('A3', 'Total Booking:');
        $sheet->setCellValue('C3', count($bookings));
        
        $sheet->mergeCells('A4:B4');
        $sheet->setCellValue('A4', 'Total Pendapatan (Lunas):');
        $sheet->setCellValue('C4', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'));
        
        // Table Header (row 6)
        $headers = [
            'ID', 'Nama Pemesan', 'Email', 'No. HP', 
            'Nama Properti', 'Alamat Properti', 
            'Tanggal Pemesanan', 'Lama Sewa (Bulan)', 
            'Total Harga (Rp)', 'Status Booking', 
            'Status Pembayaran', 'Tanggal Bayar', 
            'Metode Pembayaran', 'Tanggal Dibuat'
        ];
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '6', $header);
            $col++;
        }
        
        // Style header
        $sheet->getStyle('A6:N6')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
        
        // Data rows
        $row = 7;
        foreach ($bookings as $booking) {
            $sheet->setCellValue('A' . $row, $booking->id_pemesanan);
            $sheet->setCellValue('B' . $row, $booking->penyewa->username ?? 'User');
            $sheet->setCellValue('C' . $row, $booking->penyewa->email ?? '-');
            $sheet->setCellValue('D' . $row, $booking->penyewa->no_hp ?? '-');
            $sheet->setCellValue('E' . $row, $booking->properti->nama);
            $sheet->setCellValue('F' . $row, $booking->properti->alamat ?? '-');
            $sheet->setCellValue('G' . $row, $booking->tanggal_pemesanan->format('d/m/Y'));
            $sheet->setCellValue('H' . $row, $booking->lama_sewa);
            $sheet->setCellValue('I' . $row, number_format($booking->total_harga, 0, ',', '.'));
            $sheet->setCellValue('J' . $row, $booking->status_pemesanan === 'confirmed' ? 'Lunas' : ($booking->status_pemesanan === 'pending' ? 'Pending' : 'Dibatalkan'));
            $sheet->setCellValue('K' . $row, $booking->status_pembayaran === 'sudah_bayar' ? 'Sudah Bayar' : 'Belum Bayar');
            $sheet->setCellValue('L' . $row, $booking->paid_at ? $booking->paid_at->format('d/m/Y H:i') : '-');
            $sheet->setCellValue('M' . $row, $booking->metode_pembayaran ?? '-');
            $sheet->setCellValue('N' . $row, $booking->created_at->format('d/m/Y H:i'));
            
            $row++;
        }
        
        // Style data rows with borders
        $lastRow = $row - 1;
        if ($lastRow >= 7) {
            $sheet->getStyle('A6:N' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]);
        }
        
        // Ringkasan section
        $row += 2;
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('A' . $row, '=== RINGKASAN ===');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        
        $row++;
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('A' . $row, 'Total Semua Booking:');
        $sheet->setCellValue('C' . $row, count($bookings));
        
        $row++;
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('A' . $row, 'Total Pending:');
        $sheet->setCellValue('C' . $row, $bookings->where('status_pemesanan', 'pending')->count());
        
        $row++;
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('A' . $row, 'Total Lunas:');
        $sheet->setCellValue('C' . $row, $bookings->where('status_pemesanan', 'confirmed')->count());
        
        $row++;
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('A' . $row, 'Total Dibatalkan:');
        $sheet->setCellValue('C' . $row, $bookings->where('status_pemesanan', 'cancelled')->count());
        
        // Pendapatan section
        $row += 2;
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('A' . $row, '=== PENDAPATAN ===');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        
        $row++;
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('A' . $row, 'Total Pendapatan (Lunas):');
        $sheet->setCellValue('C' . $row, 'Rp ' . number_format($totalPendapatan, 0, ',', '.'));
        $sheet->getStyle('C' . $row)->getFont()->setBold(true);
        
        // Auto-size columns
        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Generate filename and download
        $filename = 'laporan_booking_pendapatan_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Create writer and download
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function getStats()
    {
        $stats = [
            'total' => Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->count(),
            'pending' => Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->where('status_pemesanan', 'pending')->count(),
            'diterima' => Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->where('status_pemesanan', 'confirmed')->count(),
            'ditolak' => Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->where('status_pemesanan', 'cancelled')->count(),
            'totalPendapatan' => Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->where('status_pemesanan', 'confirmed')
              ->where('status_pembayaran', 'sudah_bayar')
              ->sum('total_harga'),
            'pendapatanBulanIni' => Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->where('status_pemesanan', 'confirmed')
              ->where('status_pembayaran', 'sudah_bayar')
              ->whereMonth('paid_at', now()->month)
              ->whereYear('paid_at', now()->year)
              ->sum('total_harga'),
            'pendapatanHariIni' => Pemesanan::whereHas('properti', function($q) {
                $q->where('pemilik_id', Auth::id());
            })->where('status_pemesanan', 'confirmed')
              ->where('status_pembayaran', 'sudah_bayar')
              ->whereDate('paid_at', today())
              ->sum('total_harga'),
        ];

        return response()->json($stats);
    }
}