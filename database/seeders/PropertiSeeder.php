<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PropertiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inisialisasi Faker untuk data berbahasa Indonesia
        $faker = Faker::create('id_ID');

        // Ambil ID akun pemilik properti
        $pemilikIds = DB::table('akun')
            ->join('role_user', 'akun.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', 'pemilik')
            ->pluck('akun.id')
            ->toArray();
            
        // Jika tidak ada pemilik, buat satu pemilik baru
        if (empty($pemilikIds)) {
            $pemilikId = DB::table('akun')->insertGetId([
                'username' => 'pemilik',
                'email' => 'pemilik@rental.com',
                'password' => bcrypt('password'),
                'phone_number' => '081234567891',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Dapatkan role pemilik
            $pemilikRoleId = DB::table('roles')->where('name', 'pemilik')->first()->id ?? null;
            
            // Jika role pemilik tidak ada, buat dulu
            if (!$pemilikRoleId) {
                $pemilikRoleId = DB::table('roles')->insertGetId([
                    'name' => 'pemilik',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Hubungkan akun dengan role
            DB::table('role_user')->insert([
                'role_id' => $pemilikRoleId,
                'user_id' => $pemilikId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $pemilikIds = [$pemilikId];
        }

        // Data properti yang lebih realistis
        $propertiData = [
            // Properti Rumah
            [
                'nama' => 'Rumah Mewah Permata Buana',
                'alamat' => 'Jl. Permata Buana Blok C5 No. 12, Jakarta Barat',
                'tipe' => 'Rumah',
                'harga' => 8500000000,
                'deskripsi' => 'Rumah mewah 2 lantai dengan 5 kamar tidur, 4 kamar mandi, kolam renang pribadi, dan taman luas. Bangunan baru dengan arsitektur modern minimalis, lokasi strategis dekat dengan sekolah internasional dan pusat perbelanjaan.',
                'status' => 'tersedia',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Rumah Asri Bintaro',
                'alamat' => 'Jl. Graha Raya Blok F2 No. 8, Bintaro, Tangerang Selatan',
                'tipe' => 'Rumah',
                'harga' => 3200000000,
                'deskripsi' => 'Rumah nyaman di lingkungan yang asri dan aman. Memiliki 4 kamar tidur, 3 kamar mandi, ruang keluarga luas, dan carport untuk 2 mobil. Dekat dengan stasiun KRL Jurang Mangu dan akses tol.',
                'status' => 'tersedia',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Rumah Minimalis BSD City',
                'alamat' => 'Cluster Natura Blok NA5 No. 17, BSD City, Tangerang',
                'tipe' => 'Rumah',
                'harga' => 2800000000,
                'deskripsi' => 'Rumah minimalis modern dengan 3 kamar tidur, 2 kamar mandi, ruang tamu dan keluarga yang nyaman. Berada di kawasan elit BSD City dengan fasilitas keamanan 24 jam dan taman bermain anak.',
                'status' => 'tersedia',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Properti Apartemen
            [
                'nama' => 'Apartemen Mewah Sudirman Suites',
                'alamat' => 'Jl. Jendral Sudirman Kav. 45, Jakarta Pusat',
                'tipe' => 'Apartemen',
                'harga' => 5500000000,
                'deskripsi' => 'Apartemen mewah dengan pemandangan kota Jakarta. Unit 3BR (135m²) dengan interior premium, fully furnished. Fasilitas kolam renang, gym, sauna, dan sky lounge. Lokasi strategis di pusat bisnis Jakarta.',
                'status' => 'tersedia',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Apartemen Kemang Village',
                'alamat' => 'Jl. Pangeran Antasari No. 36, Kemang, Jakarta Selatan',
                'tipe' => 'Apartemen',
                'harga' => 3800000000,
                'deskripsi' => 'Apartemen eksklusif di kawasan elit Kemang. Unit 2BR (90m²) dengan konsep semi furnished. Memiliki akses langsung ke mall, kolam renang, gym, dan children playground. Cocok untuk keluarga muda.',
                'status' => 'tersedia',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Studio Apartemen The Nest',
                'alamat' => 'Jl. TB Simatupang No. 22, Jakarta Selatan',
                'tipe' => 'Apartemen',
                'harga' => 950000000,
                'deskripsi' => 'Studio apartemen modern dengan konsep ruang efisien. Luas 32m², fully furnished dengan interior minimalis. Fasilitas kolam renang, co-working space, dan laundry. Cocok untuk eksekutif muda dan investasi.',
                'status' => 'tersedia',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Properti Ruko
            [
                'nama' => 'Ruko Boulevard Business Park',
                'alamat' => 'Jl. Boulevard Raya Blok A2 No. 15, Kelapa Gading, Jakarta Utara',
                'tipe' => 'Ruko',
                'harga' => 4500000000,
                'deskripsi' => 'Ruko 3 lantai di kawasan bisnis Kelapa Gading. Luas tanah 5x15m dengan luas bangunan 225m². Cocok untuk kantor, toko, atau usaha kuliner. Akses mudah dan ramai pengunjung sepanjang hari.',
                'status' => 'tersedia',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ruko Modern Gading Serpong',
                'alamat' => 'Jl. Boulevard Raya Gading Serpong Blok M5 No. 8, Tangerang',
                'tipe' => 'Ruko',
                'harga' => 3200000000,
                'deskripsi' => 'Ruko modern 2 lantai dengan desain kontemporer. Luas tanah 4x12m dengan luas bangunan 96m². Lokasi strategis di pusat bisnis Gading Serpong, dekat dengan universitas dan apartemen.',
                'status' => 'disewa',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Properti Villa
            [
                'nama' => 'Villa Mewah Puncak',
                'alamat' => 'Jl. Raya Puncak Km. 85, Cisarua, Bogor',
                'tipe' => 'Villa',
                'harga' => 6800000000,
                'deskripsi' => 'Villa mewah dengan pemandangan gunung yang spektakuler. Memiliki 6 kamar tidur, 5 kamar mandi, kolam renang, taman luas, dan area BBQ. Desain semi modern dengan sentuhan kayu alami. Cocok untuk retreat keluarga.',
                'status' => 'tersedia',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Villa Asri Batu',
                'alamat' => 'Jl. Villa Bukit Panderman No. 15, Batu, Malang',
                'tipe' => 'Villa',
                'harga' => 2500000000,
                'deskripsi' => 'Villa nyaman dengan panorama Kota Batu yang indah. Memiliki 4 kamar tidur, 3 kamar mandi, ruang santai outdoor, dan taman dengan gazebo. Suasana sejuk dengan akses ke berbagai tempat wisata.',
                'status' => 'tersedia',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Properti Kost
            [
                'nama' => 'Kost Exclusive Pondok Indah',
                'alamat' => 'Jl. Pondok Indah V No. 12, Jakarta Selatan',
                'tipe' => 'Kost',
                'harga' => 1500000000,
                'deskripsi' => 'Kost exclusive dengan 15 kamar premium. Setiap kamar dilengkapi dengan AC, water heater, dan kamar mandi dalam. Fasilitas bersama meliputi dapur, ruang santai, laundry, dan taman. ROI tinggi dengan okupansi rata-rata 95%.',
                'status' => 'tersedia',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kost Modern Pogung',
                'alamat' => 'Jl. Pogung Baru No. 23, Sinduadi, Yogyakarta',
                'tipe' => 'Kost',
                'harga' => 850000000,
                'deskripsi' => 'Kost modern dengan 10 kamar yang nyaman dan bersih. Setiap kamar dilengkapi dengan AC dan tempat tidur. Fasilitas bersama meliputi dapur, ruang tamu, dan area parkir. Lokasi strategis dekat kampus UGM.',
                'status' => 'tersedia',
                'id_akun' => $faker->randomElement($pemilikIds),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Tambahkan properti data ke database
        foreach ($propertiData as $properti) {
            DB::table('properti')->insert($properti);
        }
        
        // Tambahkan beberapa properti random untuk variasi
        for ($i = 1; $i <= 10; $i++) {
            DB::table('properti')->insert([
                'nama' => 'Properti ' . $faker->company . ' ' . $faker->city,
                'alamat' => $faker->address,
                'tipe' => $faker->randomElement(['Rumah', 'Apartemen', 'Ruko', 'Villa', 'Kost']),
                'harga' => $faker->numberBetween(500000000, 5000000000),
                'deskripsi' => $faker->realText(200),
                'status' => $faker->randomElement(['tersedia', 'disewa', 'tidak tersedia']),
                'id_akun' => $faker->randomElement($pemilikIds), // Assign ke salah satu pemilik
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}