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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // 旅行計画のタイトル
            $table->text('memo')->nullable(); // メモ用
            $table->date('start_date'); // 旅行日程の開始日
            $table->timestamps(); // created_at と updated_at
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ユーザーが削除された場合、関連する計画も削除される
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
