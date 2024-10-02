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
        Schema::table('review_images', function (Blueprint $table) {
            $table->string('name')->nullable(); // 名前カラムを追加
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_images', function (Blueprint $table) {
            $table->dropColumn('name'); // 名前カラムを削除
        });
    }
};
