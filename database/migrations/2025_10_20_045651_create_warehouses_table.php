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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id('warehouse_id');
            $table->char('warehouse_code', 10)->unique();
            $table->string('warehouse_name', 200);
            $table->string('warehouse_type', 30);
            $table->text('address')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->decimal('capacity', 15, 2)->nullable()->comment('in cubic meters');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('manager_id')->references('employee_id')->on('employees')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
