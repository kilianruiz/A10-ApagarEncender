@extends('layouts.layout')

@section('title', 'Dashboard Técnico')

@section('content')
<div class="container mt-4">
    <!-- Información del usuario y logout -->
    <div class="user-header mb-4 d-flex justify-content-between align-items-center">
        <div class="user-info">
            <h4 class="mb-0">Bienvenido, {{ Auth::user()->name }}</h4>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Cerrar Sesión</button>
        </form>
    </div>

    <!-- Pestañas de navegación -->
    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" 
                    data-bs-target="#pendientes" type="button" role="tab">
                Incidencias Pendientes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="historial-tab" data-bs-toggle="tab" 
                    data-bs-target="#historial" type="button" role="tab">
                Incidencias Resueltas
            </button>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="myTabContent">
        <!-- Pestaña de Incidencias Pendientes -->
        <div class="tab-pane fade show active" id="pendientes" role="tabpanel">
            <h2>Comentarios de Incidencias Pendientes</h2>
            
            <!-- Agregar filtros -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="filtroEstado" class="form-label">Filtrar por Estado:</label>
                    <select id="filtroEstado" class="form-select">
                        <option value="">Todos</option>
                        <option value="asignada">Asignada</option>
                        <option value="en proceso">En proceso</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filtroFecha" class="form-label">Filtrar hasta fecha:</label>
                    <input type="date" id="filtroFecha" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-secondary" onclick="limpiarFiltros()">Limpiar Filtros</button>
                </div>
            </div>

            <div id="tabla-comentarios">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Comentario</th>
                            <th>Imagen</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="comentarios-body">
                    </tbody>
                </table>
                <div id="no-comentarios" class="alert alert-info d-none">
                    No hay incidencias pendientes.
                </div>
            </div>
        </div>

        <!-- Pestaña de Historial -->
        <div class="tab-pane fade" id="historial" role="tabpanel">
            <h2>Historial de Incidencias Resueltas</h2>
            <div id="tabla-historial">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Comentario</th>
                            <th>Feedback</th>
                            <th>Fecha Resolución</th>
                        </tr>
                    </thead>
                    <tbody id="historial-body">
                    </tbody>
                </table>
                <div id="no-historial" class="alert alert-info d-none">
                    No hay incidencias resueltas.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/crudTecnico.js') }}"></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/tecnicos.css') }}">
@endsection