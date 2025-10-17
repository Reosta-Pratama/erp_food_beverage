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
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('reviewer_id');
            $table->date('review_period_start');
            $table->date('review_period_end');
            $table->decimal('performance_score', 5, 2)->nullable()->comment('0-100');
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('reviewer_id')->references('employee_id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
