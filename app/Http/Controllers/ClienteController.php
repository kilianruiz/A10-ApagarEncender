<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Incidencia;

class ClienteController extends Controller
{
    public function index($userId) // Recibe el ID del usuario
    {
        // Obtener las incidencias asociadas al usuario con el id proporcionado
        $incidencias = Incidencia::where('user_id', $userId)->get();

        // Pasar las incidencias a la vista
        return view('crudClientes.index', compact('incidencias'));
    }
    // En ClienteController.php
    public function show($id)
    {
        $incidencia = Incidencia::find($id);  // Si usas Eloquent, encuentra la incidencia por ID
        
        if (!$incidencia) {
            return response()->json(['error' => 'Incidencia no encontrada'], 404);
        }
    
        return response()->json($incidencia);
    }
    public function getIncidencias()
    {
        // Obtener todas las incidencias (puedes agregar filtros si es necesario)
        $incidencias = Incidencia::all();

        return response()->json($incidencias);
    }


}

