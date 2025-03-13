@extends('../layouts.layout')

@section('title', 'Mis Incidencias')

@section('content')
<!-- Asegúrate de incluir Bootstrap en el archivo layout principal -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Navbar de Bootstrap -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <!-- Coloca el nombre de usuario a la izquierda -->
    <span class="navbar-text">
      Bienvenido, {{ Auth::user()->name }}
    </span>

    <!-- El ícono de logout a la derecha -->
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
      @csrf
      <button type="submit" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i>
      </button>
    </form>
  </div>
</nav>

<!-- Resto del contenido de "Mis Incidencias" -->
<div class="container">
    <h1>Mis Incidencias</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <button id="btn-crear" name="btn-crear" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createIncidenciaModal">Crear</button>

    <ul class="nav nav-tabs" id="incidenciasTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="todas-tab" data-bs-toggle="tab" data-bs-target="#todas" type="button" role="tab" aria-controls="todas" aria-selected="true" data-status="todas">Todas</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sin-asignar-tab" data-bs-toggle="tab" data-bs-target="#sin-asignar" type="button" role="tab" aria-controls="sin-asignar" aria-selected="false" data-status="sin_asignar">Sin Asignar</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="asignadas-tab" data-bs-toggle="tab" data-bs-target="#asignadas" type="button" role="tab" aria-controls="asignadas" aria-selected="false" data-status="asignadas">Asignadas</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="en-proceso-tab" data-bs-toggle="tab" data-bs-target="#en-proceso" type="button" role="tab" aria-controls="en-proceso" aria-selected="false" data-status="en_proceso">En Proceso</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="resueltas-tab" data-bs-toggle="tab" data-bs-target="#resueltas" type="button" role="tab" aria-controls="resueltas" aria-selected="false" data-status="resueltas">Resueltas</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="cerradas-tab" data-bs-toggle="tab" data-bs-target="#cerradas" type="button" role="tab" aria-controls="cerradas" aria-selected="false" data-status="cerradas">Cerradas</button>
        </li>
    </ul>
    <table class="table table-striped" id="incidenciasTable">
        <thead>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Imagen</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha de creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Las filas de la tabla se cargarán aquí con AJAX -->
        </tbody>
    </table>
</div>

<!-- Modal para ver la incidencia en grande -->
<div class="modal fade" id="viewIncidenciaModal" tabindex="-1" role="dialog" aria-labelledby="viewIncidenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewIncidenciaModalLabel">Detalles de la Incidencia</h5>
            </div>
            <div class="modal-body" id="incidenciaDetails">
                <!-- Los detalles de la incidencia se cargarán aquí con JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar la incidencia -->
<div class="modal fade" id="editIncidenciaModal" tabindex="-1" role="dialog" aria-labelledby="editIncidenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editIncidenciaModalLabel">Editar Incidencia</h5>
            </div>
            <div class="modal-body">
                <form id="editIncidenciaForm">
                    <div class="mb-3">
                        <label for="editTitulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="editTitulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="editDescripcion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editPrioridad" class="form-label">Prioridad</label>
                        <select class="form-select" id="editPrioridad" required>
                            <option value="alta">Alta</option>
                            <option value="media">Media</option>
                            <option value="baja">Baja</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear la incidencia -->
<div class="modal fade" id="createIncidenciaModal" tabindex="-1" aria-labelledby="createIncidenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createIncidenciaModalLabel">Crear Nueva Incidencia</h5>
            </div>
            <div class="modal-body">
                <!-- Formulario para crear una incidencia -->
                <form id="createIncidenciaForm" method="POST" action="{{ route('incidencias.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="createTitulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="createTitulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="createDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="createDescripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="createPrioridad" class="form-label">Prioridad</label>
                        <select class="form-select" id="createPrioridad" name="prioridad" required>
                            <option value="alta">Alta</option>
                            <option value="media">Media</option>
                            <option value="baja">Baja</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="createImagen" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="createImagen" name="imagen" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Incidencia</button>
                </form>
                
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/crudClientes.js') }}"></script>
@endsection