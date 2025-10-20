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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id('driver_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('license_number', 50)->unique();
            $table->string('license_type', 20)->comment('A, B, C, SIM category');
            $table->date('license_expiry');
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employees');

            $table->index('license_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
