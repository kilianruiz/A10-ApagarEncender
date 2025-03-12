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
                    str += `<td class="d-flex flex-row" style="gap:20px;">`;
                    if (user.role_id !== 1) { // Suponiendo que el rol de administrador tiene el ID 1
                        str += `<button type='button' class='btn btn-success' onclick="Editar(${user.id})">Editar</button>`;
                    } else {
                        str += `<button type='button' class='btn btn-success' disabled>Editar</button>`;
                    }
                    if (user.role_id !== 1) { // Suponiendo que el rol de administrador tiene el ID 1
                        str += `<button type='button' class='btn btn-danger' onclick="Eliminar(${user.id})">Eliminar</button>`;
                    } else {
                        str += `<button type='button' class='btn btn-danger' disabled>Eliminar</button>`;
                    }
                        str += `<button type='button' class='btn btn-primary' id='btn-incidencias-${user.id}' onclick="AsignarIncidencia(${user.id})">Incidencia</button>`;
                    str += `</td></tr>`;
                    tabla += str;
                });
                resultado.innerHTML = tabla;

                // Verificar incidencias para cada usuario
                data.usuarios.forEach(user => {
                    const buttonElement = document.getElementById(`btn-incidencias-${user.id}`);
                    verificarIncidencias(user.id, buttonElement);
                });

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
                    if (responseText.message) {
                        Swal.fire({
                            icon: 'success',
                            title: responseText.message,
                            showConfirmButton: false,
                            timer: 1100
                        });
                        ListarProductos(); // Actualiza la lista de usuarios
                    } else if (responseText.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: responseText.error,
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar el usuario:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al eliminar el usuario.',
                    });
                });
            }
        });
    };

    function verificarIncidencias(userId, buttonElement) {
        fetch(`/admin/users/${userId}/incidencias`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                buttonElement.disabled = false;
            } else {
                buttonElement.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error al verificar las incidencias:', error);
        });
    }

    window.AsignarIncidencia = function(userId) {
        fetch(`/admin/users/${userId}/incidencias`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const incidenciasList = document.getElementById('incidenciasList');
            incidenciasList.innerHTML = '';
            if (data.length > 0) {
                data.forEach(incidencia => {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item';
                    listItem.innerHTML = `
                        <strong>Título:</strong> ${incidencia.titulo}<br>
                        <strong>Comentario:</strong> ${incidencia.comentario ? incidencia.comentario : 'No rellenado'}<br>
                        <strong>Imagen:</strong> ${incidencia.imagen ? `<img src="${incidencia.imagen}" alt="Imagen" style="max-width: 100px;">` : 'No disponible'}<br>
                    `;
                    incidenciasList.appendChild(listItem);
                });
                $('#incidenciasModal').modal('show');
            } else {
                alert('Este usuario no tiene incidencias.');
            }
        })
        .catch(error => {
            console.error('Error al obtener las incidencias:', error);
        });
    };
});