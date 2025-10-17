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
        Schema::create('shift_assignments', function (Blueprint $table) {
            $table->id('assignment_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('shift_id');
            $table->date('effective_date');
            $table->date('end_date')->nullable()->comment('NULL jika masih berlaku');
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('shift_id')->references('shift_id')->on('shifts');

            $table->index(['employee_id', 'effective_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_assignments');
    }
};
