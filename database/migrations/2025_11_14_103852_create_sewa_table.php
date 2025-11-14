<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop jika ada, lalu create ulang
        Schema::dropIfExists('sewa');
        
        Schema::create('sewa', function (Blueprint $table) {
            $table->id('id_sewa');
            $table->unsignedBigInteger('id_properti');
            $table->unsignedBigInteger('id_penyewa');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->decimal('total_harga', 15, 2);
            $table->enum('status', ['pending', 'diterima', 'ditolak', 'dibatalkan', 'selesai'])->default('pending');
            $table->timestamp('tanggal_diterima')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_properti')->references('id_properti')->on('properti')->onDelete('cascade');
            $table->foreign('id_penyewa')->references('id')->on('akun')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sewa');
    }
};