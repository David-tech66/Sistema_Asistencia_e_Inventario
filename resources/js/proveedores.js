/* ==========================================
    PROVEEDORES - PANEL ADMINISTRATIVO (GRUPO MDL)
    Recursos para resources/views/proveedores.blade.php
   ========================================== */

// Las funciones usadas por onclick en línea deben ser globales (Vite usa módulos ESM).
window.abrirModal = function (id) {
    document.getElementById(id).classList.remove('hidden');
};

window.cerrarModal = function (id) {
    document.getElementById(id).classList.add('hidden');
};

window.editarProveedor = function (id) {
    fetch('/proveedores/' + id + '/edit')
        .then(function (res) { return res.json(); })
        .then(function (p) {
            const form = document.getElementById('form-proveedor-editar');
            form.action = '/proveedores/' + p.id;
            form.querySelector('[name="razon_social"]').value = p.razon_social || '';
            form.querySelector('[name="ruc"]').value = p.ruc || '';
            form.querySelector('[name="telefono"]').value = p.telefono || '';
            window.abrirModal('modal-proveedor-editar');
        })
        .catch(function () { alert('No se pudo cargar el proveedor.'); });
};

window.eliminarProveedor = function (id, nombre) {
    document.getElementById('form-eliminar').action = '/proveedores/' + id;
    document.getElementById('eliminar-nombre').textContent = nombre;
    window.abrirModal('modal-eliminar');
};

function filtrarProveedores() {
    const texto = document.getElementById('buscar-proveedor').value.toLowerCase().trim();
    const filas = document.querySelectorAll('.proveedor-fila');
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
    const buscador = document.getElementById('buscar-proveedor');
    if (buscador) {
        buscador.addEventListener('input', filtrarProveedores);
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
        window.abrirModal('modal-proveedor-crear');
    }
});
