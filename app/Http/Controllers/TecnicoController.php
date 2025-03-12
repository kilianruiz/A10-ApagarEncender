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
        return view('/crudTecnico.index');
    }

    public function getComentarios() {
        $comentarios = IncidenciaUsuario::where('user_id', Auth::id())
            ->whereHas('incidencia', function($query) {
                $query->where('estado', '!=', 'resuelta');
            })
            ->with('incidencia')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($comentarios);
    }

    public function getHistorial()
    {
        $historial = IncidenciaUsuario::where('user_id', Auth::id())
            ->whereHas('incidencia', function($query) {
                $query->where('estado', 'resuelta');
            })
            ->with('incidencia')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($historial);
    }

    public function resolverIncidencia(Request $request)
    {
        try {
            $incidencia = Incidencia::findOrFail($request->incidencia_id);
            $incidencia->estado = 'resuelta';
            $incidencia->feedback = $request->feedback;
            $incidencia->save();

            return response()->json([
                'success' => true,
                'message' => 'Incidencia marcada como resuelta'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al resolver la incidencia'
            ], 500);
        }
    }

    public function cambiarEstado(Request $request)
    {
        try {
            $request->validate([
                'incidencia_id' => 'required|exists:incidencias,id',
                'estado' => 'required|in:sin_asignar,asignada,en proceso,resuelta'
            ]);

            $incidencia = Incidencia::findOrFail($request->incidencia_id);
            $incidencia->estado = $request->estado;
            $incidencia->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado'
            ], 500);
        }
    }
}
