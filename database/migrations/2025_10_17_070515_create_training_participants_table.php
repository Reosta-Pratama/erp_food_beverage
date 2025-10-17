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
        Schema::create('training_participants', function (Blueprint $table) {
            $table->id('participant_id');
            $table->unsignedBigInteger('training_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('status', 30);
            $table->decimal('score', 5, 2)->nullable()->comment('NULL sampai selesai training');
            $table->boolean('is_certified')->default(false);
            $table->date('certificate_date')->nullable();
            $table->string('certificate_number', 100)->nullable();
            $table->timestamps();

            $table->foreign('training_id')->references('training_id')->on('training_programs');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_participants');
    }
};
