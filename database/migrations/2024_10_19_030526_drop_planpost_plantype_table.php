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
        Schema::dropIfExists('planpost_plantype'); // テーブルを削除
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planpost_plantype', function (Blueprint $table) {
            //
        });
    }
};
