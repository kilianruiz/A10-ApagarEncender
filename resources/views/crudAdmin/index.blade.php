@extends('../layouts.layout')

@section('content')
<div class="container">
    <h1>Gestión de Usuarios</h1>
    <input type="text" id="filter" placeholder="Buscar usuarios..." class="form-control mb-3">
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
</div>

<!-- Modal para crear/editar usuario -->
<div id="userModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="role_id">Rol</label>
                        <select id="role_id" name="role_id" class="form-control" required>
                            <option value="">Seleccione un rol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sede_id">Sede</label>
                        <select id="sede_id" name="sede_id" class="form-control" required>
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
// Definir la función Editar en el ámbito global
function Editar(id) {
    // Usar fetch para realizar la solicitud GET
    fetch('/admin/' + id + '/edit', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud');
        }
        return response.json();
    })
    .then(json => {
        // DEBUG (se confirma que se recibe un objeto con datos)
        console.log(json);

        // Rellenar los campos del formulario con los datos del usuario
        document.getElementById('id').value = json.id;
        document.getElementById('name').value = json.name;
        document.getElementById('email').value = json.email;
        document.getElementById('role_id').value = json.role_id;
        document.getElementById('sede_id').value = json.sede_id;
        $('#userModal').modal('show'); // Mostrar el modal
    })
    .catch(error => {
        console.error('Error al obtener los datos del usuario:', error);
    });
}

$(document).ready(function() {
    // Inicializar la lista de productos al cargar la página
    ListarProductos();

    // Escuchar el evento keyup para el filtro de búsqueda
    $('#filter').on('keyup', function() {
        const valor = $(this).val();
        ListarProductos(valor);
    });

    // Función para listar productos
    function ListarProductos(filtro = '') {
        const resultado = document.getElementById('userTable');
        fetch(`/admin/users?nombre=${encodeURIComponent(filtro)}`, {
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
                    str += `<button type='button' class='btn btn-success' onclick=\"Editar(${user.id})\">Editar</button>`;
                    str += `<button type='button' class='btn btn-danger' onclick=\"Eliminar(${user.id})\">Eliminar</button>`;
                    str += `</td></tr>`;
                    tabla += str;
                });
                resultado.innerHTML = tabla;
            } else {
                resultado.innerHTML = '<tr><td colspan="5">No se encontraron usuarios.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error al obtener los usuarios:', error);
            resultado.innerHTML = '<tr><td colspan="5">Error al cargar los usuarios.</td></tr>';
        });
    }

    // Función para registrar o actualizar un usuario
    $('#userForm').submit(function(e) {
        e.preventDefault();
        let id = $('#id').val();
        let url = id ? `/admin/users/${id}` : "{{ route('crudAdmin.store') }}";
        let method = id ? 'PUT' : 'POST';

        const formdata = new FormData(this);
        formdata.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch(url, {
            method: method,
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
                $('#userModal').modal('hide');
                ListarProductos('');
            }
        })
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
                    if (responseText.message === "Usuario eliminado exitosamente.") {
                        ListarProductos('');
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                })
            }
        });
    }

    $('#openUserModal').click(function() {
        $('#userForm')[0].reset();
        $('#id').val('');
        $('#userModal').modal('show');
    });  
});
</script>
@endsection