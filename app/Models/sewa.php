<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sewa extends Model
{
    use HasFactory;

    protected $table = 'sewa';
    protected $primaryKey = 'id_sewa';

    protected $fillable = [
        'id_properti',
        'id_penyewa',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_harga',
        'status',
        'tanggal_diterima'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_diterima' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }

    public function penyewa()
    {
        return $this->belongsTo(Akun::class, 'id_penyewa'); // ← UBAH ke Akun::class
    }
}