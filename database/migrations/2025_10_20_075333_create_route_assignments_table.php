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
        Schema::create('route_assignments', function (Blueprint $table) {
            $table->id('assignment_id');
            $table->unsignedBigInteger('delivery_id');
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('driver_id');
            $table->date('assignment_date');
            $table->timestamps();

            $table->foreign('delivery_id')->references('delivery_id')->on('deliveries');
            $table->foreign('route_id')->references('route_id')->on('delivery_routes');
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles');
            $table->foreign('driver_id')->references('driver_id')->on('drivers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_assignments');
    }
};
