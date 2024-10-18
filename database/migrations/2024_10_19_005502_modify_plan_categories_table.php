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
        Schema::table('plan_categories', function (Blueprint $table) {
            
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');

            $table->foreignId('planpost_id')->constrained('planposts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_categories', function (Blueprint $table) {
            //
        });
    }
};