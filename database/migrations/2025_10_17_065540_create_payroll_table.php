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
        Schema::create('payroll', function (Blueprint $table) {
            $table->id('payroll_id');
            $table->unsignedBigInteger('employee_id');
            $table->tinyInteger('month')->comment('1-12');
            $table->integer('year');
            $table->decimal('base_salary', 15, 2);
            $table->decimal('overtime_pay', 15, 2)->nullable()->default(0);
            $table->decimal('allowances', 15, 2)->nullable()->default(0);
            $table->decimal('deductions', 15, 2)->nullable()->default(0);
            $table->decimal('gross_salary', 15, 2);
            $table->decimal('net_salary', 15, 2);
            $table->date('payment_date')->nullable()->comment('NULL jika belum dibayar');
            $table->string('status', 30);
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employees');

            $table->index(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll');
    }
};
