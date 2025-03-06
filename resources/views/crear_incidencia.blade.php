<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Incidencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Crear Nueva Incidencia</h1>

        <!-- Formulario para crear una incidencia -->
        <form action="" method="POST">
            @csrf <!-- Token CSRF para protección contra ataques -->

            <!-- Campo para el título -->
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>

            <!-- Campo para la descripción -->
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>

            <!-- Campo para la categoría -->
            <div class="mb-3">
                <label for="categoria_id" class="form-label">Categoría</label>
                <select class="form-select" id="categoria_id" name="categoria_id" required>
                    <option value="">Selecciona una categoría</option>
                </select>
            </div>

            <!-- Botón para enviar el formulario -->
            <button type="submit" class="btn btn-primary">Guardar Incidencia</button>
        </form>
    </div>
</body>
</html>