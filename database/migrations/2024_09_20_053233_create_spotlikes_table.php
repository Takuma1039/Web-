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
        Schema::create('spotlikes', function (Blueprint $table) {
            //いいねしたユーザーのid
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            //いいねされたspotのid
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            //主キーをuser_idとspot_idの組み合わせにする
            $table->primary(['user_id','spot_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spotlikes');
    }
};
