<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_ubication', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ubication_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ubication_id')->references('id')->on('ubications')->onDelete('cascade');

            $table->unique(['user_id', 'ubication_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_ubication');
    }
};