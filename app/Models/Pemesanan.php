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
        'tanggal_mulai',
        'tanggal_selesai',
        'total_harga',
        'lama_sewa',
        'status_pemesanan',
        'status',
        'metode_pembayaran',
        'status_pembayaran',
        'catatan',
    ];

    // Relasi ke Akun
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id');
    }

    // Relasi ke Properti
    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }
}
