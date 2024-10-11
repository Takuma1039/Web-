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
        Schema::create('plan_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->foreignId('planpost_id')->constrained('planposts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_images');
    }
};
