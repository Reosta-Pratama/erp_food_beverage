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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id('attendance_id');
            $table->unsignedBigInteger('employee_id');
            $table->date('attendance_date');
            $table->time('check_in_time')->nullable()->comment('Bisa absen tanpa check-in');
            $table->time('check_out_time')->nullable();
            $table->string('status', 30);
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->decimal('overtime_hours', 4, 2)->nullable()->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('shift_id')->references('shift_id')->on('shifts')->nullOnDelete();

            $table->index(['attendance_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
