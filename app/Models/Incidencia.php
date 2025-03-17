<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{

    protected $fillable = [
        'titulo', 
        'descripcion', 
        'comentario', 
        'estado', 
        'prioridad', 
        'user_id', 
        'sede_id', 
        'imagen', 
        'subcategoria_id',
        'feedback'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'incidencia_usuario');
    }
    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'incidencia_id');
    }

}