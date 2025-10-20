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
        Schema::create('qc_parameters', function (Blueprint $table) {
            $table->id('parameter_id');
            $table->unsignedBigInteger('qc_id');
            $table->string('parameter_name', 150);
            $table->string('standard_value', 100)->nullable();
            $table->string('actual_value', 100)->nullable();
            $table->string('unit', 50)->nullable();
            $table->boolean('is_passed')->default(false);
            $table->timestamps();

            $table->foreign('qc_id')->references('qc_id')->on('quality_control')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qc_parameters');
    }
};
