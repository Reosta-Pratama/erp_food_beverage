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
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id('tax_id');
            $table->string('tax_name', 100)->unique();
            $table->char('tax_code', 10)->unique();
            $table->decimal('tax_percentage', 5, 2);
            $table->string('tax_type', 30);
            $table->boolean('is_active')->default(true);
            $table->date('effective_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_rates');
    }
};
