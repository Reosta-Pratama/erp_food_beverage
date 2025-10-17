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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id('activity_id');
            $table->unsignedBigInteger('user_id');
            $table->string('activity_type', 100);
            $table->text('description')->nullable();
            $table->string('module_name', 100);
            $table->timestamp('activity_timestamp')->useCurrent();

            $table->foreign('user_id')->references('user_id')->on('users');

            $table->index(['user_id', 'activity_timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
