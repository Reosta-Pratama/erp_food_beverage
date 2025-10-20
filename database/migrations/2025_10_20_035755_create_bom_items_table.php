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
        Schema::create('bom_items', function (Blueprint $table) {
            $table->id('bom_item_id');
            $table->unsignedBigInteger('bom_id');
            $table->unsignedBigInteger('material_id');
            $table->decimal('quantity_required', 15, 4);
            $table->unsignedBigInteger('uom_id');
            $table->string('item_type', 30);
            $table->decimal('scrap_percentage', 5, 2)->nullable()->default(0);
            $table->timestamps();

            $table->foreign('bom_id')->references('bom_id')->on('bill_of_materials')->cascadeOnDelete();
            $table->foreign('material_id')->references('product_id')->on('products');
            $table->foreign('uom_id')->references('uom_id')->on('units_of_measure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_items');
    }
};
