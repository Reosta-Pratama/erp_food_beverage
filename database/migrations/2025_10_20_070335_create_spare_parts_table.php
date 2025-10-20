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
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id('spare_part_id');
            $table->string('part_code', 10)->unique();
            $table->string('part_name', 200);
            $table->unsignedBigInteger('machine_id')->nullable()->comment('Spare part universal');
            $table->decimal('quantity_on_hand', 15, 4)->default(0);
            $table->decimal('reorder_point', 15, 4)->nullable();
            $table->decimal('reorder_quantity', 15, 4)->nullable();
            $table->unsignedBigInteger('uom_id');
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->timestamps();
            
            $table->foreign('machine_id')->references('machine_id')->on('machines')->nullOnDelete();
            $table->foreign('uom_id')->references('uom_id')->on('units_of_measure');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
