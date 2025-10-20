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
        Schema::create('daily_audits', function (Blueprint $table) {
            $table->id('audit_id');
            $table->string('audit_type', 30);
            $table->date('audit_date');
            $table->unsignedBigInteger('auditor_id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('area_audited', 200);
            $table->decimal('compliance_score', 5, 2)->nullable()->comment('0-100');
            $table->text('findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->string('status', 30)->default('Open');
            $table->timestamps();

            $table->foreign('auditor_id')->references('employee_id')->on('employees');
            $table->foreign('department_id')->references('department_id')->on('departments')->nullOnDelete();
            
            $table->index('audit_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_audits');
    }
};
