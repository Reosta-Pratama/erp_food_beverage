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
        Schema::create('technicians', function (Blueprint $table) {
            $table->id('technician_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('specialization', 200)->nullable();
            $table->string('certification_level', 30)->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technicians');
    }
};
