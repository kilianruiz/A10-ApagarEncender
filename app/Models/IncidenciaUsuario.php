<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidenciaUsuario extends Model
{
    protected $table = 'incidencia_usuario';

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
}
