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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id('recipe_id');
            $table->unsignedBigInteger('product_id');
            $table->string('recipe_name', 200);
            $table->string('recipe_version', 20)->default('1.0');
            $table->text('instructions')->nullable();
            $table->decimal('batch_size', 15, 4);
            $table->unsignedBigInteger('uom_id');
            $table->decimal('preparation_time', 6, 2)->nullable()->comment('in minutes');
            $table->decimal('cooking_time', 6, 2)->nullable()->comment('in minutes');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('uom_id')->references('uom_id')->on('units_of_measure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
