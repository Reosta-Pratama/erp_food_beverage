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
        Schema::create('purchase_receipts', function (Blueprint $table) {
            $table->id('receipt_id');
            $table->char('receipt_code', 10)->unique();
            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('supplier_id');
            $table->date('receipt_date');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('received_by');
            $table->string('status', 30);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('po_id')->references('po_id')->on('purchase_orders');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses');
            $table->foreign('received_by')->references('employee_id')->on('employees');
            
            $table->index('receipt_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_receipts');
    }
};
