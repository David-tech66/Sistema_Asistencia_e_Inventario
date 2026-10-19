/* ==========================================
    REPORTE DE PRODUCTOS (cuadrícula) - PANEL ADMINISTRATIVO (GRUPO MDL)
    Recursos para resources/views/reporte_productos.blade.php
   ========================================== */

// Las funciones usadas por onclick en línea deben ser globales (Vite usa módulos ESM).
window.abrirModal = function (id) {
    document.getElementById(id).classList.remove('hidden');
};

window.cerrarModal = function (id) {
    document.getElementById(id).classList.add('hidden');
};

window.editarProducto = function (id) {
    fetch('/productos/' + id + '/edit')
        .then(function (res) { return res.json(); })
        .then(function (p) {
            const form = document.getElementById('form-producto-editar');
            form.action = '/productos/' + p.id;

            const set = function (nombre, valor) {
                const el = form.querySelector('[name="' + nombre + '"]');
                if (el) el.value = (valor === null || valor === undefined) ? '' : valor;
            };
            set('codigo_qr', p.codigo_qr);
            set('nombre', p.nombre);
            set('categoria_id', p.categoria_id);
            set('marca_id', p.marca_id);
            set('proveedor_id', p.proveedor_id);
            set('meses_garantia', p.meses_garantia);
            set('stock_actual', p.stock_actual);
            set('stock_minimo', p.stock_minimo);
            set('precio_compra', p.precio_compra);
            set('precio_venta', p.precio_venta);
            set('especificaciones', p.especificaciones);

            const fileInput = form.querySelector('[name="imagen"]');
            if (fileInput) fileInput.value = '';

            const img = document.getElementById('preview-img-editar');
            const icon = document.getElementById('preview-icon-editar');
            if (img) {
                if (p.imagen) {
                    img.src = '/storage/' + p.imagen;
                    img.classList.remove('hidden');
                    if (icon) icon.classList.add('hidden');
                } else {
                    img.classList.add('hidden');
                    if (icon) icon.classList.remove('hidden');
                }
            }

            window.abrirModal('modal-producto-editar');
        })
        .catch(function () { alert('No se pudo cargar el producto.'); });
};

window.eliminarProducto = function (id, nombre) {
    document.getElementById('form-eliminar').action = '/productos/' + id;
    document.getElementById('eliminar-nombre').textContent = nombre;
    window.abrirModal('modal-eliminar');
};

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
        window.abrirModal('modal-producto-crear');
    }
});
