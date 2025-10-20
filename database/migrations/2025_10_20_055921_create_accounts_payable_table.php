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
        Schema::create('accounts_payable', function (Blueprint $table) {
            $table->id('ap_id');
            $table->char('ap_code', 10)->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('po_id')->nullable()->comment('NULL untuk AP tanpa PO');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('invoice_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->nullable()->default(0);
            $table->decimal('balance_amount', 15, 2);
            $table->string('status', 30);
            $table->string('payment_terms', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers');
            $table->foreign('po_id')->references('po_id')->on('purchase_orders')->nullOnDelete();
            
            $table->index('status');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_payable');
    }
};
