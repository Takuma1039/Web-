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
        Schema::dropIfExists('majorspots');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->id();
        $table->foreignId('spot_id')->constrained()->onDelete('cascade'); // 既存のカラムを再作成
        $table->timestamps();
    }
};
