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
        Schema::create('maintenance_history', function (Blueprint $table) {
            $table->id('history_id');
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('schedule_id')->nullable()->comment('NULL karena bisa unscheduled maintenance');
            $table->date('maintenance_date');
            $table->string('maintenance_type', 30);
            $table->unsignedBigInteger('technician_id');
            $table->decimal('duration_hours', 6, 2)->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->text('work_performed');
            $table->text('parts_replaced')->nullable();
            $table->string('status', 30);
            $table->timestamps();

            $table->foreign('machine_id')->references('machine_id')->on('machines');
            $table->foreign('schedule_id')->references('schedule_id')->on('maintenance_schedules')->nullOnDelete();
            $table->foreign('technician_id')->references('technician_id')->on('technicians');
            
            $table->index('maintenance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_history');
    }
};
