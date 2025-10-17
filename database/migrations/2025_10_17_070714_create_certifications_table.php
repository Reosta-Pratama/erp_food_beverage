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
        Schema::create('certifications', function (Blueprint $table) {
            $table->id('certification_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('certification_name', 200);
            $table->string('issuing_authority', 200);
            $table->date('issue_date');
            $table->date('expiry_date')->nullable()->comment('Null karena beberapa sertifikat lifetime');
            $table->string('certificate_number', 100)->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employees');

            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
