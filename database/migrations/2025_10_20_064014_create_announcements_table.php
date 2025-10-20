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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id('announcement_id');
            $table->string('announcement_title', 200);
            $table->text('announcement_content');
            $table->string('priority', 30);
            $table->date('publish_date');
            $table->date('expiry_date')->nullable()->comment('NULL jika announcement tanpa expiry');
            $table->unsignedBigInteger('created_by');
            $table->string('target_audience', 30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('created_by')->references('user_id')->on('users');
            
            $table->index('publish_date');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
