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
            // もしプライマリーキーを名前付きで削除する必要がある場合、テーブルの情報を確認してください
            // $table->dropPrimary(['user_id', 'review_id']);
            // ただし、通常は以下のように制約を削除します
            $table->dropForeign(['user_id']);
            $table->dropForeign(['review_id']);
            
            $table->dropPrimary(['user_id', 'review_id']);
        });

        // 次に、idカラムを追加し、それをプライマリーキーとして設定
        Schema::table('review_likes', function (Blueprint $table) {
            // idカラムを追加
            $table->bigIncrements('id')->first(); // ここで自動インクリメントプライマリーキーを設定
            // 外部キー制約を再追加
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
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
