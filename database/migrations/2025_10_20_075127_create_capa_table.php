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
        Schema::create('capa', function (Blueprint $table) {
            $table->id('capa_id');
            $table->char('capa_code', 10)->unique();
            $table->unsignedBigInteger('ncr_id');
            $table->string('action_type', 30);
            $table->text('root_cause_analysis')->nullable();
            $table->text('corrective_action')->nullable();
            $table->text('preventive_action')->nullable();
            $table->unsignedBigInteger('responsible_person_id');
            $table->date('target_date');
            $table->date('completion_date')->nullable()->comment('NULL sampai selesai');
            $table->string('status', 30);
            $table->text('verification_notes')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable()->comment('NULL sampai verified');
            $table->timestamps();
            
            $table->foreign('ncr_id')->references('ncr_id')->on('non_conformance_reports');
            $table->foreign('responsible_person_id')->references('employee_id')->on('employees');
            $table->foreign('verified_by')->references('employee_id')->on('employees')->nullOnDelete();
            
            $table->index('status');
            $table->index('target_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capa');
    }
};
