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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->char('employee_code', 10)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 150)->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 10);
            $table->text('address')->nullable();
            $table->string('id_number', 50)->nullable()->comment('KTP/ID Card');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('position_id');
            $table->date('join_date');
            $table->date('resign_date')->nullable()->comment('NULL = masih aktif');
            $table->string('employment_status', 30)->default('Probation');
            $table->decimal('base_salary', 15, 2)->nullable();
            $table->timestamps();

            $table->foreign('department_id')->references('department_id')->on('departments');
            $table->foreign('position_id')->references('position_id')->on('positions');

            $table->index(['department_id', 'position_id', 'employment_status']);
            $table->index('employment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
