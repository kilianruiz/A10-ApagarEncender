<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncidenciasController extends Controller
{
    // Método para mostrar las incidencias en el gestor
    public function index($nombre_sede)
    {
        $user = Auth::user();
        $sede = $user->sede()->where('localización', $nombre_sede)->first();

        if (!$sede) {
            return abort(404, 'Sede no encontrada');
        }

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

        return view('crudGestor.index', compact('sin_asignar', 'asignadas', 'resueltas', 'cerradas', 'sede', 'user'));
    }

    // Obtener incidencias por estado a través de AJAX
    public function getByStatus(Request $request)
    {
        $estado = str_replace("_", " ", $request->input('estado'));

        if (!$estado) {
            return response()->json(['error' => 'Estado no proporcionado'], 400);
        }

        try {
            $user = Auth::user();
            $sede_id = $user->sede_id;

            // Obtener las incidencias con sus relaciones
            $query = Incidencia::with(['user', 'categoria', 'subcategoria'])
                            ->with(['tecnicoAsignado' => function($query) {
                                $query->select('users.id', 'users.name');
                            }])
                            ->where('estado', $estado);

            if ($sede_id !== null) {
                $query->where('sede_id', $sede_id);
            }

            $incidencias = $query->get();

            return response()->json($incidencias);
        } catch (\Exception $e) {
            \Log::error('Error en getByStatus:', [
                'error' => $e->getMessage(),
                'estado' => $estado
            ]);
            return response()->json(['error' => 'Error al obtener incidencias: ' . $e->getMessage()], 500);
        }
    }

    // Asignar incidencia
    public function asignarIncidencia(Request $request)
    {
        // Validación de datos
        $request->validate([
            'incidencia_id' => 'required|exists:incidencias,id',
            'tecnico_id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            // 1. Obtener la incidencia
            $incidencia = Incidencia::findOrFail($request->incidencia_id);

            // 2. Actualizar el estado de la incidencia
            $incidencia->update([
                'estado' => 'asignada'
            ]);

            // 3. Insertar en la tabla incidencia_usuario
            DB::table('incidencia_usuario')->insert([
                'titulo' => $incidencia->titulo,
                'comentario' => 'Incidencia asignada al técnico',
                'imagen' => $incidencia->imagen ?? '',
                'user_id' => $request->tecnico_id,
                'incidencia_id' => $request->incidencia_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return response()->json(['message' => 'Incidencia asignada correctamente']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al asignar incidencia:', [
                'error' => $e->getMessage(),
                'incidencia_id' => $request->incidencia_id,
                'tecnico_id' => $request->tecnico_id,
            ]);

            return response()->json(['error' => 'Ocurrió un error al asignar la incidencia'], 500);
        }
    }

    // Obtener técnicos disponibles
    public function obtenerTecnicos()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $tecnicos = \App\Models\User::where('role', 'tecnico')->get();
        } else {
            $tecnicos = \App\Models\User::where('jefe_id', $user->id)->get();
        }

        if ($tecnicos->isEmpty()) {
            return response()->json(['error' => 'No tienes técnicos asignados'], 404);
        }

        return response()->json($tecnicos);
    }
}