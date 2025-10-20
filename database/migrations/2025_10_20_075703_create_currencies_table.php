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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id('currency_id');
            $table->string('currency_code', 3)->unique()->comment('IDR, USD, EUR');
            $table->string('currency_name', 100);
            $table->string('symbol', 10)->nullable();
            $table->decimal('exchange_rate', 15, 6)->default(1.00);
            $table->boolean('is_base_currency')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
