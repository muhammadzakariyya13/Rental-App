<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'id_akun',
        'id_properti',
        'tanggal_pemesanan',
        'lama_sewa',
        'total_harga',
        'biaya_admin',
        'status_pemesanan',
        'metode_pembayaran',
        'status_pembayaran',
        'snap_token',
        'transaction_id',
        'payment_type',
        'paid_at',
        'refund_date',
        'download_izin',
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'date',
        'paid_at' => 'datetime',
        'refund_date' => 'datetime',
    ];

    // Relasi ke Akun
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id');
    }

    // Alias untuk akun
    public function penyewa()
    {
        return $this->akun();
    }

    // Relasi ke Properti
    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }

    // Relasi ke Kontrak
    public function kontrak()
    {
        return $this->hasOne(Kontrak::class, 'id_pemesanan', 'id_pemesanan');
    }

    // Relasi ke Review
    public function akunReview()
    {
        return $this->hasMany(Review::class, 'id_pemesanan', 'id_pemesanan');
    }

    // Format payment type untuk display
    public function getFormattedPaymentTypeAttribute()
    {
        if (!$this->payment_type) {
            return 'Transfer Bank';
        }

        $paymentTypes = [
            'bank_transfer' => 'Transfer Bank',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
            'cimb_clicks' => 'CIMB Clicks',
            'bca_klikpay' => 'BCA KlikPay',
            'bca_klikbca' => 'BCA KlikBCA',
            'mandiri_clickpay' => 'Mandiri ClickPay',
            'bri_epay' => 'BRI e-Pay',
            'echannel' => 'Mandiri Bill Payment',
            'permata_va' => 'Permata Virtual Account',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'other_va' => 'Virtual Account',
            'indomaret' => 'Indomaret',
            'alfamart' => 'Alfamart',
            'akulaku' => 'Akulaku',
        ];

        return $paymentTypes[$this->payment_type] ?? ucwords(str_replace('_', ' ', $this->payment_type));
    }

    // Get status badge color
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status_pemesanan) {
            'confirmed' => 'green',
            'pending' => 'yellow',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        return match($this->status_pemesanan) {
            'confirmed' => 'Confirmed',
            'pending' => 'Pending',
            'cancelled' => 'Cancelled',
            default => 'Unknown',
        };
    }

    // Check if can be cancelled
    public function canBeCancelled()
    {
        return $this->status_pemesanan === 'pending' && $this->status_pembayaran === 'belum_bayar';
    }

    // Check if can be paid
    public function canBePaid()
    {
        return $this->status_pemesanan === 'pending' && $this->status_pembayaran === 'belum_bayar';
    }

    // Check if is confirmed
    public function isConfirmed()
    {
        return $this->status_pemesanan === 'confirmed' && $this->status_pembayaran === 'sudah_bayar';
    }

    // Check if is cancelled
    public function isCancelled()
    {
        return $this->status_pemesanan === 'cancelled';
    }
}
