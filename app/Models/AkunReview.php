<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunReview extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'akunreview';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_akun',
        'id_properti',
        'review_text',
        'rating'
    ];

    /**
     * Get the user that owns the review.
     */
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id');
    }

    /**
     * Get the property that the review is for.
     */
    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }
}