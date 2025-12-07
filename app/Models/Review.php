<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_review';
    
    protected $fillable = [
        'id_properti',
        'id_penyewa', 
        'id_pemesanan',
        'rating',
        'review',
        'is_approved',
        'tanggal_review',
        'pemilik_reply',
        'reply_date'
    ];

    protected $casts = [
        'tanggal_review' => 'datetime',
        'reply_date' => 'datetime',
        'is_approved' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========== RELATIONS - SESUAI DB ==========
    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }

    public function penyewa()
    {
        return $this->belongsTo(Akun::class, 'id_penyewa', 'id');
    }

    // ========== ACCESSORS ==========
    public function getRatingStarsAttribute()
    {
        return str_repeat('⭐', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getHasReplyAttribute()
    {
        return !empty($this->pemilik_reply);
    }

    public function getTimeAgoAttribute()
    {
        return $this->tanggal_review->diffForHumans();
    }

    public function getIsApprovedBadgeAttribute()
    {
        return $this->is_approved ? '✅ Approved' : '❌ Not Approved';
    }

    // ========== SCOPES ==========
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeNotApproved($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeNeedReply($query)
    {
        return $query->whereNull('pemilik_reply');
    }

    public function scopeReplied($query)
    {
        return $query->whereNotNull('pemilik_reply');
    }
}