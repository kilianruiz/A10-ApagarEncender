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
            $table->enum('estado', ['sin_asignar', 'asignada', 'en_proceso', 'resuelta', 'cerrada'])->default('sin_asignar'); 
            $table->enum('prioridad', ['alta', 'media', 'baja'])->default('media'); 
            $table->foreignId('user_id')->constrained('users'); 
            $table->foreignId('sede_id')->constrained('sedes'); 
            $table->text('imagen');
            $table->foreignId('categoria_id')->constrained('categorias'); 
            $table->foreignId('subcategoria_id')->constrained('subcategorias');
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
