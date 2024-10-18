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
        Schema::create('planpost_plantype', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planpost_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('plantype_id')->constrained()->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planpost_plantype');
    }
};
