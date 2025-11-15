<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Properti extends Model
{
    use HasFactory;

    protected $table = 'properti';
    protected $primaryKey = 'id_properti';
    
    protected $fillable = [
        'nama',
        'alamat', 
        'tipe',
        'harga',
        'kamar_tidur',
        'kamar_mandi',
        'luas_tanah',
        'luas_bangunan',
        'deskripsi',
        'status',
        'pemilik_id',
        'gambar'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function pemilik()
    {
        return $this->belongsTo(Akun::class, 'pemilik_id'); // ← UBAH ke Akun::class
    }

    public function images()
    {
        return $this->hasMany(PropertiImage::class, 'id_properti', 'id_properti')->orderBy('order_index');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_properti', 'id_properti');
    }

    public function sewas()
    {
        return $this->hasMany(Sewa::class, 'id_properti', 'id_properti');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_properti', 'id_properti');
    }

    // Methods for reviews and ratings
    public function totalReviews()
    {
        return $this->reviews()->count();
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function ratingDistribution()
    {
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribution[$i] = $this->reviews()->where('rating', $i)->count();
        }
        return $distribution;
    }

    public function ratingPercentage($rating)
    {
        $total = $this->totalReviews();
        if ($total == 0) return 0;
        
        $count = $this->reviews()->where('rating', $rating)->count();
        return ($count / $total) * 100;
    }

    public function recentReviews($limit = 5)
    {
        return $this->reviews()
            ->with('penyewa')
            ->orderBy('tanggal_review', 'desc')
            ->limit($limit)
            ->get();
    }

    // Methods for stats
    public function totalPendapatan()
    {
        return $this->sewas()
            ->where('status', 'diterima') // ← UBAH dari 'aktif' ke 'diterima'
            ->sum('total_harga') ?? 0;
    }

    public function jumlahPenyewa()
    {
        return $this->sewas()
            ->distinct('id_penyewa')
            ->count() ?? 0;
    }

    // Image methods
    public function primaryImage()
    {
        return $this->images()->where('is_primary', true)->first();
    }

    public function getImageUrlAttribute()
    {
        $primary = $this->primaryImage();
        return $primary ? $primary->image_url : '/images/no-image.jpg';
    }
}