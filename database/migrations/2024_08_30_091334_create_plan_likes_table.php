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
        Schema::create('plan_likes', function (Blueprint $table) {
            //いいねしたユーザーのid
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            //いいねされた旅行計画のid
            $table->foreignId('planpost_id')->constrained()->onDelete('cascade');
            //主キーをuser_idとplanspot_idの組み合わせにする
            $table->primary(['user_id','planpost_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_likes');
    }
};
