<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin mendapat semua permission
        $adminRole = Role::where('name', 'admin')->first();
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id')->toArray());
        
        // Pemilik properti mendapat permission terkait properti
        $pemilikRole = Role::where('name', 'pemilik')->first();
        $pemilikPermissions = Permission::whereIn('name', [
            'manage-properties',
            'view-bookings',
            'confirm-bookings'
        ])->get();
        $pemilikRole->permissions()->sync($pemilikPermissions->pluck('id')->toArray());
        
        // Penyewa mendapat permission untuk memesan dan review
        $penyewaRole = Role::where('name', 'penyewa')->first();
        $penyewaPermissions = Permission::whereIn('name', [
            'browse-properties',
            'book-property',
            'write-review'
        ])->get();
        $penyewaRole->permissions()->sync($penyewaPermissions->pluck('id')->toArray());
    }
}
