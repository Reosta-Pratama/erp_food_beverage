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
        Schema::create('sanitation_inspections', function (Blueprint $table) {
            $table->id('inspection_id');
            $table->unsignedBigInteger('checklist_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->date('inspection_date');
            $table->unsignedBigInteger('inspector_id');
            $table->decimal('score', 5, 2)->nullable()->comment('0-100');
            $table->string('result', );
            $table->text('findings')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->timestamps();

            $table->foreign('checklist_id')->references('checklist_id')->on('sanitation_checklists');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses');
            $table->foreign('inspector_id')->references('employee_id')->on('employees');
            
            $table->index('inspection_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanitation_inspections');
    }
};
