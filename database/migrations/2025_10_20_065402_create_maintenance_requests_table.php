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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->char('ticket_code', 10)->unique();
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('requested_by');
            $table->date('request_date');
            $table->string('priority', 30);
            $table->string('issue_type', 30);
            $table->text('issue_description');
            $table->string('status', 30);
            $table->unsignedBigInteger('assigned_to')->nullable()->comment('NULL sampai assigned');
            $table->date('resolution_date')->nullable()->comment('NULL sampai resolved');
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
            
            $table->foreign('machine_id')->references('machine_id')->on('machines');
            $table->foreign('requested_by')->references('employee_id')->on('employees');
            $table->foreign('assigned_to')->references('technician_id')->on('technicians')->nullOnDelete();
            
            $table->index('status');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
