<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index($id)
    {
        // Encuentra al usuario por ID
        $usuario = User::findorfail($id);

        if (!$usuario) {
            return redirect()->route('/')->with('error', 'Usuario no encontrado');
        }

        $incidencias = $usuario->incidencias;

        return view('crudClientes.index', compact('incidencias')); 
    }
    
}
