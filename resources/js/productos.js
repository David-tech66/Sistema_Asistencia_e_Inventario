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
    if (errores && errores.value === '1') {
        window.abrirModal('modal-producto-crear');
    }
});
