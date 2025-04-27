@extends('layouts.layout')

@section('title', 'Dashboard Técnico')

@section('content')
<div class="container mt-4">
    <div class="user-header mb-4 d-flex justify-content-between align-items-center">
        <div class="user-info">
            <h4 class="mb-0">Bienvenido, {{ Auth::user()->name }}</h4>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Cerrar Sesión</button>
        </form>
    </div>

    <!-- Pestañas -->
    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes" type="button" role="tab">Pendientes</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="en-proceso-tab" data-bs-toggle="tab" data-bs-target="#en-proceso" type="button" role="tab">En proceso</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="resueltas-tab" data-bs-toggle="tab" data-bs-target="#resueltas" type="button" role="tab">Resueltas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="cerradas-tab" data-bs-toggle="tab" data-bs-target="#cerradas" type="button" role="tab">Cerradas</button>
        </li>
    </ul>

    <!-- Contenido de pestañas -->
    <div class="tab-content" id="myTabContent">
        <!-- Pendientes -->
        <div class="tab-pane fade show active" id="pendientes" role="tabpanel">
            <h2>Incidencias Pendientes</h2>
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
                <tbody id="pendientes-body"></tbody>
            </table>
            <div id="no-pendientes" class="alert alert-info d-none">No hay incidencias pendientes.</div>
        </div>

        <!-- En Proceso -->
        <div class="tab-pane fade" id="en-proceso" role="tabpanel">
            <h2>Incidencias En Proceso</h2>
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
                <tbody id="en-proceso-body"></tbody>
            </table>
            <div id="no-en-proceso" class="alert alert-info d-none">No hay incidencias en proceso.</div>
        </div>

        <!-- Resueltas -->
        <div class="tab-pane fade" id="resueltas" role="tabpanel">
            <h2>Incidencias Resueltas</h2>
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
                <tbody id="resueltas-body"></tbody>
            </table>
            <div id="no-resueltas" class="alert alert-info d-none">No hay incidencias resueltas.</div>
        </div>

        <!-- Cerradas -->
        <div class="tab-pane fade" id="cerradas" role="tabpanel">
            <h2>Incidencias Cerradas</h2>
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
                <tbody id="cerradas-body"></tbody>
            </table>
            <div id="no-cerradas" class="alert alert-info d-none">No hay incidencias cerradas.</div>
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