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
            <!-- Filtros para Sin Asignar -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="filtroTitulo-sin_asignar" class="form-label">Filtrar por Título:</label>
                    <input type="text" id="filtroTitulo-sin_asignar" class="form-control" placeholder="Buscar por título...">
                </div>
                <div class="col-md-4">
                    <label for="filtroPrioridad-sin_asignar" class="form-label">Filtrar por Prioridad:</label>
                    <select id="filtroPrioridad-sin_asignar" class="form-select">
                        <option value="">Todas</option>
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-secondary" onclick="limpiarFiltros('sin_asignar')">Limpiar Filtros</button>
                </div>
            </div>
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
            <!-- Filtros para Asignadas -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="filtroTitulo-asignada" class="form-label">Filtrar por Título:</label>
                    <input type="text" id="filtroTitulo-asignada" class="form-control" placeholder="Buscar por título...">
                </div>
                <div class="col-md-3">
                    <label for="filtroPrioridad-asignada" class="form-label">Filtrar por Prioridad:</label>
                    <select id="filtroPrioridad-asignada" class="form-select">
                        <option value="">Todas</option>
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroTecnico-asignada" class="form-label">Filtrar por Técnico:</label>
                    <select id="filtroTecnico-asignada" class="form-select">
                        <option value="">Todos</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-secondary" onclick="limpiarFiltros('asignada')">Limpiar Filtros</button>
                </div>
            </div>
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
            <!-- Filtros para En Proceso -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="filtroTitulo-en_proceso" class="form-label">Filtrar por Título:</label>
                    <input type="text" id="filtroTitulo-en_proceso" class="form-control" placeholder="Buscar por título...">
                </div>
                <div class="col-md-3">
                    <label for="filtroPrioridad-en_proceso" class="form-label">Filtrar por Prioridad:</label>
                    <select id="filtroPrioridad-en_proceso" class="form-select">
                        <option value="">Todas</option>
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroTecnico-en_proceso" class="form-label">Filtrar por Técnico:</label>
                    <select id="filtroTecnico-en_proceso" class="form-select">
                        <option value="">Todos</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-secondary" onclick="limpiarFiltros('en_proceso')">Limpiar Filtros</button>
                </div>
            </div>
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
            <!-- Filtros para Resueltas -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="filtroTitulo-resuelta" class="form-label">Filtrar por Título:</label>
                    <input type="text" id="filtroTitulo-resuelta" class="form-control" placeholder="Buscar por título...">
                </div>
                <div class="col-md-3">
                    <label for="filtroPrioridad-resuelta" class="form-label">Filtrar por Prioridad:</label>
                    <select id="filtroPrioridad-resuelta" class="form-select">
                        <option value="">Todas</option>
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroTecnico-resuelta" class="form-label">Filtrar por Técnico:</label>
                    <select id="filtroTecnico-resuelta" class="form-select">
                        <option value="">Todos</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-secondary" onclick="limpiarFiltros('resuelta')">Limpiar Filtros</button>
                </div>
            </div>
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
            <!-- Filtros para Cerradas -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="filtroTitulo-cerrada" class="form-label">Filtrar por Título:</label>
                    <input type="text" id="filtroTitulo-cerrada" class="form-control" placeholder="Buscar por título...">
                </div>
                <div class="col-md-3">
                    <label for="filtroPrioridad-cerrada" class="form-label">Filtrar por Prioridad:</label>
                    <select id="filtroPrioridad-cerrada" class="form-select">
                        <option value="">Todas</option>
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroTecnico-cerrada" class="form-label">Filtrar por Técnico:</label>
                    <select id="filtroTecnico-cerrada" class="form-select">
                        <option value="">Todos</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-secondary" onclick="limpiarFiltros('cerrada')">Limpiar Filtros</button>
                </div>
            </div>
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