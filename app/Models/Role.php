<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'nama_tampilan',
        'deskripsi',
        'aktif',
    ];

    /**
     * Relasi ke Akun (satu role bisa dimiliki banyak akun)
     */
    public function akun(): HasMany
    {
        return $this->hasMany(Akun::class, 'role_id');
    }

    /**
     * Relasi ke Permission (many-to-many)
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permission',
            'role_id',
            'permission_id'
        );
    }

    /**
     * Cek apakah role punya permission tertentu
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('nama', $permission)->exists();
    }
}