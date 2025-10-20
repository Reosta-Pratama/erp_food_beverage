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
        Schema::create('delivery_routes', function (Blueprint $table) {
            $table->id('route_id');
            $table->string('route_name', 200);
            $table->char('route_code', 10)->unique();
            $table->text('route_description')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('estimated_duration_hours', 6, 2)->nullable();
            $table->json('waypoints')->nullable()->comment('Array of GPS coordinates');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_routes');
    }
};
