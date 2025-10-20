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
        Schema::create('workflow', function (Blueprint $table) {
            $table->id('workflow_id');
            $table->string('workflow_name', 200)->unique();
            $table->string('module_name', 100);
            $table->string('process_name', 100);
            $table->json('approval_hierarchy')->nullable()->comment('JSON structure of approval levels');
            $table->boolean('require_approval')->default(false);
            $table->integer('approval_levels')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_configurations');
    }
};
