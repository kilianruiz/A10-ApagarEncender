@extends('../layouts.layout')

@section('title', 'Mis Incidencias')

@section('content')
    <div class="container">
        <h1>Mis Incidencias</h1>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <button id="btn-crear" name="btn-crear" class="btn btn-primary mb-3">Crear</button>
        <ul class="nav nav-tabs" id="incidenciasTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-status="sin asignar" data-bs-toggle="tab" data-bs-target="#sinAsignar" type="button">Sin Asignar</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-status="asignadas" data-bs-toggle="tab" data-bs-target="#asignadas" type="button">Asignadas</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-status="en proceso" data-bs-toggle="tab" data-bs-target="#enProceso" type="button">En Proceso</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-status="resueltas" data-bs-toggle="tab" data-bs-target="#resueltas" type="button">Resueltas</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-status="cerradas" data-bs-toggle="tab" data-bs-target="#cerradas" type="button">Cerradas</button>
            </li>
        </ul>
        <table class="table table-striped" id="incidenciasTable">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
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
@endsection

@section('scripts')
    <script src="{{ asset('js/crudClientes.js') }}"></script>
@endsection
