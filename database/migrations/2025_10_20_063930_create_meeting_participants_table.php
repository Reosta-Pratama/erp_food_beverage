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
        Schema::create('meeting_participants', function (Blueprint $table) {
            $table->id('participant_id');
            $table->unsignedBigInteger('meeting_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('attendance_status', 30);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('meeting_id')->references('meeting_id')->on('meetings')->cascadeOnDelete();
            $table->foreign('employee_id')->references('employee_id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_participants');
    }
};
