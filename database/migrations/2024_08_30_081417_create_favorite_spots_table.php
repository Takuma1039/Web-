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
        Schema::create('favorite_spots', function (Blueprint $table) {
            //お気に入りしたユーザーのid
            $table->foreignId('user_id')->constrained('spots')->onDelete('cascade');
            //お気に入りされたスポットのid
            $table->foreignId('spot_id')->constrained('users')->onDelete('cascade');
            //主キーをuser_idとspot_idの組み合わせにする
            $table->primary(['user_id','spot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_spots');
    }
};
