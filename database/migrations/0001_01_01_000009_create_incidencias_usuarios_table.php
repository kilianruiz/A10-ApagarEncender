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
        Schema::create('incidencia_usuario', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('comentario');
            $table->text('imagen');
            $table->foreignId('user_id')->constrained('users'); // Relación 1 usuario N incidencia_usuario
            $table->foreignId('incidencia_id')->constrained('incidencias'); // Relación 1 incidencia N incidencia_usuario
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias_usuarios');
    }
};
