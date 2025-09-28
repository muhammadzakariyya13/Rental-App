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
        // Ambil role_id dari tabel role
        $adminRole = Role::where('nama', 'admin')->first();
        $officerRole = Role::where('nama', 'officer')->first();
        $customerRole = Role::where('nama', 'customer')->first();

        Akun::create([
            'phone_number' => '081234567890',
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'password' => Hash::make('password123'),
            'nama' => 'John Doe',
            'role_id' => $adminRole ? $adminRole->id : null,
        ]);
        
        Akun::create([
            'phone_number' => '082233445566',
            'email' => 'officer@example.com',
            'username' => 'officeruser',
            'password' => Hash::make('password123'),
            'nama' => 'Officer User',
            'role_id' => $officerRole ? $officerRole->id : null,
        ]);

        Akun::create([
            'phone_number' => '089876543210',
            'email' => 'jane@example.com',
            'username' => 'janesmith',
            'password' => Hash::make('password123'),
            'nama' => 'Jane Smith',
            'role_id' => $customerRole ? $customerRole->id : null,
        ]);
    }
}