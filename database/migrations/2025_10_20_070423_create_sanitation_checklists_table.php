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
        Schema::create('sanitation_checklists', function (Blueprint $table) {
            $table->id('checklist_id');
            $table->string('checklist_name', 200);
            $table->string('area_type', 30);
            $table->json('checklist_items')->comment('Array of checklist items');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanitation_checklists');
    }
};
