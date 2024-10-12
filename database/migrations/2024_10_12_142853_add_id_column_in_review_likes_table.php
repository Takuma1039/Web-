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
        // まず、既存のプライマリーキーを削除
        Schema::table('review_likes', function (Blueprint $table) {
            
            // 既存のプライマリーキーを削除
            $table->dropPrimary(['user_id', 'review_id']);

            $table->id()->first(); // 既存のカラムの最初に追加
            // 新しい主キーを設定
            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_likes', function (Blueprint $table) {
            //
        });
    }
};
