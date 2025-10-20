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
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->unsignedBigInteger('machine_id');
            $table->string('maintenance_type', 30);
            $table->string('frequency', 30);
            $table->integer('interval_days');
            $table->date('last_maintenance')->nullable()->comment('NULL untuk schedule baru belum pernah maintenance');
            $table->date('next_maintenance');
            $table->unsignedBigInteger('assigned_technician_id')->nullable();
            $table->string('status', 30);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->foreign('machine_id')->references('machine_id')->on('machines');
            $table->foreign('assigned_technician_id')->references('technician_id')->on('technicians')->nullOnDelete();
            
            $table->index('next_maintenance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
