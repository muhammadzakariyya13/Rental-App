<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
    use HasFactory;

    protected $table = 'kontraks';
    protected $primaryKey = 'id_kontrak';

    protected $fillable = [
        'id_properti',
        'id_pemesanan',
        'tgl_mulai_sewa',
        'tgl_akhir_sewa',
        'harga_sewa',
    ];

    public $timestamps = true;

    
    // Relasi dengan model Properti. Setiap kontrak dimiliki oleh satu properti.
    
    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }
    
    //Relasi dengan model Pemesanan. Setiap kontrak dibuat dari satu pemesanan.
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}