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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id('leave_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->text('reason')->nullable();
            $table->string('status', 30);
            $table->unsignedBigInteger('approved_by')->nullable()->comment('NULL sampai ada approval');
            $table->timestamp('approval_date')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('leave_type_id')->references('leave_type_id')->on('leave_types');
            $table->foreign('approved_by')->references('employee_id')->on('employees')->nullOnDelete();

            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
