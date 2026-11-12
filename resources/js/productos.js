/* ==========================================
    PRODUCTOS (stock + escáner) - PANEL ADMINISTRATIVO (GRUPO MDL)
    Recursos para resources/views/productos.blade.php
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

            const nombreArchivo = document.getElementById('archivo-nombre-editar');
            if (nombreArchivo) nombreArchivo.textContent = 'Ningún archivo seleccionado';

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

const csrfMeta = document.querySelector('meta[name="csrf-token"]');
const csrfToken = csrfMeta ? csrfMeta.content : '';
const scannerInput = document.getElementById('scanner-input');

/* ============ Toast ============ */
let toastTimer = null;

function mostrarToast(texto, tono) {
    const toast = document.getElementById('toast-escaneo');
    document.getElementById('toast-escaneo-texto').textContent = texto;
    toast.className = 'toast-escaneo' + (tono ? ' toast-escaneo--' + tono : '');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { toast.classList.add('hidden'); }, 2500);
}

/* ============ Escaneo: código existente = +1 stock, desconocido = registrar ============ */
async function procesarEscaneo(codigo) {
    try {
        const r = await fetch('/productos/escanear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ codigo }),
        });
        const d = await r.json();

        if (d.encontrado) {
            actualizarFilaStock(d.producto.id, d.producto.stock_actual);
            mostrarToast('+1 ' + d.producto.nombre + ' — stock: ' + d.producto.stock_actual, '');
            return;
        }

        cerrarCamara();
        window.abrirModal('modal-producto-crear');
        const campo = document.getElementById('codigo_qr-crear');
        campo.value = d.codigo;
        campo.focus();
        mostrarToast('Código nuevo: completa el registro del producto', 'blue');
    } catch (e) {
        mostrarToast('Error al procesar el código', 'red');
    }
}

/* ============ Escáner USB/bluetooth: Enter procesa el código ============ */
if (scannerInput) {
    scannerInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const codigo = this.value.trim();
            if (!codigo) return;
            this.value = '';
            procesarEscaneo(codigo);
        }
    });
}

/* ============ Cámara (celular o laptop) ============ */
let camaraScanner = null;
let camaraFacing = 'environment';
let camaraPausada = false;

window.abrirCamara = async function () {
    document.getElementById('modal-camara').classList.remove('hidden');
    const estado = document.getElementById('camara-estado');

    if (!camaraScanner) {
        camaraScanner = new Html5Qrcode('lector-camara');
    }

    try {
        await camaraScanner.start(
            { facingMode: camaraFacing },
            { fps: 10, qrbox: { width: 230, height: 230 } },
            function (texto) { alDetectarCamara(texto); },
            function () {}
        );
        estado.textContent = 'Apunta al código del producto.';
        estado.className = 'camera-status';
    } catch (e) {
        estado.textContent = 'No se pudo acceder a la cámara. ' + e;
        estado.className = 'camera-status camera-status--error';
    }
};

async function alDetectarCamara(texto) {
    if (camaraPausada) return;
    camaraPausada = true;

    const estado = document.getElementById('camara-estado');
    estado.textContent = 'Código detectado: ' + texto;
    estado.className = 'camera-status camera-status--active';

    try { camaraScanner.pause(true); } catch (e) {}

    await procesarEscaneo(texto);

    setTimeout(async function () {
        try { await camaraScanner.resume(); } catch (e) {}
        estado.textContent = 'Apunta al siguiente código.';
        estado.className = 'camera-status';
        camaraPausada = false;
    }, 1500);
}

window.cerrarCamara = async function () {
    document.getElementById('modal-camara').classList.add('hidden');
    if (camaraScanner) {
        try { await camaraScanner.stop(); camaraScanner.clear(); } catch (e) {}
        camaraScanner = null;
    }
    camaraPausada = false;
};

window.voltearCamara = async function () {
    camaraFacing = camaraFacing === 'environment' ? 'user' : 'environment';
    if (camaraScanner) {
        try { await camaraScanner.stop(); camaraScanner.clear(); } catch (e) {}
        camaraScanner = null;
    }
    await window.abrirCamara();
};

/* ============ Tabla de stock ============ */
function actualizarFilaStock(id, stockNuevo) {
    const fila = document.querySelector('tr[data-stock-id="' + id + '"]');
    if (!fila) return;

    fila.querySelector('[data-stock-valor]').textContent = stockNuevo;

    const minimo = parseInt(fila.getAttribute('data-stock-minimo'), 10) || 0;
    const badge = fila.querySelector('[data-stock-estado]');
    if (badge) {
        if (stockNuevo <= minimo) {
            badge.className = 'stock-badge stock-badge--low';
            badge.textContent = 'Stock bajo';
        } else {
            badge.className = 'stock-badge stock-badge--ok';
            badge.textContent = 'Normal';
        }
    }

    fila.classList.add('row-flash');
    setTimeout(function () { fila.classList.remove('row-flash'); }, 1200);
}

function filtrarStock() {
    const q = document.getElementById('buscar-stock').value.toLowerCase();
    document.querySelectorAll('tr[data-stock-id]').forEach(function (tr) {
        tr.style.display = tr.getAttribute('data-buscar').includes(q) ? '' : 'none';
    });
}

/* ============ Ajuste manual de stock (entrada/salida con motivo) ============ */
window.abrirAjusteStock = function (id, nombre, stockActual) {
    document.getElementById('ajuste-producto-id').value = id;
    document.getElementById('ajuste-producto-nombre').textContent = nombre + ' — stock actual: ' + stockActual;
    document.getElementById('ajuste-stock-actual').value = stockActual;
    document.getElementById('ajuste-cantidad').value = 1;
    document.getElementById('ajuste-motivo').value = '';
    document.getElementById('ajuste-tipo').value = 'entrada';

    const error = document.getElementById('ajuste-error');
    error.classList.add('hidden');

    window.abrirModal('modal-ajustar-stock');
};

window.guardarAjusteStock = async function (event) {
    event.preventDefault();

    const id = document.getElementById('ajuste-producto-id').value;
    const errorBox = document.getElementById('ajuste-error');
    const errorTexto = errorBox.querySelector('span');
    const btn = document.getElementById('ajuste-btn-guardar');

    const payload = {
        tipo: document.getElementById('ajuste-tipo').value,
        cantidad: parseInt(document.getElementById('ajuste-cantidad').value, 10),
        motivo: document.getElementById('ajuste-motivo').value.trim(),
    };

    errorBox.classList.add('hidden');
    btn.disabled = true;

    try {
        const res = await fetch('/productos/' + id + '/stock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-Token': csrfToken,
            },
            body: JSON.stringify(payload),
        });
        const data = await res.json();

        if (!res.ok || !data.exito) {
            const mensaje = (data && data.mensaje)
                || (data && data.errors ? Object.values(data.errors).flat().join(' ') : null)
                || 'No se pudo registrar el movimiento.';
            errorTexto.textContent = mensaje;
            errorBox.classList.remove('hidden');
            return false;
        }

        actualizarFilaStock(id, data.producto.stock_actual);
        mostrarToast(data.mensaje + ' Stock actual: ' + data.producto.stock_actual + '.', 'blue');
        window.cerrarModal('modal-ajustar-stock');

        // Recargar para refrescar el historial de movimientos
        setTimeout(function () { window.location.reload(); }, 1600);

        return false;
    } catch (e) {
        errorTexto.textContent = 'Error de conexión. Intenta nuevamente.';
        errorBox.classList.remove('hidden');
        return false;
    } finally {
        btn.disabled = false;
    }
};

document.addEventListener('DOMContentLoaded', function () {
    // Búsqueda en vivo en la tabla de stock
    const buscador = document.getElementById('buscar-stock');
    if (buscador) {
        buscador.addEventListener('input', filtrarStock);
    }

    // Escape: primero detener la cámara si está abierta, luego cerrar modales
    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;

        const camara = document.getElementById('modal-camara');
        if (camara && !camara.classList.contains('hidden')) {
            window.cerrarCamara();
        }
        document.querySelectorAll('.modal-overlay').forEach(function (modal) {
            modal.classList.add('hidden');
        });
    });

    // Reabrir el formulario si Laravel devolvió errores de validación
    const errores = document.getElementById('errores-validacion');
    if (errores && errores.value) {
        // Reabre el mismo modal del que vino el envío (crear o editar)
        if (typeof window.reabrirModalConErrores === 'function') {
            window.reabrirModalConErrores(errores.value);
        } else {
            window.abrirModal(errores.value);
        }
    }
});
