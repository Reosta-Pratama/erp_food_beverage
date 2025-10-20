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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id('journal_id');
            $table->char('journal_code', 10)->unique();
            $table->date('journal_date');
            $table->string('journal_type', 30);
            $table->string('reference_type', 100)->nullable()->comment('PO, SO, Payment, etc');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_credit', 15, 2)->default(0);
            $table->string('status', 30);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable()->comment('NULL sampai approved');
            $table->timestamps();

            $table->foreign('created_by')->references('user_id')->on('users');
            $table->foreign('approved_by')->references('employee_id')->on('employees')->nullOnDelete();
            
            $table->index('journal_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
