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
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id('so_item_id');
            $table->unsignedBigInteger('so_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity_ordered', 15, 4);
            $table->decimal('quantity_delivered', 15, 4)->nullable()->default(0);
            $table->unsignedBigInteger('uom_id');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('discount_percentage', 5, 2)->nullable()->default(0);
            $table->decimal('tax_percentage', 5, 2)->nullable()->default(0);
            $table->decimal('line_total', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('so_id')->references('so_id')->on('sales_orders')->cascadeOnDelete();
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('uom_id')->references('uom_id')->on('units_of_measure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
    }
};
