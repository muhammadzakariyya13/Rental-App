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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->unsignedBigInteger('id_akun');
            $table->unsignedBigInteger('id_properti');
            $table->date('tanggal_pemesanan');
            $table->integer('lama_sewa');
            $table->enum('status_pemesanan', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->string('metode_pembayaran', 50);
            $table->enum('status_pembayaran', ['belum_bayar', 'sudah_bayar'])->default('belum_bayar');
            $table->timestamps();

            // Add foreign key to properti
            $table->foreign('id_properti')->references('id_properti')->on('properti')->onDelete('cascade');
            
            // If you have a users table with id column (default Laravel)
            $table->foreign('id_akun')->references('id')->on('users')->onDelete('cascade');
            
            // Or if you have an account table with id_akun column, use this instead:
            // $table->foreign('id_akun')->references('id_akun')->on('account')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};