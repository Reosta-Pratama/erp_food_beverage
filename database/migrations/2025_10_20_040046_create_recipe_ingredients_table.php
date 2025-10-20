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
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id('ingredient_id');
            $table->unsignedBigInteger('recipe_id');
            $table->unsignedBigInteger('material_id');
            $table->decimal('quantity', 15, 4);
            $table->unsignedBigInteger('uom_id');
            $table->text('preparation_notes')->nullable();
            $table->integer('sequence_order')->nullable();
            $table->timestamps();

            $table->foreign('recipe_id')->references('recipe_id')->on('recipes')->cascadeOnDelete();
            $table->foreign('material_id')->references('product_id')->on('products');
            $table->foreign('uom_id')->references('uom_id')->on('units_of_measure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
