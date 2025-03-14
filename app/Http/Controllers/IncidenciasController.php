<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidenciasController extends Controller
{
    // Método para mostrar las incidencias en el gestor
    public function index($nombre_sede)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Buscar la sede por nombre
        $sede = $user->sede()->where('localización', $nombre_sede)->first();

        if (!$sede) {
            return abort(404, 'Sede no encontrada');
        }

        // Obtener las incidencias filtradas por la sede del usuario
        $sin_asignar = Incidencia::where('estado', 'sin asignar')
                                ->where('sede_id', $sede->id)
                                ->get();

        $asignadas = Incidencia::whereIn('estado', ['asignada', 'en proceso'])
                                ->where('sede_id', $sede->id)
                                ->get();
        
        $resueltas = Incidencia::where('estado', 'resuelta')
                                ->where('sede_id', $sede->id)
                                ->get();

        $cerradas = Incidencia::where('estado', 'cerrada')
                                ->where('sede_id', $sede->id)
                                ->get();

        // Retornar la vista con las incidencias filtradas
        return view('crudGestor.index', compact('sin_asignar', 'asignadas', 'resueltas', 'cerradas', 'sede','user'));
    }

    // Obtener incidencias por estado a través de AJAX
    public function getByStatus(Request $request)
    {
        $estado = str_replace("_", " ", $request->input('estado')); // Reemplazar "_" por espacios
    
        if (!$estado) {
            return response()->json(['error' => 'Estado no proporcionado'], 400);
        }
    
        $user = Auth::user(); // Obtener el usuario autenticado
        $sede_id = $user->sede_id;
    
        // Buscar las incidencias, uniendo la relación de usuario, categoría y subcategoría
        if ($sede_id === null) {
            // Admin puede ver todas las incidencias de todas las sedes
            $incidencias = Incidencia::with(['user', 'categoria', 'subcategoria'])
                                    ->where('estado', $estado)
                                    ->get();
        } else {
            // Filtrar las incidencias por sede_id del usuario autenticado
            $incidencias = Incidencia::with(['user', 'categoria', 'subcategoria'])
                                    ->where('estado', $estado)
                                    ->where('sede_id', $sede_id)
                                    ->get();
        }
    
        return response()->json($incidencias);
    }

    public function asignarIncidencia(Request $request)
    {
        $request->validate([
            'incidencia_id' => 'required|exists:incidencias,id',
            'tecnico_id' => 'required|exists:users,id'
        ]);

        $user = Auth::user();

        // Si el usuario es un administrador, puede asignar a cualquier técnico
        if ($user->role === 'admin') {
            $tecnico = User::find($request->tecnico_id);
        } else {
            // Verificar si el técnico pertenece al jefe autenticado
            $tecnico = User::where('id', $request->tecnico_id)
                           ->where('jefe_id', $user->id)
                           ->first();
        }

        if (!$tecnico) {
            return response()->json(['error' => 'No puedes asignar a este técnico'], 403);
        }

        // Asignar la incidencia
        $incidencia = Incidencia::findOrFail($request->incidencia_id);
        $incidencia->user_id = $tecnico->id;
        $incidencia->estado = 'asignada';
        $incidencia->save();

        return response()->json(['message' => 'Incidencia asignada correctamente']);
    }

    public function obtenerTecnicos()
    {
        $user = Auth::user(); // Obtener el usuario autenticado

        if ($user->role === 'admin') {
            $tecnicos = \App\Models\User::where('role', 'tecnico')->get(); // Obtener todos los técnicos
        } else {
            $tecnicos = \App\Models\User::where('jefe_id', $user->id)->get(); // Solo los técnicos del jefe
        }

        if ($tecnicos->isEmpty()) {
            return response()->json(['error' => 'No tienes técnicos asignados'], 404);
        }

        return response()->json($tecnicos);
    }

}