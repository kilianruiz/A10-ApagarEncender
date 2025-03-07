<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenciasController;

Route::get('/', function () {
    return view('index');
});

// Ruta incidencias
Route::get('/incidencias', [IncidenciasController::class, 'index']);
Route::get('/incidencias/crear', [IncidenciasController::class, 'crear']);
Route::get('/incidencias/{id}', [IncidenciasController::class, 'ver'])->name('incidencias.ver');