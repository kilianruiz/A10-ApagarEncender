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
                <option value="{{ $role->id }}">{{ $role->nombre }}</option>
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
                                <option value="{{ $role->id }}">{{ $role->nombre }}</option>
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
<script>
$(document).ready(function() {
    // Definir la función ListarProductos en el ámbito global
    window.ListarProductos = function(page = 1) {
        const nombre = $('#filter').val();
        const email = $('#filterEmail').val();
        const role_id = $('#filterRole').val();
        const sede_id = $('#filterSede').val();
        const resultado = document.getElementById('userTable');

        fetch(`/admin/users?nombre=${encodeURIComponent(nombre)}&email=${encodeURIComponent(email)}&role_id=${role_id}&sede_id=${sede_id}&page=${page}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Verifica los datos recibidos
            if (data.usuarios && Array.isArray(data.usuarios)) {
                let tabla = '';
                data.usuarios.forEach(user => {
                    let str = `<tr><td>${user.name}</td>`;
                    str += `<td>${user.email}</td>`;
                    str += `<td>${user.role ? user.role.nombre : 'Sin rol'}</td>`;
                    str += `<td>${user.sede ? user.sede.nombre : 'Sin sede'}</td>`;
                    str += `<td>`;
                    if (user.role_id !== 1) { // Suponiendo que el rol de administrador tiene el ID 1
                        str += `<button type='button' class='btn btn-success' onclick="Editar(${user.id})">Editar</button>`;
                    } else {
                        str += `<button type='button' class='btn btn-success' disabled>Editar</button>`;
                    }
                    str += `<button type='button' class='btn btn-danger' onclick="Eliminar(${user.id})">Eliminar</button>`;
                    str += `</td></tr>`;
                    tabla += str;
                });
                resultado.innerHTML = tabla;

                // Generar elementos de paginación
                let pagination = '<nav aria-label="Page navigation example"><ul class="pagination">';
                if (data.pagination.current_page > 1) {
                    pagination += `<li class="page-item"><a class="page-link" href="#" onclick="ListarProductos(${data.pagination.current_page - 1})">Previous</a></li>`;
                }
                for (let i = 1; i <= data.pagination.last_page; i++) {
                    pagination += `<li class="page-item ${i === data.pagination.current_page ? 'active' : ''}"><a class="page-link" href="#" onclick="ListarProductos(${i})">${i}</a></li>`;
                }
                if (data.pagination.current_page < data.pagination.last_page) {
                    pagination += `<li class="page-item"><a class="page-link" href="#" onclick="ListarProductos(${data.pagination.current_page + 1})">Next</a></li>`;
                }
                pagination += '</ul></nav>';
                document.getElementById('paginationControls').innerHTML = pagination;
            } else {
                resultado.innerHTML = '<tr><td colspan="5">No se encontraron usuarios.</td></tr>';
            }
        })
    };

    // Inicializar la lista de productos al cargar la página
    ListarProductos();

    // Escuchar el evento keyup para el filtro de búsqueda por nombre
    $('#filter').on('keyup', function() {
        ListarProductos();
    });

    // Escuchar el evento keyup para el filtro de búsqueda por email
    $('#filterEmail').on('keyup', function() {
        ListarProductos();
    });

    // Escuchar el cambio en el filtro de rol
    $('#filterRole').on('change', function() {
        ListarProductos();
    });

    // Escuchar el cambio en el filtro de sede
    $('#filterSede').on('change', function() {
        ListarProductos();
    });

    // Función para limpiar filtros
    $('#clearFilters').click(function() {
        $('#filter').val('');
        $('#filterEmail').val('');
        $('#filterRole').val('');
        $('#filterSede').val('');
        ListarProductos();
    });

    // Función para abrir el modal de creación de usuario
    $('#openUserModal').click(function() {
        $('#createUserForm')[0].reset();
        $('#createUserModal').modal('show');
    });

    // Definir la función Editar en el ámbito global
    window.Editar = function(id) {
        fetch(`/admin/${id}/edit`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(json => {
            if (json) {
                // Llenar el formulario del modal con los datos del usuario
                document.getElementById('editId').value = json.id || '';
                document.getElementById('editName').value = json.name || '';
                document.getElementById('editEmail').value = json.email || '';
                document.getElementById('editRoleId').value = json.role_id || '';
                document.getElementById('editSedeId').value = json.sede_id || '';

                // Mostrar el modal de edición
                $('#editUserModal').modal('show');
            } else {
                console.error('No se recibieron datos del usuario.');
            }
        })
        .catch(error => {
            console.error('Error al obtener los datos del usuario:', error);
        });
    };

    // Función para registrar un nuevo usuario
    $('#createUserForm').submit(function(e) {
        e.preventDefault();
        const formdata = new FormData(this);
        formdata.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch("{{ route('crudAdmin.store') }}", {
            method: 'POST',
            body: formdata
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                Swal.fire({
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#createUserModal').modal('hide');
                ListarProductos();
            }
        })
    });

    $('#editUserForm').submit(function(e) {
    e.preventDefault();
    let id = $('#editId').val();
    const data = {
        name: $('#editName').val(),
        email: $('#editEmail').val(),
        password: $('#editPassword').val(),
        password_confirmation: $('#editPasswordConfirmation').val(),
        role_id: $('#editRoleId').val(),
        sede_id: $('#editSedeId').val(),
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    const urlEncodedData = new URLSearchParams(data).toString();

    fetch(`/admin/users/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: urlEncodedData
    })
    .then(response => response.json())
    .then(data => {
        if (data.errors) {
            console.error('Errores de validación:', data.errors);
        } else if (data.message) {
            Swal.fire({
                icon: 'success',
                title: data.message,
                showConfirmButton: false,
                timer: 1500
            });
            $('#editUserModal').modal('hide');
            ListarProductos();
        }
    })
    .catch(error => {
        console.error('Error al actualizar el usuario:', error);
    });
});

    // Función para eliminar un usuario
    window.Eliminar = function(id) {
        Swal.fire({
            title: 'Está seguro de eliminar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si!',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(responseText => {
                    if (responseText.message === "Usuario e incidencias eliminados exitosamente.") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Usuario e incidencias eliminados exitosamente',
                            showConfirmButton: false,
                            timer: 1100
                        });
                        ListarProductos();
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar el usuario:', error);
                });
            }
        });
    };
});
</script>
@endsection