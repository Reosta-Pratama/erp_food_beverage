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
        Schema::create('quality_control', function (Blueprint $table) {
            $table->id('qc_id');
            $table->char('qc_code', 10)->unique();
            $table->string('inspection_type', 30);
            $table->unsignedBigInteger('work_order_id')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->date('inspection_date');
            $table->unsignedBigInteger('inspector_id');
            $table->string('result', 30);
            $table->decimal('quantity_inspected', 15, 4);
            $table->decimal('quantity_passed', 15, 4)->nullable()->default(0);
            $table->decimal('quantity_failed', 15, 4)->nullable()->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('work_order_id')->references('work_order_id')->on('work_orders')->nullOnDelete();
            $table->foreign('batch_id')->references('batch_id')->on('batches')->nullOnDelete();
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('inspector_id')->references('employee_id')->on('employees');
            
            $table->index('result');
            $table->index('inspection_date');
            $table->index(['product_id', 'result', 'inspection_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_control');
    }
};
