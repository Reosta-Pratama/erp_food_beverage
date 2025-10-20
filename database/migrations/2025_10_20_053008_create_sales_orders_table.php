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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id('so_id');
            $table->char('so_code', 10)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->date('order_date');
            $table->date('requested_delivery');
            $table->date('confirmed_delivery')->nullable()->comment('NULL sampai dikonfirmasi');
            $table->string('status', 30);
            $table->string('order_type', 30);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->nullable()->default(0);
            $table->decimal('discount_amount', 15, 2)->nullable()->default(0);
            $table->decimal('shipping_cost', 15, 2)->nullable()->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('payment_status', 30);
            $table->unsignedBigInteger('sales_person_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable()->comment('NULL sampai approved');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('sales_person_id')->references('employee_id')->on('employees')->nullOnDelete();
            $table->foreign('created_by')->references('user_id')->on('users');
            $table->foreign('approved_by')->references('employee_id')->on('employees')->nullOnDelete();
            
            $table->index('status');
            $table->index('order_date');
            $table->index(['customer_id', 'status', 'order_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
