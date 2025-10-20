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
        Schema::create('production_planning', function (Blueprint $table) {
            $table->id('plan_id');
            $table->char('plan_code', 10)->unique();
            $table->date('plan_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status', 30);
            $table->unsignedBigInteger('created_by');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_planning');
    }
};
