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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('location_id')->nullable()->comment('Null jika produk belum dialokasikan ke lokasi spesifik');
            $table->unsignedBigInteger('lot_id')->nullable()->comment('Null jika produk tanpa lot tracking');
            $table->decimal('quantity_on_hand', 15, 4)->default(0);
            $table->decimal('quantity_reserved', 15, 4)->nullable()->default(0);
            $table->decimal('quantity_available', 15, 4)->default(0);
            $table->decimal('reorder_point', 15, 4)->nullable();
            $table->decimal('reorder_quantity', 15, 4)->nullable();
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
                        
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses');
            $table->foreign('location_id')->references('location_id')->on('warehouse_locations')->nullOnDelete();
            $table->foreign('lot_id')->references('lot_id')->on('lots')->nullOnDelete();
            
            $table->index(['product_id', 'warehouse_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
