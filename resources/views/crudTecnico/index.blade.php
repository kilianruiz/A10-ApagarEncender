@extends('layouts.layout')

@section('title', 'Dashboard Técnico')

@section('content')
<div class="container mt-4">
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