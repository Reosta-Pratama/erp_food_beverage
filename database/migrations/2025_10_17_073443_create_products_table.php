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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->char('product_code', 10)->unique();
            $table->string('product_name', 200);
            $table->string('product_type', 30);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('uom_id');
            $table->decimal('standard_cost', 15, 2)->nullable()->default(0);
            $table->decimal('selling_price', 15, 2)->nullable()->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('category_id')->references('category_id')->on('product_categories');
            $table->foreign('uom_id')->references('uom_id')->on('units_of_measure');
            
            $table->index('product_type');
            $table->index('is_active');
            $table->index(['category_id', 'product_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
