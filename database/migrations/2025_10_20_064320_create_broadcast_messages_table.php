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
        Schema::create('broadcast_messages', function (Blueprint $table) {
            $table->id('broadcast_id');
            $table->string('message_title', 200);
            $table->text('message_content');
            $table->string('message_type', 30);
            $table->unsignedBigInteger('sender_id');
            $table->timestamp('sent_at')->useCurrent();
            $table->string('target_roles', 200)->nullable()->comment('Comma-separated role codes');
            $table->boolean('is_urgent')->default(false);
            $table->timestamps();

            $table->foreign('sender_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_messages');
    }
};
