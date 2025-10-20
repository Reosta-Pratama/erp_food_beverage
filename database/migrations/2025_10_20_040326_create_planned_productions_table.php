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
        Schema::create('planned_productions', function (Blueprint $table) {
            $table->id('planned_production_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('planned_quantity', 15, 4);
            $table->unsignedBigInteger('uom_id');
            $table->date('target_date');
            $table->string('priority', 30);
            $table->timestamps();

            $table->foreign('plan_id')->references('plan_id')->on('production_planning')->cascadeOnDelete();
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('uom_id')->references('uom_id')->on('units_of_measure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planned_productions');
    }
};
