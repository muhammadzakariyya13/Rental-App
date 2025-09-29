<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Properti extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * Perlu didefinisikan karena 'properti' bukan bentuk jamak dari 'Properti'.
     *
     * @var string
     */
    protected $table = 'properti';

    /**
     * Primary key untuk model ini.
     * Perlu didefinisikan karena primary key-nya adalah 'id_properti', bukan 'id'.
     *
     * @var string
     */
    protected $primaryKey = 'id_properti';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'harga',
        'deskripsi',
        'foto',
        'status',
    ];

    /**
     * Tipe data asli dari atribut yang perlu di-casting.
     * Berguna agar 'harga' selalu diperlakukan sebagai angka desimal.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga' => 'decimal:2',
    ];

    // Relasi: Properti memiliki banyak Pemesanan
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_properti', 'id_properti');
    }

    // Relasi: Properti memiliki banyak Review
    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_properti', 'id_properti');
    }
}