<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{

    // Definir el nombre de la tabla en caso de que no sea pluralizado automáticamente.
    protected $table = 'mensajes';

    // Si quieres permitir asignación masiva de ciertas columnas
    protected $fillable = [
        'mensaje', 
        'id_usuario_emisor', 
        'id_usuario_receptor', 
        'incidencia_id'
    ];

    /**
     * Relación con el modelo User (emisor del mensaje)
     */
    public function emisor()
    {
        return $this->belongsTo(User::class, 'id_usuario_emisor', 'user_id');
    }

    /**
     * Relación con el modelo User (receptor del mensaje)
     */
    public function receptor()
    {
        return $this->belongsTo(User::class, 'id_usuario_receptor', 'user_id');
    }

    /**
     * Relación con el modelo Incidencia
     */
    public function incidencia()
    {
        return $this->belongsTo(Incidencia::class, 'incidencia_id');
    }

    // Configurar las fechas para los campos created_at y updated_at si es necesario
    protected $dates = [
        'created_at', 
        'updated_at'
    ];
}
