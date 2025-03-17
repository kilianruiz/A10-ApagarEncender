@extends('../layouts.layout')


@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('styles/admin.css') }}">

<div class="container">
        <div class="user-header mb-4 d-flex justify-content-between align-items-center">
            <div class="user-info">
                <h4 class="mb-0">Bienvenido, {{ Auth::user()->name }}</h4>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-box-arrow-right"></i></button>    
            </form>
       </div>    
      <div class="mb-3 d-flex flex-row gap-3 align-items-center" style="width: 80%;">
        <input type="text" id="filter" placeholder="Buscar usuarios por nombre..." class="form-control">
        <input type="text" id="filterEmail" placeholder="Buscar usuarios por email..." class="form-control">
        <select id="filterRole" class="form-select">
            <option value="">Filtrar por rol</option>
            @foreach($roles as $role)
                @if($role->id !== 1)
                    <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                @endif
            @endforeach
        </select>
        <select id="filterSede" class="form-select">
            <option value="">Filtrar por sede</option>
            @foreach($sedes as $sede)
                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
            @endforeach
        </select>
        <button id="clearFilters" class="btn"><i class="fas fa-broom"></i></button>
    </div>
    <button id="openUserModal" name="btn-crear" class="btn btn-crear mb-3">Crear Usuario</button>
    <table class="table text-center">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Sede</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="userTable">
            <!-- Los usuarios se cargarán aquí mediante AJAX -->
        </tbody>
    </table>
    <!-- Contenedor para los controles de paginación -->
    <div id="paginationControls" class="mt-3"></div>

<!-- Modal para crear usuario -->
<div id="createUserModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Usuario</h5>
            </div>
            <div class="modal-body">
                <form id="createUserForm">
                    <div class="form-group">
                        <label for="createName">Nombre</label>
                        <input type="text" id="createName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="createEmail">Email</label>
                        <input type="email" id="createEmail" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="createPassword">Contraseña</label>
                        <input type="password" id="createPassword" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="createPasswordConfirmation">Confirmar Contraseña</label>
                        <input type="password" id="createPasswordConfirmation" name="password_confirmation" class="form-control" required>
                    </div>
                    <label for="role_id">Rol</label>
                        <select id="role_id" name="role_id" class="form-control" required>
                            <option value="">Seleccione un rol</option>
                            @foreach($roles as $role)
                            @if($role->id !== 1)
                                <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                            @endif
                            @endforeach
                        </select>
                        
                    <label for="sede_id">Sede</label>
                        <select id="sede_id" name="sede_id" class="form-control" required>
                            <option value="">Seleccione una sede</option>
                            @foreach($sedes as $sede)
                                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                            @endforeach
                        </select>
                    <button type="submit" class="btn-crear btn mt-3">Crear</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar usuario -->
<div id="editUserModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editId" name="id">
                    <div class="form-group">
                        <label for="editName">Nombre</label>
                        <input type="text" id="editName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" id="editEmail" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editPassword">Contraseña</label>
                        <input type="password" id="editPassword" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editPasswordConfirmation">Confirmar Contraseña</label>
                        <input type="password" id="editPasswordConfirmation" name="password_confirmation" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editRoleId">Rol</label>
                        <select id="editRoleId" name="role_id" class="form-control" required>
                            <option value="">Seleccione un rol</option>
                            @foreach($roles as $role)
                            @if($role->id !== 1)
                                <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editSedeId">Sede</label>
                        <select id="editSedeId" name="sede_id" class="form-control" required>
                            <option value="">Seleccione una sede</option>
                            @foreach($sedes as $sede)
                                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-crear btn mt-3">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="incidenciasModal" tabindex="-1" aria-labelledby="incidenciasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="incidenciasModalLabel">Incidencias del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="incidenciasList" class="list-group">
                    <!-- Incidencias se cargarán aquí -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/crudAdmin.js') }}"></script>
<script src="{{ asset('js/validacionesAdmin.js') }}"></script>
@endsection