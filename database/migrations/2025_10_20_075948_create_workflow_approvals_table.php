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
        Schema::create('workflow_approvals', function (Blueprint $table) {
            $table->id('approval_id');
            $table->unsignedBigInteger('workflow_id');
            $table->string('reference_type', 30)->comment('PO, SO, Leave, etc');
            $table->unsignedBigInteger('reference_id');
            $table->unsignedBigInteger('approver_id');
            $table->integer('approval_level')->default(1);
            $table->string('status', 30);
            $table->text('comments')->nullable();
            $table->timestamp('action_date')->nullable()->comment('NULL sampai ada action');
            $table->timestamps();

            $table->foreign('workflow_id')->references('workflow_id')->on('workflow');
            $table->foreign('approver_id')->references('employee_id')->on('employees');
            
            $table->index('status');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_approvals');
    }
};
