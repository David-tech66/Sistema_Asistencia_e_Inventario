/* ==========================================
    CATEGORÍAS - PANEL ADMINISTRATIVO (GRUPO MDL)
    Recursos para resources/views/categorias.blade.php
   ========================================== */

// Las funciones usadas por onclick en línea deben ser globales (Vite usa módulos ESM).
window.abrirModal = function (id) {
    document.getElementById(id).classList.remove('hidden');
};

window.cerrarModal = function (id) {
    document.getElementById(id).classList.add('hidden');
};

window.editarCategoria = function (id) {
    fetch('/categorias/' + id + '/edit')
        .then(function (res) { return res.json(); })
        .then(function (c) {
            const form = document.getElementById('form-categoria-editar');
            form.action = '/categorias/' + c.id;
            form.querySelector('[name="nombre_categoria"]').value = c.nombre_categoria || '';
            form.querySelector('[name="descripcion"]').value = c.descripcion || '';
            window.abrirModal('modal-categoria-editar');
        })
        .catch(function () { alert('No se pudo cargar la categoría.'); });
};

window.eliminarCategoria = function (id, nombre) {
    document.getElementById('form-eliminar').action = '/categorias/' + id;
    document.getElementById('eliminar-nombre').textContent = nombre;
    window.abrirModal('modal-eliminar');
};

function filtrarCategorias() {
    const texto = document.getElementById('buscar-categoria').value.toLowerCase().trim();
    const filas = document.querySelectorAll('.categoria-fila');
    let visibles = 0;

    filas.forEach(function (fila) {
        const coincide = fila.dataset.buscar.includes(texto);
        fila.classList.toggle('hidden', !coincide);
        if (coincide) visibles++;
    });

    const vacio = document.getElementById('sin-resultados');
    if (vacio) vacio.classList.toggle('hidden', !(texto && visibles === 0));
}

document.addEventListener('DOMContentLoaded', function () {
    // Búsqueda en vivo
    const buscador = document.getElementById('buscar-categoria');
    if (buscador) {
        buscador.addEventListener('input', filtrarCategorias);
    }

    // Cerrar modales con la tecla Escape
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal-overlay').forEach(function (modal) {
                modal.classList.add('hidden');
            });
        }
    });

    // Reabrir el formulario si Laravel devolvió errores de validación
    const errores = document.getElementById('errores-validacion');
    if (errores && errores.value === '1') {
        window.abrirModal('modal-categoria-crear');
    }
});
