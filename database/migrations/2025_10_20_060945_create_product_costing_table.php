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
        Schema::create('product_costing', function (Blueprint $table) {
            $table->id('costing_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('material_cost', 15, 2)->nullable()->default(0);
            $table->decimal('labor_cost', 15, 2)->nullable()->default(0);
            $table->decimal('overhead_cost', 15, 2)->nullable()->default(0);
            $table->decimal('total_cost', 15, 2);
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->decimal('profit_margin', 5, 2)->nullable()->comment('Percentage');
            $table->date('effective_date');
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            
            $table->index(['product_id', 'effective_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_costing');
    }
};
