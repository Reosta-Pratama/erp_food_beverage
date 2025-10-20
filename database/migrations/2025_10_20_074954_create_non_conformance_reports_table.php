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
        Schema::create('non_conformance_reports', function (Blueprint $table) {
            $table->id('ncr_id');
            $table->char('ncr_code', 10)->unique();
            $table->date('report_date');
            $table->unsignedBigInteger('reported_by');
            $table->string('nc_category', 30);
            $table->string('nc_type', 30);
            $table->unsignedBigInteger('product_id')->nullable()->comment('NULL jika NC tidak terkait product');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->text('description');
            $table->string('severity', 30);
            $table->string('status', 30);
            $table->text('immediate_action')->nullable();
            $table->timestamps();
            
            $table->foreign('reported_by')->references('employee_id')->on('employees');
            $table->foreign('product_id')->references('product_id')->on('products')->nullOnDelete();
            $table->foreign('batch_id')->references('batch_id')->on('batches')->nullOnDelete();
            
            $table->index('status');
            $table->index('report_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_conformance_reports');
    }
};
