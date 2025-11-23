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
    $user = Role::where('nama', 'user')->first();

        // Ambil semua permission
        $allPermissions = Permission::pluck('id')->toArray();

        // Contoh: admin dapat semua permission
        if ($admin) {
            $admin->permissions()->sync($allPermissions);
        }

        // User hanya dapat view_property (dan permission lain yang kamu anggap wajar untuk user)
        if ($user) {
            $userPermissions = Permission::whereIn('nama', [
                'view_property'
            ])->pluck('id')->toArray();
            $user->permissions()->sync($userPermissions);
        }
    }
}