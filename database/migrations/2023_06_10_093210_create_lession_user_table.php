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
        Schema::create('lession_user', function (Blueprint $table) {
            
            $table->unsignedBigInteger('lession_id');
            $table->foreign('lession_id')->references('id')->on('lessions')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
            $table->enum('status',['Em Progresso','Concluída'])->default('Em Progresso');
            $table->timestamp('started_at')->default(now());
            $table->timestamp('ended_at')->nullable();
            $table->primary(['lession_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lession_user');
    }
};
