<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('dismissed_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('type');  // 'value' o 'battery'
            $table->string('sector');
            $table->date('alert_date');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('data_id'); // Agregar este campo
            $table->string('name'); // Agregar este campo

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('data_id')->references('id')->on('data'); // Asegúrate de que la tabla 'data' exista
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dismissed_alerts');
    }
};
