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
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id('sr_id');
            $table->char('sr_code', 10)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('delivery_id')->nullable()->comment('NULL bisa return tanpa delivery reference');
            $table->date('return_date');
            $table->string('return_reason', 30);
            $table->string('status', 30);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('refund_method', 30)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('delivery_id')->references('delivery_id')->on('deliveries')->nullOnDelete();
            $table->foreign('created_by')->references('user_id')->on('users');
            
            $table->index('return_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_returns');
    }
};
