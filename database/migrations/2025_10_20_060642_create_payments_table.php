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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->char('payment_code', 10)->unique();
            $table->string('payment_type', 30);
            $table->unsignedBigInteger('supplier_id')->nullable()->comment('NULL jika payment_type = Receivable');
            $table->unsignedBigInteger('customer_id')->nullable()->comment('NULL jika payment_type = Payable');
            $table->unsignedBigInteger('ap_id')->nullable();
            $table->unsignedBigInteger('ar_id')->nullable();
            $table->date('payment_date');
            $table->decimal('payment_amount', 15, 2);
            $table->string('payment_method', 30);
            $table->string('bank_account', 100)->nullable();
            $table->string('reference_number', 100)->nullable();
            $table->string('status', 30);
            $table->unsignedBigInteger('processed_by');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->nullOnDelete();
            $table->foreign('customer_id')->references('customer_id')->on('customers')->nullOnDelete();
            $table->foreign('ap_id')->references('ap_id')->on('accounts_payable')->nullOnDelete();
            $table->foreign('ar_id')->references('ar_id')->on('accounts_receivable')->nullOnDelete();
            $table->foreign('processed_by')->references('employee_id')->on('employees');
            
            $table->index('payment_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
