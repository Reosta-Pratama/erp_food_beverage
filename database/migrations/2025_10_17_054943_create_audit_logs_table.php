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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('audit_id');
            $table->unsignedBigInteger('user_id');
            $table->string('action_type', 20)->comment('CREATE, UPDATE, DELETE, LOGIN, LOGOUT');
            $table->string('module_name', 100);
            $table->string('table_name', 100);
            $table->unsignedBigInteger('record_id')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->timestamp('action_timestamp')->useCurrent();

            $table->foreign('user_id')->references('user_id')->on('users');

            $table->index(['user_id', 'action_timestamp']);
            $table->index(['module_name', 'table_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
