<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('nama_tampilan');
            $table->string('kelompok')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('permission');
    }
};