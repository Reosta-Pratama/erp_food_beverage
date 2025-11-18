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
        Schema::table('recipes', function (Blueprint $table) {
            //
            $table->char('recipe_code', 10)->unique()->after('recipe_id');
            $table->index('recipe_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            //
            $table->dropUnique(['recipe_code']);
            $table->dropIndex(['recipe_code']);
            $table->dropColumn('recipe_code');
        });
    }
};
