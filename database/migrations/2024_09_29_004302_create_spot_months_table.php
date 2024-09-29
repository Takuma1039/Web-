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
        Schema::create('spot_months', function (Blueprint $table) {
            $table->foreignId('month_id')->constrained()->onDelete('cascade');
            $table->foreignId('spot_id')->constrained()->onDelete('cascade');
            //主キーをmonth_idとspot_idの組み合わせにする
            $table->primary(['spot_id','month_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spot_months');
    }
};
