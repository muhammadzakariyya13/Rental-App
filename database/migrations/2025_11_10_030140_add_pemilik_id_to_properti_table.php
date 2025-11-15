<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('properti', function (Blueprint $table) {
            // Tambahkan setelah id_properti
            $table->unsignedBigInteger('pemilik_id')->after('id_properti');
            
            // Tambahkan kolom yang belum ada
            $table->text('alamat')->after('nama');
            $table->enum('tipe', ['rumah', 'apartemen', 'kontrakan', 'vila'])->nullable()->after('harga');
            $table->integer('kamar_tidur')->nullable()->after('tipe');
            $table->integer('kamar_mandi')->nullable()->after('kamar_tidur');
            $table->integer('luas_tanah')->nullable()->after('kamar_mandi');
            $table->integer('luas_bangunan')->nullable()->after('luas_tanah');
            
            // Foreign key ke tabel akun
            $table->foreign('pemilik_id')->references('id')->on('akun');
        });
    }

    public function down()
    {
        Schema::table('properti', function (Blueprint $table) {
            $table->dropForeign(['pemilik_id']);
            $table->dropColumn([
                'pemilik_id', 
                'alamat', 
                'tipe', 
                'kamar_tidur', 
                'kamar_mandi', 
                'luas_tanah', 
                'luas_bangunan'
            ]);
        });
    }
};