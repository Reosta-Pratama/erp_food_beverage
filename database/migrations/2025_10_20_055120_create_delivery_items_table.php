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
        Schema::create('delivery_items', function (Blueprint $table) {
            $table->id('delivery_item_id');
            $table->unsignedBigInteger('delivery_id');
            $table->unsignedBigInteger('so_item_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('lot_id')->nullable()->comment('NULL untuk produk tanpa lot tracking');
            $table->decimal('quantity_delivered', 15, 4);
            $table->unsignedBigInteger('uom_id');
            $table->string('condition', 30);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('delivery_id')->references('delivery_id')->on('deliveries')->cascadeOnDelete();
            $table->foreign('so_item_id')->references('so_item_id')->on('sales_order_items');
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
        Schema::dropIfExists('delivery_items');
    }
};
