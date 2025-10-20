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
        Schema::create('warehouse_locations', function (Blueprint $table) {
            $table->id('location_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->char('location_code', 10)->unique();
            $table->string('location_name', 150);
            $table->string('aisle', 20)->nullable();
            $table->string('rack', 20)->nullable();
            $table->string('bin', 20)->nullable();
            $table->timestamps();

            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_locations');
    }
};
