<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\IncidenciaUsuario;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class TecnicoController extends Controller
{
    public function index(){
        // Cojo el usuario logueado con su id para mostrar la incidencia que se le ha asignado
        $comentarios = IncidenciaUsuario::where('user_id', Auth::id())
            ->with('incidencia')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('/crudTecnico.index', compact('comentarios'));
    }
}
