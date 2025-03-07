<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenciasController;
use App\Http\Controllers\AdminUserController;
Route::get('/', function () {
    return view('index');
});
//rutas admin
Route::get('/admin', [AdminUserController::class, 'index'])->name('crudAdmin.index');
Route::get('/admin/create', [AdminUserController::class, 'create'])->name('crudAdmin.create');
Route::post('/admin', [AdminUserController::class, 'store'])->name('crudAdmin.store');
Route::get('/admin/{id}/edit', [AdminUserController::class, 'edit'])->name('crudAdmin.edit');
Route::put('/admin/{id}', [AdminUserController::class, 'update'])->name('crudAdmin.update');
Route::delete('/admin/{id}', [AdminUserController::class, 'destroy'])->name('crudAdmin.destroy');
Route::resource('admin/users', AdminUserController::class)->except(['show', 'create', 'edit']);

// Ruta incidencias
Route::get('/incidencias', [IncidenciasController::class, 'index']);
Route::get('/incidencias/crear', [IncidenciasController::class, 'crear']);
Route::get('/incidencias/{id}', [IncidenciasController::class, 'ver'])->name('incidencias.ver');