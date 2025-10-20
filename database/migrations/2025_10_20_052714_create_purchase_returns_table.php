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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id('pr_id');
            $table->char('pr_code', 10)->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('receipt_id')->nullable()->comment('Nullable: bisa return tanpa receipt reference');
            $table->date('return_date');
            $table->string('return_reason', 30);
            $table->string('status', 30);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->unsignedBigInteger('created_by');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers');
            $table->foreign('receipt_id')->references('receipt_id')->on('purchase_receipts')->nullOnDelete();
            $table->foreign('created_by')->references('user_id')->on('users');
            
            $table->index('return_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};
