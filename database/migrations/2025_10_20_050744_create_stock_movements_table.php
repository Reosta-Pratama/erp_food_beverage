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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id('movement_id');
            $table->char('movement_code', 10)->unique();
            $table->string('movement_type', 300);
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('from_warehouse_id')->nullable()->comment('Null untuk receipt dari supplier');
            $table->unsignedBigInteger('to_warehouse_id')->nullable()->comment('Null untuk issue ke customer');
            $table->unsignedBigInteger('from_location_id')->nullable();
            $table->unsignedBigInteger('to_location_id')->nullable();
            $table->unsignedBigInteger('lot_id')->nullable();
            $table->decimal('quantity', 15, 4);
            $table->unsignedBigInteger('uom_id');
            $table->date('movement_date');
            $table->unsignedBigInteger('performed_by');
            $table->string('reference_type', 30)->nullable()->comment('PO, SO, WO, dll');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('from_warehouse_id')->references('warehouse_id')->on('warehouses')->nullOnDelete();
            $table->foreign('to_warehouse_id')->references('warehouse_id')->on('warehouses')->nullOnDelete();
            $table->foreign('from_location_id')->references('location_id')->on('warehouse_locations')->nullOnDelete();
            $table->foreign('to_location_id')->references('location_id')->on('warehouse_locations')->nullOnDelete();
            $table->foreign('lot_id')->references('lot_id')->on('lots')->nullOnDelete();
            $table->foreign('uom_id')->references('uom_id')->on('units_of_measure');
            $table->foreign('performed_by')->references('employee_id')->on('employees');
            
            $table->index('movement_type');
            $table->index('movement_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
