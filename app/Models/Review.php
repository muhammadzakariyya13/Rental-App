<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;
    protected $fillable = [
        'id_akun',
        'id_properti',
        'komentar',
        'rating',
        'tanggal',
    ];
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id');
    }

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }
}
