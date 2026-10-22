/* ==========================================
    REPORTE DE PRODUCTOS (cuadrícula) - PANEL ADMINISTRATIVO (GRUPO MDL)
    Recursos para resources/views/reporte_productos.blade.php
    Reporte de solo lectura: únicamente búsqueda/filtrado, sin acciones CRUD.
   ========================================== */

function filtrarProductos() {
    const texto = document.getElementById('buscar-producto').value.toLowerCase().trim();
    const tarjetas = document.querySelectorAll('.producto-card');
    let visibles = 0;

    tarjetas.forEach(function (tarjeta) {
        const coincide = tarjeta.dataset.buscar.includes(texto);
        tarjeta.classList.toggle('hidden', !coincide);
        if (coincide) visibles++;
    });

    const vacio = document.getElementById('sin-resultados');
    if (vacio) vacio.classList.toggle('hidden', !(texto && visibles === 0));
}

document.addEventListener('DOMContentLoaded', function () {
    // Búsqueda en vivo
    const buscador = document.getElementById('buscar-producto');
    if (buscador) {
        buscador.addEventListener('input', filtrarProductos);
    }
});
