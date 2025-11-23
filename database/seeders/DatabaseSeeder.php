<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // RBAC seeders (harus duluan karena jadi foreign key di akun)
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AkunSeeder::class,

            // Data utama aplikasi
            PropertiSeeder::class,
            PemesananSeeder::class,
            KontrakSeeder::class,

            // Reviews (tergantung akun & properti)
            ReviewSeeder::class,
        ]);
    }
}