<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('pemilik_reply')->nullable()->after('review');
            $table->timestamp('reply_date')->nullable()->after('pemilik_reply');
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['pemilik_reply', 'reply_date']);
        });
    }
};