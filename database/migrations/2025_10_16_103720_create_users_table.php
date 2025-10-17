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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username', 100)->unique();
            $table->string('email', 150)->unique();
            $table->string('password_hash', 255);
            $table->string('full_name', 200);
            $table->string('phone', 20)->nullable();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('employee_id')->nullable()->comment('Bisa saja user non-employee');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login')->nullable();
            $table->timestamps();

            $table->foreign('role_id')->references('role_id')->on('roles');
            $table->foreign('employee_id')->references('employee_id')->on('employees')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
