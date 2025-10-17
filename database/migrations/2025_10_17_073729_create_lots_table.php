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
        Schema::create('lots', function (Blueprint $table) {
            $table->id('lot_id');
            $table->char('lot_code', 10)->unique();
            $table->unsignedBigInteger('product_id');
            $table->date('manufacture_date');
            $table->date('expiry_date')->nullable()->comment('Null untuk produk yang tidak punya expired date');
            $table->decimal('quantity', 15, 4);
            $table->string('status', 30);
            $table->unsignedBigInteger('supplier_id')->nullable()->comment('NULL untuk produksi internal');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->nullOnDelete();
            
            $table->index('expiry_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};
