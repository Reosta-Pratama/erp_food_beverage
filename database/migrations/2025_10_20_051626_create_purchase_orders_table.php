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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id('po_id');
            $table->char('po_code', 10)->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->date('order_date');
            $table->date('expected_delivery');
            $table->date('actual_delivery')->nullable()->comment('NULL sampai barang diterima');
            $table->string('status', 30);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->nullable()->default(0);
            $table->decimal('discount_amount', 15, 2)->nullable()->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('payment_terms', 100)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable()->comment('NULL sampai approved');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers');
            $table->foreign('created_by')->references('user_id')->on('users');
            $table->foreign('approved_by')->references('employee_id')->on('employees')->nullOnDelete();
            
            $table->index('status');
            $table->index('order_date');
            $table->index(['supplier_id', 'status', 'order_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
