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
        Schema::create('spot_seasons', function (Blueprint $table) {
            $table->foreignId('season_id')->constrained()->onDelete('cascade');
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            //主キーをseason_idとspot_idの組み合わせにする
            $table->primary(['spot_id','season_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spot_seasons');
    }
};
