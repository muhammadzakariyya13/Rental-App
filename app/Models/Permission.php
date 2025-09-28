<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $table = 'permission';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'nama_tampilan',
        'deskripsi',
        'kelompok',
    ];

    /**
     * Relasi ke Role (many-to-many)
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_permission',
            'permission_id',
            'role_id'
        );
    }
}