/* ==========================================
   VENTAS (POS) - PANEL ADMINISTRATIVO (GRUPO MDL)
   Recursos para resources/views/ventas.blade.php
   ========================================== */

const csrfMeta = document.querySelector('meta[name="csrf-token"]');
const CSRF = csrfMeta ? csrfMeta.content : '';

let carrito = []; // {id, nombre, precio, cantidad, stock_max}

window.renderVoucher = function() {
    const cont = document.getElementById('voucher-items');
    const totalEl = document.getElementById('voucher-total');
    const btnCobrar = document.getElementById('btn-cobrar');

    if (carrito.length === 0) {
        cont.innerHTML = `<p id="voucher-vacio" class="text-center text-sm text-gray-400 py-8">
            <i class="fa-solid fa-cart-shopping text-3xl text-gray-200 block mb-3"></i>
            Escanea un producto para comenzar</p>`;
        totalEl.textContent = 'S/ 0.00';
        btnCobrar.disabled = true;
        return;
    }

    btnCobrar.disabled = false;
    let total = 0;

    cont.innerHTML = carrito.map((item, i) => {
        const subtotal = item.precio * item.cantidad;
        total += subtotal;
        return `
        <div class="voucher-item-row">
            <div style="flex: 1; min-width: 0;">
                <p style="font-size: 14px; font-weight: 600; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${item.nombre}</p>
                <p style="font-size: 12px; color: #6b7280;">S/ ${item.precio.toFixed(2)} c/u</p>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <button type="button" onclick="cambiarCantidad(${i}, -1)" style="height: 28px; width: 28px; border-radius: 8px; background: #fff; border: 1px solid #e5e7eb; color: #6b7280; cursor: pointer;">
                    <i class="fa-solid fa-minus" style="font-size: 10px;"></i>
                </button>
                <span style="width: 24px; text-align: center; font-size: 14px; font-weight: 700; color: #1f2937;">${item.cantidad}</span>
                <button type="button" onclick="cambiarCantidad(${i}, 1)" style="height: 28px; width: 28px; border-radius: 8px; background: #fff; border: 1px solid #e5e7eb; color: #6b7280; cursor: pointer;">
                    <i class="fa-solid fa-plus" style="font-size: 10px;"></i>
                </button>
            </div>
            <span style="width: 64px; text-align: right; font-size: 14px; font-weight: 600; color: #111827;">S/ ${subtotal.toFixed(2)}</span>
            <button type="button" onclick="eliminarItem(${i})" style="height: 28px; width: 28px; border-radius: 8px; border: none; background: transparent; color: #f87171; cursor: pointer;">
                <i class="fa-solid fa-trash-can" style="font-size: 12px;"></i>
            </button>
        </div>`;
    }).join('');

    totalEl.textContent = 'S/ ' + total.toFixed(2);
};

window.cambiarCantidad = function(idx, delta) {
    const item = carrito[idx];
    const nueva = item.cantidad + delta;
    if (nueva < 1) { window.eliminarItem(idx); return; }
    if (nueva > item.stock_max) { window.toast('Stock máximo: ' + item.stock_max, 'amber'); return; }
    item.cantidad = nueva;
    window.renderVoucher();
};

window.eliminarItem = function(idx) {
    carrito.splice(idx, 1);
    window.renderVoucher();
};

window.cancelarVenta = function() {
    if (carrito.length === 0) return;
    if (!confirm('¿Cancelar la venta? Se vaciará el voucher.')) return;
    carrito = [];
    window.renderVoucher();
};

// ---- Escaneo ----
async function procesarEscaneo(codigo) {
    try {
        const res = await fetch('/ventas/escanear', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ codigo })
        });
        const data = await res.json();

        if (!data.encontrado) {
            window.toast('Código no encontrado: ' + codigo, 'red');
            return;
        }

        if (data.sin_stock) {
            window.toast('Sin stock: ' + data.producto.nombre, 'red');
            return;
        }

        const existente = carrito.find(c => c.id === data.producto.id);
        if (existente) {
            if (existente.cantidad >= data.producto.stock_actual) {
                window.toast('Stock máximo alcanzado para ' + data.producto.nombre, 'amber');
                return;
            }
            existente.cantidad++;
        } else {
            carrito.push({
                id: data.producto.id,
                nombre: data.producto.nombre,
                precio: data.producto.precio_venta,
                cantidad: 1,
                stock_max: data.producto.stock_actual,
            });
        }

        window.renderVoucher();
        window.toast(data.producto.nombre + ' agregado', 'green');
    } catch (err) {
        window.toast('Error al escanear', 'red');
    }
}

// ---- Cobrar ----
window.cobrarVenta = async function() {
    if (carrito.length === 0) return;

    const clienteId = document.getElementById('cliente-select').value;
    const tipo = document.getElementById('comprobante-select').value;

    const btn = document.getElementById('btn-cobrar');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Procesando...';

    try {
        const res = await fetch('/ventas', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({
                cliente_id: parseInt(clienteId),
                tipo_comprobante: tipo,
                items: carrito.map(c => ({
                    producto_id: c.id,
                    cantidad: c.cantidad,
                })),
            })
        });
        const data = await res.json();

        if (!data.exito) {
            window.toast(data.mensaje || 'Error al registrar la venta', 'red');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check-double"></i> Cobrar venta';
            return;
        }

        document.getElementById('exito-comprobante').textContent = data.venta.numero_comprobante;
        document.getElementById('exito-cliente').textContent = data.venta.cliente;
        document.getElementById('exito-total').textContent = 'S/ ' + data.venta.total.toFixed(2);
        document.getElementById('modal-exito').classList.remove('hidden');

        carrito = [];
        window.renderVoucher();
    } catch (err) {
        window.toast('Error de conexión', 'red');
    }

    btn.disabled = carrito.length === 0;
    btn.innerHTML = '<i class="fa-solid fa-check-double"></i> Cobrar venta';
};

window.cerrarModalExito = function() {
    document.getElementById('modal-exito').classList.add('hidden');
    document.getElementById('scanner-input').focus();
};

// ---- Toast ----
window.toast = function(msg, color) {
    const t = document.getElementById('toast');
    t.className = 'toast-pos toast-pos--' + (color || 'green');
    t.textContent = msg;
    t.classList.remove('hidden');
    clearTimeout(t._timer);
    t._timer = setTimeout(() => t.classList.add('hidden'), 2200);
};

// ---- Cámara ----
let camaraScanner = null;
let camaraFacing = 'environment';
let camaraPausada = false;

window.abrirCamara = async function() {
    document.getElementById('modal-camara').classList.remove('hidden');
    const estado = document.getElementById('camara-estado');

    if (!camaraScanner) {
        camaraScanner = new Html5Qrcode('lector-camara');
    }

    try {
        await camaraScanner.start(
            { facingMode: camaraFacing },
            { fps: 10, qrbox: { width: 230, height: 230 } },
            (texto) => alDetectarCamara(texto),
            () => {}
        );
        estado.textContent = 'Apunta al código del producto.';
        estado.style.color = '#6b7280';
    } catch (e) {
        estado.textContent = 'No se pudo acceder a la cámara. ' + e;
        estado.style.color = '#ef4444';
    }
};

async function alDetectarCamara(texto) {
    if (camaraPausada) return;
    camaraPausada = true;

    const estado = document.getElementById('camara-estado');
    estado.textContent = 'Código detectado: ' + texto;
    estado.style.color = '#2563eb';

    try { camaraScanner.pause(true); } catch (e) {}

    await procesarEscaneo(texto);

    setTimeout(async () => {
        try { await camaraScanner.resume(); } catch (e) {}
        estado.textContent = 'Apunta al siguiente código.';
        estado.style.color = '#6b7280';
        camaraPausada = false;
    }, 1500);
}

window.cerrarCamara = async function() {
    document.getElementById('modal-camara').classList.add('hidden');
    if (camaraScanner) {
        try { await camaraScanner.stop(); camaraScanner.clear(); } catch (e) {}
        camaraScanner = null;
    }
    camaraPausada = false;
};

window.voltearCamara = async function() {
    camaraFacing = camaraFacing === 'environment' ? 'user' : 'environment';
    if (camaraScanner) {
        try { await camaraScanner.stop(); camaraScanner.clear(); } catch (e) {}
        camaraScanner = null;
    }
    await window.abrirCamara();
};

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('scanner-input');
    if (input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const codigo = this.value.trim();
                if (codigo) procesarEscaneo(codigo);
                this.value = '';
            }
        });
    }
});