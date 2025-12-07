# Payment Status Synchronization Documentation

## Status Flow

Sistem ini menggunakan 3 status utama untuk pemesanan:

### 1. **PENDING** (Belum Bayar)
- `status_pemesanan`: `pending`
- `status_pembayaran`: `belum_bayar`
- **Kondisi**: Pemesanan baru dibuat, menunggu pembayaran
- **User dapat**: Melakukan pembayaran atau membatalkan pemesanan
- **Midtrans status**: `pending`

### 2. **CONFIRMED** (Sudah Bayar)
- `status_pemesanan`: `confirmed`
- `status_pembayaran`: `sudah_bayar`
- **Kondisi**: Pembayaran berhasil dan dikonfirmasi
- **User dapat**: Melihat kontrak dan detail pemesanan
- **Midtrans status**: `capture` (credit card) atau `settlement` (payment methods lainnya)
- **Properti status**: Otomatis berubah menjadi `disewa`

### 3. **CANCELLED** (Dibatalkan)
- `status_pemesanan`: `cancelled`
- `status_pembayaran`: `belum_bayar`
- **Kondisi**: Pembayaran dibatalkan, expired, atau ditolak
- **User dapat**: Hanya melihat detail (tidak bisa bayar lagi)
- **Midtrans status**: `deny`, `expire`, `cancel`

## Midtrans Integration

### Transaction Status Mapping

| Midtrans Status | App Status | Pembayaran | Keterangan |
|----------------|------------|-----------|------------|
| `pending` | `pending` | `belum_bayar` | Menunggu pembayaran |
| `capture` (fraud: accept) | `confirmed` | `sudah_bayar` | Pembayaran berhasil (CC) |
| `settlement` | `confirmed` | `sudah_bayar` | Pembayaran berhasil |
| `deny` | `cancelled` | `belum_bayar` | Pembayaran ditolak |
| `expire` | `cancelled` | `belum_bayar` | Pembayaran expired |
| `cancel` | `cancelled` | `belum_bayar` | Pembayaran dibatalkan |

### Auto-Sync Mechanism

Sistem melakukan sinkronisasi otomatis dengan Midtrans pada:

1. **Halaman Index Pemesanan** (`/penyewa/pemesanan`)
   - Sync semua pemesanan dengan status `pending`
   - Interval: Setiap kali halaman di-load

2. **Halaman Payment** (`/penyewa/pemesanan/{id}/payment`)
   - Sync status sebelum menampilkan halaman
   - Auto-refresh setiap 5 detik jika status masih `pending`
   - Auto-stop refresh jika sudah `confirmed` atau `cancelled`

3. **Midtrans Webhook/Callback** (`POST /midtrans/callback`)
   - Real-time update dari Midtrans server
   - Triggered saat ada perubahan status transaksi

## Database Schema

```sql
CREATE TABLE pemesanan (
    id_pemesanan BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_akun BIGINT,
    id_properti BIGINT,
    tanggal_pemesanan DATE,
    lama_sewa INT,
    total_harga DECIMAL(15,2),
    status_pemesanan ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    metode_pembayaran VARCHAR(50),
    status_pembayaran ENUM('belum_bayar', 'sudah_bayar') DEFAULT 'belum_bayar',
    snap_token VARCHAR(255),
    transaction_id VARCHAR(255),
    payment_type VARCHAR(100),
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Controller Methods

### PemesananController::syncPaymentStatus()

Method private untuk sinkronisasi status dengan Midtrans:

```php
private function syncPaymentStatus($pemesanan)
{
    // Get status dari Midtrans API
    $status = Transaction::status($pemesanan->transaction_id);
    
    // Update status berdasarkan response Midtrans
    // - capture/settlement -> confirmed
    // - pending -> pending
    // - deny/expire/cancel -> cancelled
}
```

### PemesananController::callback()

Webhook handler untuk notifikasi dari Midtrans:

```php
public function callback(Request $request)
{
    // Verify signature
    // Update status berdasarkan transaction_status
    // Update properti status jika confirmed
}
```

## Model Helper Methods

Model `Pemesanan` memiliki helper methods:

- `canBeCancelled()`: Cek apakah bisa dibatalkan
- `canBePaid()`: Cek apakah bisa dibayar
- `isConfirmed()`: Cek apakah sudah confirmed
- `isCancelled()`: Cek apakah sudah cancelled
- `getStatusBadgeColorAttribute()`: Get warna badge untuk UI
- `getStatusLabelAttribute()`: Get label status untuk display

## UI/UX Behavior

### Halaman Pemesanan (index)
- **Pending**: Tampilkan tombol "Bayar Sekarang" dan "Batalkan"
- **Confirmed**: Tampilkan tombol "Lihat Kontrak"
- **Cancelled**: Tampilkan status cancelled, tidak ada tombol aksi

### Halaman Payment
- **Pending**: Tampilkan tombol "Bayar Sekarang" + auto-refresh
- **Confirmed**: Redirect ke success page
- **Cancelled**: Redirect ke index dengan error message

### Halaman Riwayat Pembayaran
- **Confirmed**: Link "Lihat Kontrak"
- **Pending**: Link "Bayar Sekarang"
- **Cancelled**: Tampilkan "Dibatalkan" (no action)

## Migration Guide

Jika database belum memiliki kolom yang diperlukan:

```bash
# Jalankan migration
php artisan migrate

# Atau buat migration baru untuk menambah kolom
php artisan make:migration add_midtrans_fields_to_pemesanan_table
```

Tambahkan kolom berikut jika belum ada:
- `snap_token` (VARCHAR 255, nullable)
- `transaction_id` (VARCHAR 255, nullable)
- `payment_type` (VARCHAR 100, nullable)
- `paid_at` (TIMESTAMP, nullable)
- `total_harga` (DECIMAL 15,2)

## Testing

### Test Scenarios

1. **Create Order -> Pay -> Success**
   - Status: `pending` → `confirmed`
   - Payment: `belum_bayar` → `sudah_bayar`

2. **Create Order -> Cancel**
   - Status: `pending` → `cancelled`
   - Payment: `belum_bayar` (tetap)

3. **Create Order -> Pay -> Expired**
   - Status: `pending` → `cancelled` (by Midtrans)
   - Payment: `belum_bayar` (tetap)

4. **Webhook dari Midtrans**
   - Verify signature hash
   - Update status sesuai transaction_status

## Troubleshooting

### Status tidak tersinkronisasi
- Cek koneksi ke Midtrans API
- Cek `transaction_id` di database
- Lihat log error di `storage/logs/laravel.log`

### Webhook tidak jalan
- Pastikan URL webhook terdaftar di Midtrans Dashboard
- URL: `https://yourdomain.com/midtrans/callback`
- Pastikan route tidak memerlukan auth middleware

### Auto-refresh tidak berhenti
- Pastikan kondisi `@if` di JavaScript sesuai
- Cek apakah status sudah berubah di database

## Security Notes

1. **Signature Verification**: Semua callback dari Midtrans harus diverifikasi dengan signature hash
2. **Authorization**: Pastikan user hanya bisa akses pemesanan miliknya sendiri
3. **Server Key**: Jangan expose `server_key` di client-side

## Configuration

File: `config/midtrans.php`

```php
return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
```

File: `.env`

```env
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_IS_PRODUCTION=false
```
