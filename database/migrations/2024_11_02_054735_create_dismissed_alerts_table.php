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
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            // Índice único para evitar duplicados
            $table->unique(['type', 'sector', 'alert_date', 'user_id'], 'unique_alert_dismissal');
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
