@extends('../layouts.layout')

@section('content')
<div class="container">
    <h1>Gestión de Usuarios</h1>
    <div class="mb-3">
        <input type="text" id="filter" placeholder="Buscar usuarios por nombre..." class="form-control mb-2">
        <input type="text" id="filterEmail" placeholder="Buscar usuarios por email..." class="form-control mb-2">
        <select id="filterRole" class="form-control mb-2">
            <option value="">Filtrar por rol</option>
            @foreach($roles as $role)
                @if($role->id !== 1)
                    <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                @endif
            @endforeach
        </select>
        <select id="filterSede" class="form-control mb-2">
            <option value="">Filtrar por sede</option>
            @foreach($sedes as $sede)
                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
            @endforeach
        </select>
        <button id="clearFilters" class="btn btn-secondary mb-3">Limpiar Filtros</button>
    </div>
    <button id="openUserModal" class="btn btn-primary mb-3">Crear Usuario</button>
    <table class="table">
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
</div>

<!-- Modal para crear usuario -->
<div id="createUserModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                    <button type="submit" class="btn btn-primary">Crear</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                                <option value="{{ $role->id }}">{{ $role->nombre }}</option>
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
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/crudAdmin.js') }}"></script>
@endsection