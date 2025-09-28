<?php
namespace Database\Seeders;

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
        // Ambil semua role
        $admin = Role::where('nama', 'admin')->first();
        $officer = Role::where('nama', 'officer')->first();
        $customer = Role::where('nama', 'customer')->first();

        // Ambil semua permission
        $allPermissions = Permission::pluck('id')->toArray();

        // Contoh: admin dapat semua permission
        if ($admin) {
            $admin->permissions()->sync($allPermissions);
        }

        // Contoh: officer hanya dapat permission user dan properti
        if ($officer) {
            $officerPermissions = Permission::whereIn('nama', [
                'view_user', 'create_user', 'edit_user', 'view_property', 'create_property'
            ])->pluck('id')->toArray();
            $officer->permissions()->sync($officerPermissions);
        }

        // Contoh: customer hanya dapat view_property
        if ($customer) {
            $customerPermissions = Permission::whereIn('nama', [
                'view_property'
            ])->pluck('id')->toArray();
            $customer->permissions()->sync($customerPermissions);
        }
    }
}