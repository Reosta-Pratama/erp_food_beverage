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
        Schema::create('accounts_receivable', function (Blueprint $table) {
            $table->id('ar_id');
            $table->char('ar_code', 10)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('so_id')->nullable()->comment('NULL untuk AR tanpa SO');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('invoice_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->nullable()->default(0);
            $table->decimal('balance_amount', 15, 2);
            $table->string('status', 30);
            $table->string('payment_terms', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('so_id')->references('so_id')->on('sales_orders')->nullOnDelete();
            
            $table->index('status');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_receivable');
    }
};
