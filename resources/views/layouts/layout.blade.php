<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>@yield('title')</title>
    @yield('styles')
</head>

<body>
    <!-- navbar -->
    @yield('navbar')

    <!-- contenido -->
    @yield('content')

    <!-- footer -->
    @yield('content-footer')
    
    <!-- scripts -->
    @yield('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        // Inicializar la lista de productos al cargar la página
        ListarProductos('');

        // Escuchar el evento keyup para el filtro de búsqueda
        $('#filter').on('keyup', function() {
            const valor = $(this).val();
            ListarProductos(valor);
        });

        // Función para listar productos
        function ListarProductos(valor) {
            const resultado = document.getElementById('userTable');
            fetch('/admin/users', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                let tabla = '';
                data.users.forEach(user => {
                    let str = `<tr><td>${user.name}</td>`;
                    str += `<td>${user.email}</td>`;
                    str += `<td>`;
                    str += `<button type='button' class='btn btn-success' onclick="Editar(${user.id})">Editar</button>`;
                    str += `<button type='button' class='btn btn-danger' onclick="Eliminar(${user.id})">Eliminar</button>`;
                    str += `</td></tr>`;
                    tabla += str;
                });
                resultado.innerHTML = tabla;
            })
            .catch(error => {
                resultado.innerText = 'Error';
                console.error('Error:', error);
            });
        }

        // Función para registrar o actualizar un usuario
        $('#userForm').submit(function(e) {
            e.preventDefault();
            let id = $('#userId').val();
            let url = id ? `/admin/users/${id}` : "{{ route('crudAdmin.store') }}";
            let method = id ? 'PUT' : 'POST';

            const formdata = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formdata.append('_token', csrfToken);

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
            .catch(error => {
                console.error('Error:', error);
            });
        });

        // Función para editar un usuario
        window.Editar = function(id) {
            fetch(`/admin/users/${id}/edit`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(json => {
                $('#userId').val(json.id);
                $('#name').val(json.name);
                $('#email').val(json.email);
                $('#role_id').val(json.role_id);
                $('#userModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

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
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        }

        $('#openUserModal').click(function() {
            $('#userForm')[0].reset();
            $('#userId').val('');
            $('#userModal').modal('show');
        });
    });
    </script>
</body>

</html>