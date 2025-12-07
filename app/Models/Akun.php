<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Akun extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'akun';

    protected $fillable = [
        'phone_number',
        'email',
        'username', 
        'password',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Accessor untuk name (gunakan username sebagai name)
    public function getNameAttribute()
    {
        return $this->username;
    }

    // Relasi dengan roles
    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    // Relasi dengan properti
    public function properti()
    {
        return $this->hasMany(\App\Models\Properti::class, 'pemilik_id', 'id');
    }
}