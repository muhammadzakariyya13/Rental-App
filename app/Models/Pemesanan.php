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
        'id_penyewa',
        'id_properti',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi',
        'total_harga',
        'status',
        'metode_pembayaran',
        'catatan',
        'payment_transaction_id',
        'payment_url',
        'payment_status',
    ];

    // Relasi ke Akun (Penyewa)
    public function penyewa()
    {
        return $this->belongsTo(Akun::class, 'id_penyewa', 'id');
    }

    // Relasi ke Properti
    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }
}
