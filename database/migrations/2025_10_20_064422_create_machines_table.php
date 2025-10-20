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
        Schema::create('machines', function (Blueprint $table) {
            $table->id('machine_id');
            $table->string('machine_code', 10)->unique();
            $table->string('machine_name', 200);
            $table->string('machine_type', 100);
            $table->string('manufacturer', 150)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->nullable()->unique();
            $table->date('purchase_date')->nullable();
            $table->date('installation_date')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable()->comment('NULL jika machine belum dialokasikan');
            $table->string('status', 30);
            $table->text('specifications')->nullable();
            $table->timestamps();
            
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
