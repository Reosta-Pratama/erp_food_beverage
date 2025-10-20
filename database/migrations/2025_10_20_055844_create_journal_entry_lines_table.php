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
        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id('line_id');
            $table->unsignedBigInteger('journal_id');
            $table->unsignedBigInteger('account_id');
            $table->decimal('debit_amount', 15, 2)->nullable()->default(0);
            $table->decimal('credit_amount', 15, 2)->nullable()->default(0);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('journal_id')->references('journal_id')->on('journal_entries')->cascadeOnDelete();
            $table->foreign('account_id')->references('account_id')->on('chart_of_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entry_lines');
    }
};
