<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('nama_tampilan');
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif')->default(true); 
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('role');
    }
};