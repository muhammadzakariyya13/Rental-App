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
        Schema::create('properti', function (Blueprint $table) {
            $table->id('id_properti'); // Primary key sesuai ERD
            $table->string('nama');
            $table->decimal('harga', 15, 2); // Tipe decimal cocok untuk harga/uang
            $table->text('deskripsi');
            $table->enum('status', ['tersedia', 'disewa']);
            $table->longText('gambar'); // Kolom untuk menyimpan file gambar (binary data)
            $table->timestamps(); // Kolom created_at dan updated_at
        }); // <-- SEHARUSNYA SEPERTI INI
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properti');
    }
};