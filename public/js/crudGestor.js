document.addEventListener("DOMContentLoaded", function () {
    function cargarIncidencias(estado) {
        let estadoNormalizado = estado.replace(/\s/g, "_"); // Normaliza el estado
        let url = `/api/incidencias?estado=${estadoNormalizado}`; // Ruta corregida

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error en la respuesta del servidor");
                }
                return response.json();
            })
            .then(data => {
                console.log(`Datos recibidos para el estado: ${estado}`, data);

                let tabla = document.getElementById(`tabla-${estadoNormalizado}`);
                tabla.innerHTML = ""; // Limpiar tabla

                if (data.length === 0) {
                    tabla.innerHTML = "<tr><td colspan='6'>No hay incidencias en este estado.</td></tr>";
                } else {
                    data.forEach(incidencia => {
                        let fila = document.createElement("tr");
                        fila.innerHTML = `
                            <td>${incidencia.id}</td>
                            <td>${incidencia.titulo}</td>
                            <td>${incidencia.descripcion}</td>
                            <td>${incidencia.comentario}</td>
                            <td>${incidencia.estado}</td>
                            <td>${incidencia.prioridad}</td>
                            <td>${incidencia.user.name}</td>
                            <td>${incidencia.categoria ? incidencia.categoria.nombre : 'Sin Categoría'}</td> <!-- Nombre de la categoría -->
                            <td>${incidencia.subcategoria ? incidencia.subcategoria.nombre : 'Sin Subcategoría'}</td> <!-- Nombre de la subcategoría -->
                            <td>${incidencia.feedback}</td>
                            <td>${incidencia.created_at}</td>
                        `;
                        tabla.appendChild(fila);
                    });
                }
            })
            .catch(error => {
                console.error("Error al obtener incidencias:", error);
            });
    }

    // Cargar incidencias al cambiar de pestaña
    document.querySelectorAll(".nav-link").forEach(tab => {
        tab.addEventListener("shown.bs.tab", function (event) {
            let estado = event.target.getAttribute("data-status");
            cargarIncidencias(estado);
        });
    });

    // Cargar la primera pestaña al inicio
    let estadoInicial = document.querySelector(".nav-link.active").getAttribute("data-status");
    cargarIncidencias(estadoInicial);
});