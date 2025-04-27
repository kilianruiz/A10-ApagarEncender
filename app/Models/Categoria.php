<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $fillable = ['nombre_categoria'];

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class);
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }

    // Accessor para mantener compatibilidad
    public function getNombreAttribute()
    {
        return $this->nombre_categoria;
    }
}
