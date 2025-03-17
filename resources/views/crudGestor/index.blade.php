@extends('layouts.layout')

@section('title', 'Dashboard Gestor')

@section('content')
<div class="container mt-4">
    <!-- Información del usuario y logout -->
    <div class="user-header mb-4 d-flex justify-content-between align-items-center">
        <div class="user-info">
            <h4 class="mb-0">Lista de Incidencias de la sede de {{ $sede->localización }}</h4>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Cerrar Sesión</button>
        </form>
    </div>

    <!-- Pestañas -->
    <ul class="nav nav-tabs mb-4" id="incidenciasTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-status="sin_asignar" data-bs-toggle="tab" data-bs-target="#sinAsignar" type="button">Sin asignar</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="asignada" data-bs-toggle="tab" data-bs-target="#asignada" type="button">Asignadas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="en_proceso" data-bs-toggle="tab" data-bs-target="#enProceso" type="button">En proceso</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="resuelta" data-bs-toggle="tab" data-bs-target="#resuelta" type="button">Resueltas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="cerrada" data-bs-toggle="tab" data-bs-target="#cerrada" type="button">Cerradas</button>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="myTabContent">
        <!-- Pestaña Sin Asignar -->
        <div class="tab-pane fade show active" id="sinAsignar" role="tabpanel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Comentario</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Informador</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Feedback</th>
                            <th>Fecha</th>
                            <th>Técnico</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-sin_asignar"></tbody>
                </table>
            </div>
        </div>

        <!-- Pestaña Asignadas -->
        <div class="tab-pane fade" id="asignada" role="tabpanel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Comentario</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Informador</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Feedback</th>
                            <th>Fecha</th>
                            <th>Técnico</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-asignada"></tbody>
                </table>
            </div>
        </div>

        <!-- Pestaña En Proceso -->
        <div class="tab-pane fade" id="enProceso" role="tabpanel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Comentario</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Informador</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Feedback</th>
                            <th>Fecha</th>
                            <th>Técnico</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-en_proceso"></tbody>
                </table>
            </div>
        </div>

        <!-- Pestaña Resueltas -->
        <div class="tab-pane fade" id="resuelta" role="tabpanel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Comentario</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Informador</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Feedback</th>
                            <th>Fecha</th>
                            <th>Técnico</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-resuelta"></tbody>
                </table>
            </div>
        </div>

        <!-- Pestaña Cerradas -->
        <div class="tab-pane fade" id="cerrada" role="tabpanel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Comentario</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Informador</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Feedback</th>
                            <th>Fecha</th>
                            <th>Técnico</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-cerrada"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Asignación -->
<div id="modal-asignar">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Asignar Técnico</h3>
        </div>
        <div class="modal-body">
            <form id="form-asignar">
                <input type="hidden" id="incidencia-id" name="incidencia_id" />
                <div class="form-group">
                    <label for="tecnico-select">Seleccionar Técnico:</label>
                    <select id="tecnico-select" name="tecnico_id" class="form-select">
                        <option value="">Seleccione un técnico</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-cancelar">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/crudGestor.js') }}" defer></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('styles/incidencias.css') }}">
@endsection