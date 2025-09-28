<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Akun extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'akun';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'phone_number',
        'email',
        'username',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke Role (setiap akun punya satu role)
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relasi ke Review (satu akun bisa punya banyak review)
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'id_akun');
    }

    /**
     * Relasi ke Pemesanan (satu akun bisa punya banyak pemesanan)
     */
    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class, 'id_akun');
    }

    /**
     * Cek apakah user punya permission tertentu (RBAC)
     */
    public function hasPermission(string $permission): bool
    {
        return $this->role?->hasPermission($permission) ?? false;
    }

    /**
     * Helper RBAC: cek role user
     */
    public function isAdmin(): bool
    {
        return $this->role?->nama === 'admin';
    }

    public function isOfficer(): bool
    {
        return $this->role?->nama === 'officer';
    }

    public function isCustomer(): bool
    {
        return $this->role?->nama === 'customer';
    }
}