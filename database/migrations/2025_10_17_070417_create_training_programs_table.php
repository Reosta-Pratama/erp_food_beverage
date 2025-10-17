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
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id('training_id');
            $table->string('training_name', 200);
            $table->char('training_code', 10)->unique();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('trainer_name', 150)->nullable();
            $table->string('location', 200)->nullable();
            $table->integer('max_participants')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_programs');
    }
};
