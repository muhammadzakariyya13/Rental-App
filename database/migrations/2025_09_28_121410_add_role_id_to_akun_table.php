<?php

// database/migrations/xxxx_xx_xx_add_role_id_to_akun_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('akun', function (Blueprint $table) {
            // Karena sudah punya kolom 'phone_number', 'email', 'username', dan 'password'
            // Kita tambahkan 'role_id' yang menunjuk ke tabel 'role'
            $table->foreignId('role_id')
                  ->nullable()
                  ->constrained('role')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('akun', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};