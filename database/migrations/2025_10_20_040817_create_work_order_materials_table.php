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
        Schema::create('work_order_materials', function (Blueprint $table) {
            $table->id('wom_id');
            $table->unsignedBigInteger('work_order_id');
            $table->unsignedBigInteger('material_id');
            $table->decimal('quantity_required', 15, 4);
            $table->decimal('quantity_consumed', 15, 4)->nullable()->default(0);
            $table->unsignedBigInteger('uom_id');
            $table->timestamps();

            $table->foreign('work_order_id')->references('work_order_id')->on('work_orders')->cascadeOnDelete();
            $table->foreign('material_id')->references('product_id')->on('products');
            $table->foreign('uom_id')->references('uom_id')->on('units_of_measure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_materials');
    }
};
