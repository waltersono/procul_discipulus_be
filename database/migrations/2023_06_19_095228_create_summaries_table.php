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
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lession_id');
            $table->string('name');
            $table->enum('type',['png','jpeg','gif','mp3','bmp','pdf','csv','xslx','docx','mp4','webm']);
            $table->string('file_path')->nullable();
            $table->foreign('lession_id')->references('id')->on('lessions')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summaries');
    }
};
