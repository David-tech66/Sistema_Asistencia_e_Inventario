/* ==========================================
    MARCAS - PANEL ADMINISTRATIVO (GRUPO MDL)
    Recursos para resources/views/marcas.blade.php
   ========================================== */

// Las funciones usadas por onclick en línea deben ser globales (Vite usa módulos ESM).
window.abrirModal = function (id) {
    document.getElementById(id).classList.remove('hidden');
};

window.cerrarModal = function (id) {
    document.getElementById(id).classList.add('hidden');
};

window.editarMarca = function (id) {
    fetch('/marcas/' + id + '/edit')
        .then(function (res) { return res.json(); })
        .then(function (m) {
            const form = document.getElementById('form-marca-editar');
            form.action = '/marcas/' + m.id;
            form.querySelector('[name="nombre"]').value = m.nombre || '';
            form.querySelector('[name="descripcion"]').value = m.descripcion || '';
            window.abrirModal('modal-marca-editar');
        })
        .catch(function () { alert('No se pudo cargar la marca.'); });
};

window.eliminarMarca = function (id, nombre) {
    document.getElementById('form-eliminar').action = '/marcas/' + id;
    document.getElementById('eliminar-nombre').textContent = nombre;
    window.abrirModal('modal-eliminar');
};

function filtrarMarcas() {
    const texto = document.getElementById('buscar-marca').value.toLowerCase().trim();
    const filas = document.querySelectorAll('.marca-fila');
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
    const buscador = document.getElementById('buscar-marca');
    if (buscador) {
        buscador.addEventListener('input', filtrarMarcas);
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
        window.abrirModal('modal-marca-crear');
    }
});
