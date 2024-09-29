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
        Schema::table('spots', function (Blueprint $table) {
            $table->text('category_ids')->nullable(); // カテゴリーを保存するカラム
            $table->text('season_ids')->nullable();   // 季節を保存するカラム
            $table->text('month_ids')->nullable();    // 月を保存するカラム
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->dropColumn('category_ids');
            $table->dropColumn('season_ids');
            $table->dropColumn('month_ids');
        });
    }
};
