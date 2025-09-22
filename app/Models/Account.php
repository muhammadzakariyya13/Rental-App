<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_akun';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // Add your account table fields here
        // For example: 'name', 'email', 'password', etc.
    ];

    /**
     * Get the pemesanan records associated with the account.
     */
    public function pemesanan()
    {
        return $this->belongsToMany(Pemesanan::class, 'account_pemesanan', 'id_akun', 'id_pemesanan')
                    ->withTimestamps();
    }
}