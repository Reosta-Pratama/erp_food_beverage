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
        Schema::create('sales_return_items', function (Blueprint $table) {
            $table->id('sr_item_id');
            $table->unsignedBigInteger('sr_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('lot_id')->nullable();
            $table->decimal('quantity_returned', 15, 4);
            $table->unsignedBigInteger('uom_id');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('line_total', 15, 2);
            $table->string('defect_type', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('sr_id')->references('sr_id')->on('sales_returns')->cascadeOnDelete();
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('lot_id')->references('lot_id')->on('lots')->nullOnDelete();
            $table->foreign('uom_id')->references('uom_id')->on('units_of_measure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_return_items');
    }
};
