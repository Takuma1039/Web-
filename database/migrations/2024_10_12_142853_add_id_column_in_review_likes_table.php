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
        Schema::table('review_likes', function (Blueprint $table) {
            // 既存の主キーを削除
            $table->dropPrimary(['user_id', 'review_id']);
            
            // 新しい主キーとしてのidカラムを追加
            $table->id()->first(); // 先頭に追加する場合

            // 新しい主キーを設定
            $table->primary('id');
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
