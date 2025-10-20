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
        Schema::create('downtime_tracking', function (Blueprint $table) {
            $table->id('downtime_id');
            $table->unsignedBigInteger('machine_id');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable()->comment('NULL jika masih downtime');
            $table->decimal('duration_hours', 6, 2)->nullable()->comment('Calculated saat end_time diisi');
            $table->string('downtime_reason', 30);
            $table->string('downtime_category', 30);
            $table->unsignedBigInteger('reported_by');
            $table->decimal('production_loss', 15, 2)->nullable()->comment('Estimated loss in currency');
            $table->text('notes')->nullable();
            $table->timestamps();
                        
            $table->foreign('machine_id')->references('machine_id')->on('machines');
            $table->foreign('reported_by')->references('employee_id')->on('employees');
            
            $table->index(['machine_id', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downtime_tracking');
    }
};
