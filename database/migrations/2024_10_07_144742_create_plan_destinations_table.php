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
        Schema::create('plan_destinations', function (Blueprint $table) {
            $table->id();
            $table->integer('order'); // 目的地の順番
            $table->timestamps(); // created_at と updated_at

            // 外部キー制約
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->foreignId('spot_id')->constrained('spots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_destinations');
    }
};
