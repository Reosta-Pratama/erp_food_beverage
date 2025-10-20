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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id('vehicle_id');
            $table->char('vehicle_code', 10)->unique();
            $table->string('vehicle_type', 30);
            $table->string('license_plate', 20)->unique();
            $table->string('manufacturer', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->integer('capacity_kg')->nullable();
            $table->decimal('fuel_consumption', 6, 2)->nullable()->comment('L/100km');
            $table->date('purchase_date')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->string('status', 30);
            $table->timestamps();
            
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
