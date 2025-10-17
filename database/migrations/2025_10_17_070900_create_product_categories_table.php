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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category_name', 150)->unique();
            $table->char('category_code', 10)->unique();
            $table->unsignedBigInteger('parent_category_id')->nullable()->comment('NULL untuk root category');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('parent_category_id')->references('category_id')->on('product_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
