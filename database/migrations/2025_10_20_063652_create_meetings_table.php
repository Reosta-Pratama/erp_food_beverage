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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id('meeting_id');
            $table->string('meeting_title', 200);
            $table->text('meeting_agenda')->nullable();
            $table->date('meeting_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location', 200)->nullable();
            $table->unsignedBigInteger('organizer_id');
            $table->string('status', 30);
            $table->text('minutes')->nullable()->comment('Diisi setelah meeting selesai');
            $table->timestamps();
            
            $table->foreign('organizer_id')->references('employee_id')->on('employees');
            
            $table->index('meeting_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
