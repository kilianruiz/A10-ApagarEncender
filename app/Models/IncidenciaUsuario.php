<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidenciaUsuario extends Model
{
    use HasFactory;

    // Nombre de la tabla asociada al modelo
    protected $table = 'incidencia_usuario';

    // Campos que se pueden llenar mediante asignación masiva
    protected $fillable = [
        'titulo',
        'comentario',
        'imagen',
        'user_id',
        'incidencia_id'
    ];

    public function incidencia()
    {
        return $this->belongsTo(Incidencia::class, 'incidencia_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}