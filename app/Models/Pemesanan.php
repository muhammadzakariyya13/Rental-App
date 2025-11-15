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
        'status_pemesanan',
        'metode_pembayaran',
        'status_pembayaran',
    ];

    // Relasi ke Akun
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
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
}
