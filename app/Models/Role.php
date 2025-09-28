<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'display_name', 'description'];

    /**
     * Role memiliki banyak Permission.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    /**
     * Role dimiliki oleh banyak User.
     */
    public function users()
    {
        return $this->belongsToMany(Akun::class, 'role_user', 'role_id', 'user_id');
    }
}
