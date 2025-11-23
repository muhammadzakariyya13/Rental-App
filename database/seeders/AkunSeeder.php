<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Akun;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil role_id dari tabel role (hanya admin dan user)
        $adminRole = Role::where('nama', 'admin')->first();
        $userRole = Role::where('nama', 'user')->first();

        // Create demo accounts dengan email yang mudah diingat (skip jika sudah ada)
        if (!Akun::where('email', 'admin@rental.com')->exists()) {
            Akun::create([
                'phone_number' => '081234567890',
                'email' => 'admin@rental.com',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'nama' => 'Administrator',
                'role_id' => $adminRole ? $adminRole->id : null,
            ]);
        }

        if (!Akun::where('email', 'user@rental.com')->exists()) {
            Akun::create([
                'phone_number' => '089876543210', 
                'email' => 'user@rental.com',
                'username' => 'user',
                'password' => Hash::make('password'),
                'nama' => 'User Demo',
                'role_id' => $userRole ? $userRole->id : null,
            ]);
        }
    }
}