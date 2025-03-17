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
        'feedback',
        'user_id',
        'categoria_id',
        'subcategoria_id',
        'sede_id'
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

    public function tecnicoAsignado()
    {
        return $this->belongsToMany(User::class, 'incidencia_usuario', 'incidencia_id', 'user_id')
                    ->orderBy('incidencia_usuario.created_at', 'desc');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'incidencia_usuario', 'incidencia_id', 'user_id')
                    ->withTimestamps();
    }

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'incidencia_id');
    }

}