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
        Schema::table('planposts', function (Blueprint $table) {
            
            $table->dropForeign(['plantype_id']);
            $table->dropColumn('plantype_id');
            $table->text('plan_category_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planposts', function (Blueprint $table) {
            //
        });
    }
};
