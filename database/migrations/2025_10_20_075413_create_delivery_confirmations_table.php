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
        Schema::create('delivery_confirmations', function (Blueprint $table) {
            $table->id('confirmation_id');
            $table->unsignedBigInteger('delivery_id');
            $table->date('delivery_date');
            $table->time('delivery_time');
            $table->string('recipient_name', 150);
            $table->string('recipient_position', 100)->nullable();
            $table->string('signature_path')->nullable()->comment('Digital signature image');
            $table->text('delivery_notes')->nullable();
            $table->string('proof_of_delivery_path')->nullable()->comment('Photo or document');
            $table->timestamps();

            $table->foreign('delivery_id')->references('delivery_id')->on('deliveries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_confirmations');
    }
};
