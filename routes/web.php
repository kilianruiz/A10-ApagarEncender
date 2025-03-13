<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenciasController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\ClienteController;

// Ruta principal
Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta para mostrar el formulario de login
Route::get('/login', function () {
    return view('index');
})->name('login');

// rutas proceso login 
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// rutas securizadas
Route::middleware(['auth'])->group(function () {
    //rutas admin
    Route::controller(AdminUserController::class)->group(function () {
        Route::get('/admin', [AdminUserController::class, 'index'])->name('crudAdmin.index');
        Route::get('/admin/create', [AdminUserController::class, 'create'])->name('crudAdmin.create');
        Route::post('/admin', [AdminUserController::class, 'store'])->name('crudAdmin.store');
        Route::get('/admin/{id}/edit', [AdminUserController::class, 'edit'])->name('crudAdmin.edit');
        Route::put('/admin/users/{id}', [AdminUserController::class, 'update'])->name('crudAdmin.update');
        Route::delete('/admin/{id}', [AdminUserController::class, 'destroy'])->name('crudAdmin.destroy');
        Route::resource('admin/users', AdminUserController::class)->except(['show', 'create', 'edit']);
    });

    // rutas incidencias
    Route::controller(IncidenciasController::class)->group(function () {
        // Ruta incidencias
        Route::get('/gestor', [IncidenciasController::class, 'index']);
        Route::get('/gestor/incidencias', [IncidenciasController::class, 'getByStatus'])->name('gestor.getByStatus');

        Route::get('/incidencias/crear', [IncidenciasController::class, 'crear']);
        Route::get('/incidencias/{id}', [IncidenciasController::class, 'ver'])->name('incidencias.ver');
    
    });

    //rutas tecnicos
    Route::controller(TecnicoController::class)->group(function () {
        Route::get('/tecnicos', [TecnicoController::class, 'index'])->name('crudTecnico');
    });
    //rutas clientes
    Route::controller(ClienteController::class)->group(function () {
        Route::get('/clientes/{id}', [ClienteController::class, 'index'])->name('crudClientes');
        Route::get('/incidencia/{id}', [ClienteController::class, 'show'])->name('incidencias.show');
        Route::get('/incidencias', [ClienteController::class, 'getIncidencias'])->name('incidencias.index');
        Route::post('/incidencias/crear', [ClienteController::class, 'store'])->name('incidencias.store');
        Route::put('/incidencia/{id}', [ClienteController::class, 'update'])->name('incidencias.update');
    });
});