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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id('budget_id');
            $table->string('budget_name', 200);
            $table->integer('fiscal_year');
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->decimal('budgeted_amount', 15, 2);
            $table->decimal('actual_amount', 15, 2)->nullable()->default(0);
            $table->decimal('variance', 15, 2)->nullable()->default(0);
            $table->string('period_type', 30);
            $table->string('period_number', 30)->comment('1-12 for Monthly, 1-4 for Quarterly, 1 for Yearly');
            $table->timestamps();
            
            $table->foreign('cost_center_id')->references('cost_center_id')->on('cost_centers')->nullOnDelete();
            $table->foreign('account_id')->references('account_id')->on('chart_of_accounts')->nullOnDelete();
            
            $table->index('fiscal_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
