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
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->text('comentario')->nullable();
            $table->enum('estado', ['sin asignar', 'asignada', 'en proceso', 'resuelta', 'cerrada'])->default('sin asignar'); 
            $table->enum('prioridad', ['alta', 'media', 'baja'])->default('media'); 
            $table->foreignId('user_id')->constrained('users'); 
            $table->foreignId('sede_id')->constrained('sedes'); 
            $table->text('imagen')->nullable();
            $table->foreignId('subcategoria_id')->constrained('subcategorias');
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
