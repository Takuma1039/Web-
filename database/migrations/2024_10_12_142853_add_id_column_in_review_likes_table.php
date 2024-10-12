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
            // idカラムが存在しない場合は追加
            if (!Schema::hasColumn('review_likes', 'id')) {
                $table->bigIncrements('id')->first(); // auto-incrementing primary key
            }

            // 既存のプライマリーキーを削除
            $table->dropPrimary(['user_id', 'review_id']);
            // 外部キーを削除
            $table->dropForeign(['user_id']);
            $table->dropForeign(['review_id']);

            // 再度外部キー制約を追加
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->change();
            $table->foreignId('review_id')->constrained()->onDelete('cascade')->change();
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
