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
        Schema::create('purchase_receipt_items', function (Blueprint $table) {
            $table->id('receipt_item_id');
            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('po_item_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('lot_id')->nullable()->comment('Nullable: akan dibuat saat receipt');
            $table->decimal('quantity_received', 15, 4);
            $table->unsignedBigInteger('uom_id');
            $table->date('manufacture_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('condition', 30);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('receipt_id')->references('receipt_id')->on('purchase_receipts')->cascadeOnDelete();
            $table->foreign('po_item_id')->references('po_item_id')->on('purchase_order_items');
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
        Schema::dropIfExists('purchase_receipt_items');
    }
};
