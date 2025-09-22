<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Akunreview extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'akunreview';

    /**
     * Primary key untuk model ini.
     *
     * @var string
     */
    protected $primaryKey = 'id_review';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_properti',
        'id_akun',
        'rating',
        'komentar',
        'tanggal',
    ];

    /**
     * Tipe data asli dari atribut yang perlu di-casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'float',
        'tanggal' => 'datetime',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Properti.
     * Setiap review dimiliki oleh satu properti.
     */
    public function properti(): BelongsTo
    {
        return $this->belongsTo(Properti::class, 'id_properti');
    }

    /**
     * Mendefinisikan relasi "belongsTo" ke model Akun.
     * Setiap review dimiliki oleh satu akun.
     */
    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }
}