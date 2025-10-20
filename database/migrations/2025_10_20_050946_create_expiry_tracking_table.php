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
        Schema::create('expiry_tracking', function (Blueprint $table) {
            $table->id('expiry_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('lot_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->date('expiry_date');
            $table->decimal('quantity', 15, 4);
            $table->string('status', 30);
            $table->date('alert_date')->nullable()->comment('Tanggal sistem mulai alert');
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('lot_id')->references('lot_id')->on('lots');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses');
            
            $table->index('expiry_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expiry_tracking');
    }
};
