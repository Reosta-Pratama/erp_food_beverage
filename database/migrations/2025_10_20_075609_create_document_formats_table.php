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
        Schema::create('document_formats', function (Blueprint $table) {
            $table->id('format_id');
            $table->string('document_type', 50)->unique()->comment('Invoice, Delivery Note, Label, etc');
            $table->string('template_name', 200);
            $table->text('template_content')->nullable()->comment('HTML/JSON template');
            $table->string('file_format', 30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_formats');
    }
};
