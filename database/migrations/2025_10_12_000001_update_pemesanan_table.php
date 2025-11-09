<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn(['tanggal_pemesanan', 'lama_sewa', 'status_pemesanan', 'status_pembayaran']);
            
            // Rename id_akun to id_penyewa
            $table->renameColumn('id_akun', 'id_penyewa');
            
            // Add new columns
            $table->date('tanggal_mulai')->after('id_properti');
            $table->date('tanggal_selesai')->after('tanggal_mulai');
            $table->integer('durasi')->after('tanggal_selesai');
            $table->decimal('total_harga', 12, 2)->after('durasi');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending')->after('total_harga');
            $table->text('catatan')->nullable()->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            // Revert changes
            $table->renameColumn('id_penyewa', 'id_akun');
            $table->dropColumn(['tanggal_mulai', 'tanggal_selesai', 'durasi', 'total_harga', 'status', 'catatan']);
            
            // Re-add original columns
            $table->date('tanggal_pemesanan');
            $table->integer('lama_sewa');
            $table->enum('status_pemesanan', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->enum('status_pembayaran', ['belum_bayar', 'sudah_bayar'])->default('belum_bayar');
        });
    }
};
