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
        Schema::create('batches', function (Blueprint $table) {
            $table->id('batch_id');
            $table->char('batch_code', 10)->unique();
            $table->unsignedBigInteger('work_order_id')->nullable()->comment('Bisa manual batch tanpa WO');
            $table->unsignedBigInteger('product_id');
            $table->date('production_date');
            $table->decimal('quantity_produced', 15, 4);
            $table->decimal('quantity_approved', 15, 4)->nullable()->default(0);
            $table->decimal('quantity_rejected', 15, 4)->nullable()->default(0);
            $table->string('status', 30);
            $table->timestamps();

            $table->foreign('work_order_id')->references('work_order_id')->on('work_orders')->nullOnDelete();
            $table->foreign('product_id')->references('product_id')->on('products');
            
            $table->index('status');
            $table->index('production_date');
            $table->index(['work_order_id', 'status', 'production_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
