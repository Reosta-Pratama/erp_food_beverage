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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id('delivery_id');
            $table->char('delivery_code', 10)->unique();
            $table->unsignedBigInteger('so_id');
            $table->unsignedBigInteger('customer_id');
            $table->date('delivery_date');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('driver_id')->nullable()->comment('Bisa pickup by customer');
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->text('shipping_address');
            $table->string('status', 30);
            $table->time('departure_time')->nullable();
            $table->time('arrival_time')->nullable();
            $table->unsignedBigInteger('delivered_by')->nullable()->comment('NULL sampai delivered');
            $table->string('signature_path')->nullable()->comment('Digital signature file');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('so_id')->references('so_id')->on('sales_orders');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses');
            $table->foreign('driver_id')->references('driver_id')->on('drivers')->nullOnDelete();
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles')->nullOnDelete();
            $table->foreign('delivered_by')->references('employee_id')->on('employees')->nullOnDelete();
            
            $table->index('status');
            $table->index('delivery_date');
            $table->index(['status', 'delivery_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
