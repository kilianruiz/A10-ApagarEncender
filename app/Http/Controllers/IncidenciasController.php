<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Models\Incidencia;
use App\Models\User;
use Illuminate\Http\Request;

class IncidenciasController extends Controller
{
    // Crud de incidencias del Gestor
    public function index()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();

        // Si el usuario es admin (sede_id null), mostrar todas las incidencias
        if ($user->sede_id === null) {
            $sin_asignar = Incidencia::where('estado', 'sin asignar')->get();
            $asignadas = Incidencia::whereIn('estado', ['asignada', 'en proceso'])->get();
            $resueltas = Incidencia::whereIn('estado', 'resuelta')->get();
        } else {
            // Si el usuario pertenece a una sede específica, filtrar por su sede_id
            $sin_asignar = Incidencia::where('estado', 'sin asignar')
                                    ->where('sede_id', $user->sede_id)
                                    ->get();

            $asignadas = Incidencia::whereIn('estado', ['asignada', 'en proceso'])
                                    ->where('sede_id', $user->sede_id)
                                    ->get();
            
            $resueltas = Incidencia::where('estado', 'resuelta')
                                    ->where('sede_id', $user->sede_id)
                                    ->get();
        }

        return view('crudGestor.index', compact('sin_asignar', 'asignadas', 'resueltas'));
    }

    // En tu controlador
    public function getByStatus(Request $request)
    {
        // Obtiene el estado desde la solicitud
        $estado = $request->input('estado');

        // Verifica si se pasó un estado válido
        if (!$estado) {
            return response()->json(['error' => 'Estado no proporcionado'], 400);
        }

        // Filtra las incidencias por estado
        $incidencias = Incidencia::where('estado', $estado)->get();

        // Devuelve las incidencias en formato JSON
        return response()->json($incidencias);
    }

    // Método para obtener una incidencia específica
    public function show($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        return response()->json($incidencia);
    }

    // Método para actualizar una incidencia
    public function update(Request $request, $id)
    {
        try {
            $incidencia = Incidencia::findOrFail($id);
            
            // Validar los datos recibidos
            $request->validate([
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'prioridad' => 'required|in:alta,media,baja'
            ]);

            // Actualizar la incidencia
            $incidencia->update([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'prioridad' => $request->prioridad
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Incidencia actualizada correctamente',
                'data' => $incidencia
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la incidencia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Pagina para asignar incidencias
    // public function crear()
    // {
    //     return view('crear_incidencia');
    // }

    // Pagina para ver en detalle una incidencia
    // public function ver($id)
    // {
    //     // Buscar la incidencia por su ID
    //     $id_incidencia = Incidencia::findOrFail($id);

    //     // Pasar la incidencia a la vista
    //     return view('ver_incidencia', ['incidencia' => $id_incidencia]);
    // }
}
