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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id('work_order_id');
            $table->char('work_order_code', 10)->unique();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('bom_id')->nullable()->comment('Null untuk produk tanpa BOM');
            $table->decimal('quantity_ordered', 15, 4);
            $table->decimal('quantity_produced', 15, 4)->nullable()->default(0);
            $table->date('scheduled_start');
            $table->date('scheduled_end');
            $table->timestamp('actual_start')->nullable()->comment('NULL sampai mulai produksi');
            $table->timestamp('actual_end')->nullable()->comment('NULL sampai selesai');
            $table->string('status', 30);
            $table->unsignedBigInteger('assigned_to')->nullable()->comment('Null bila belum ada assigned');
            $table->text('instructions')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('bom_id')->references('bom_id')->on('bill_of_materials')->nullOnDelete();
            $table->foreign('assigned_to')->references('employee_id')->on('employees')->nullOnDelete();
            
            $table->index('status');
            $table->index(['scheduled_start', 'scheduled_end']);
            $table->index(['status', 'scheduled_start', 'scheduled_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
