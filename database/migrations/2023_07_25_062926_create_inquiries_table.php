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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teste_id');
            $table->foreign('teste_id')->references('id')->on('testes')->onUpdate('cascade')->onDelete('cascade');
            $table->text('question');
            $table->text('optionA');
            $table->text('optionB');
            $table->text('optionC');
            $table->text('optionD');
            $table->enum('correct',['A','B','C','D']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
