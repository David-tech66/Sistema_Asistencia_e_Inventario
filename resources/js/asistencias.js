/* ==========================================
   ASISTENCIAS - PANEL ADMINISTRATIVO (GRUPO MDL)
   Recursos para resources/views/asistencias.blade.php
   ========================================== */

document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('dni');
    const preview = document.getElementById('dni-preview');
    const btnTexto = document.getElementById('btn-registrar-texto');
    if (!input || !preview) return;

    let timer = null;

    const modoActual = function () {
        const seleccionado = document.querySelector('input[name="modo"]:checked');
        return seleccionado ? seleccionado.value : 'entrada';
    };

    const limpiar = function () {
        preview.textContent = '';
        preview.className = 'dni-preview';
    };

    const pintar = function (texto, tipo) {
        preview.textContent = texto;
        preview.className = 'dni-preview dni-preview--' + tipo;
    };

    const actualizarBoton = function () {
        if (!btnTexto) return;
        btnTexto.textContent = modoActual() === 'salida'
            ? 'Registrar salida'
            : 'Registrar entrada';
    };

    const evaluar = function (data, dni) {
        if (!data.encontrado) {
            if (dni.length >= 8) {
                pintar('Ningún empleado con DNI ' + dni, 'err');
            } else {
                limpiar();
            }
            return;
        }

        const emp = data.empleado;
        const base = emp.nombre + ' — ' + emp.cargo;
        const modo = modoActual();

        if (!emp.activo) {
            pintar(base + ' (inactivo)', 'err');
            return;
        }

        if (modo === 'entrada') {
            if (emp.entrada_hoy) {
                pintar(base + ' · Entrada de hoy ya registrada (' + emp.entrada_hoy + ')', 'warn');
            } else if (emp.salida_hoy) {
                pintar(base + ' · Salida de hoy registrada (' + emp.salida_hoy + ')', 'warn');
            } else {
                pintar(base + ' · Listo para registrar entrada', 'ok');
            }
            return;
        }

        // Modo salida
        if (!emp.entrada_hoy) {
            pintar(base + ' · Sin entrada hoy: primero registra la entrada', 'err');
        } else if (emp.salida_hoy) {
            pintar(base + ' · Salida de hoy ya registrada (' + emp.salida_hoy + ')', 'warn');
        } else {
            pintar(base + ' · Entrada ' + emp.entrada_hoy + ' · Listo para registrar salida', 'ok');
        }
    };

    const buscar = function () {
        const dni = input.value.trim();

        if (dni.length < 4) {
            limpiar();
            return;
        }

        fetch('/empleados/buscar?dni=' + encodeURIComponent(dni), {
            headers: { 'Accept': 'application/json' },
        })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (input.value.trim() !== dni) return;
                evaluar(data, dni);
            })
            .catch(limpiar);
    };

    const refrescar = function () {
        clearTimeout(timer);
        actualizarBoton();
        buscar();
    };

    // Cambio de modo: reevalúa el DNI escrito y cambia el texto del botón.
    document.querySelectorAll('input[name="modo"]').forEach(function (radio) {
        radio.addEventListener('change', refrescar);
    });

    actualizarBoton();

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(buscar, 250);
    });

    // Escáner de código de barras: teclea los dígitos y presiona Enter.
    // Enter envía el formulario en el modo activo, validando antes el DNI.
    input.addEventListener('keydown', function (event) {
        if (event.key !== 'Enter') return;

        event.preventDefault();
        clearTimeout(timer);

        const dni = input.value.trim();

        if (!/^[0-9]{6,15}$/.test(dni)) {
            pintar('El DNI debe tener entre 6 y 15 números', 'err');
            return;
        }

        buscar();
        input.form.submit();
    });
});
