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
            $table->text('alamat');
            $table->string('tipe');
            $table->decimal('harga', 15, 2); // Tipe decimal cocok untuk harga/uang
            $table->text('deskripsi');
            $table->enum('status', ['tersedia', 'disewa', 'tidak tersedia']);
            $table->unsignedBigInteger('id_akun')->nullable(); // Foreign key ke tabel akun
            $table->foreign('id_akun')->references('id')->on('akun')->onDelete('set null');
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properti');
    }
};