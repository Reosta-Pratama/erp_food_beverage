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
        Schema::table('bill_of_materials', function (Blueprint $table) {
            //
            $table->char('bom_code', 10)->unique()->after('bom_id');
            $table->index('bom_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_of_materials', function (Blueprint $table) {
            //
            $table->dropUnique(['bom_code']);
            $table->dropIndex(['bom_code']);
            $table->dropColumn('bom_code');
        });
    }
};
