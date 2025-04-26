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
        $sede = \App\Models\Sede::where('localización', $nombre_sede)->first();
    
        if (!$sede || $sede->id !== $user->sede_id) {
            return abort(404, 'Sede no encontrada o no pertenece a tu usuario');
        }
    
        $sin_asignar = Incidencia::where('estado', 'sin asignar')
                                ->where('sede_id', $sede->id)
                                ->get();
    
        $asignadas = Incidencia::where('estado', 'asignada')
                                ->where('sede_id', $sede->id)
                                ->get();
    
        $en_proceso = Incidencia::where('estado', 'en proceso')
                                ->where('sede_id', $sede->id)
                                ->get();
    
        $resueltas = Incidencia::where('estado', 'resuelta')
                                ->where('sede_id', $sede->id)
                                ->get();
    
        $cerradas = Incidencia::where('estado', 'cerrada')
                                ->where('sede_id', $sede->id)
                                ->get();
    
        return view('crudGestor.index', compact('sin_asignar', 'asignadas', 'en_proceso', 'resueltas', 'cerradas', 'sede', 'user'));
    }

    // Obtener incidencias por estado a través de AJAX
    public function getByStatus(Request $request)
    {
        try {
            $estado = str_replace('_', ' ', $request->query('estado'));
            $titulo = $request->query('titulo');
            $prioridad = $request->query('prioridad');
            $tecnico_id = $request->query('tecnico_id');

            $user = auth()->user();
            $sede_id = $user ? $user->sede_id : null;

            $query = \App\Models\Incidencia::query()
                ->with(['user', 'usuarios', 'categoria', 'subcategoria'])
                ->where('estado', $estado);

            if ($sede_id) {
                $query->where('sede_id', $sede_id);
            }

            if ($titulo) {
                $query->where('titulo', 'like', "%{$titulo}%");
            }

            if ($prioridad) {
                $query->where('prioridad', $prioridad);
            }

            if ($tecnico_id) {
                $query->whereHas('usuarios', function ($q) use ($tecnico_id) {
                    $q->where('users.id', $tecnico_id);
                });
            }

            $incidencias = $query->get();

            return response()->json($incidencias);
        } catch (\Exception $e) {
            \Log::error('Error en getByStatus', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            return response()->json(['error' => 'Error al obtener incidencias'], 500);
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
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([], 200);
            }

            if ($user->role && $user->role->nombre === 'admin') {
                $tecnicos = \App\Models\User::whereHas('role', function ($query) {
                    $query->where('nombre', 'tecnico');
                })->get();
            } elseif ($user->sede_id) {
                $tecnicos = \App\Models\User::whereHas('role', function ($query) {
                        $query->where('nombre', 'tecnico');
                    })
                    ->where('sede_id', $user->sede_id)
                    ->get();
            } else {
                return response()->json([], 200);
            }

            return response()->json($tecnicos, 200);
        } catch (\Exception $e) {
            \Log::error('Error en obtenerTecnicos:', [
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);
            return response()->json([], 200);
        }
    }
}