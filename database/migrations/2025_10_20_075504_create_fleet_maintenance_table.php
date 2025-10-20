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
        Schema::create('fleet_maintenance', function (Blueprint $table) {
            $table->id('fleet_maintenance_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->date('maintenance_date');
            $table->string('maintenance_type', 30);
            $table->decimal('cost', 15, 2)->nullable();
            $table->integer('odometer_reading')->nullable()->comment('km');
            $table->unsignedBigInteger('technician_id')->nullable()->comment('NULL bila maintenance eksternal');
            $table->text('work_performed');
            $table->date('next_service_date')->nullable();
            $table->timestamps();

            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles');
            $table->foreign('technician_id')->references('technician_id')->on('technicians')->nullOnDelete();
            
            $table->index('maintenance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleet_maintenance');
    }
};
