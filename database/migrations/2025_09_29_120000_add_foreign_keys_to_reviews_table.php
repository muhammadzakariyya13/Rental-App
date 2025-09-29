<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Tambahkan index dan foreign key bila belum ada
            if (! $this->hasForeign('reviews', 'reviews_id_akun_foreign')) {
                $table->foreign('id_akun')->references('id')->on('akun')->onDelete('cascade');
            }
            if (! $this->hasForeign('reviews', 'reviews_id_properti_foreign')) {
                $table->foreign('id_properti')->references('id_properti')->on('properti')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if ($this->hasForeign('reviews', 'reviews_id_akun_foreign')) {
                $table->dropForeign('reviews_id_akun_foreign');
            }
            if ($this->hasForeign('reviews', 'reviews_id_properti_foreign')) {
                $table->dropForeign('reviews_id_properti_foreign');
            }
        });
    }

    private function hasForeign(string $table, string $index): bool
    {
        try {
            return Schema::getConnection()->getDoctrineSchemaManager()
                ->listTableDetails($table)
                ->hasForeignKey($index);
        } catch (\Throwable $e) {
            return false;
        }
    }
};
