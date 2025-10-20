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
        Schema::create('certification_documents', function (Blueprint $table) {
            $table->id('doc_id');
            $table->string('certification_type', 30);
            $table->string('certification_number', 100)->unique();
            $table->string('issuing_authority', 200);
            $table->date('issue_date');
            $table->date('expiry_date')->nullable()->comment('NULL untuk beberapa sertifikat tidak ada expiry');
            $table->string('document_path')->nullable();
            $table->string('status', 30);
            $table->unsignedBigInteger('responsible_person_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('responsible_person_id')->references('employee_id')->on('employees')->nullOnDelete();
            
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certification_documents');
    }
};
