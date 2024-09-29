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
        Schema::table('spot_images', function (Blueprint $table) {
            $table->string('public_id')->after('image_path')->nullable(); // public_idを追加
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spot_images', function (Blueprint $table) {
            $table->dropColumn('public_id'); // public_idを削除
        });
    }
};
