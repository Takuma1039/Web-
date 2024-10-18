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
        Schema::rename('plan_categories', 'planpost_plantype');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('planpost_plantype', 'plan_categories');
    }
};
