/* ==========================================
   ASISTENCIAS - PANEL ADMINISTRATIVO (GRUPO MDL)
   Recursos para resources/views/asistencias.blade.php
   ========================================== */

window.abrirModal = function (id) {
    document.getElementById(id).classList.remove("hidden");
};

window.cerrarModal = function (id) {
    document.getElementById(id).classList.add("hidden");
};

window.eliminarAsistencia = function (id, nombre, fecha) {
    document.getElementById("form-eliminar").action = "/asistencias/" + id;
    document.getElementById("eliminar-nombre").textContent = nombre + " (" + fecha + ")";
    window.abrirModal("modal-eliminar");
};

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

    const escapar = function (valor) {
        return String(valor).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    };

    const iniciales = function (nombre) {
        const partes = String(nombre || '').trim().split(/\s+/).filter(Boolean);
        if (!partes.length) return '?';
        const primera = partes[0].charAt(0);
        const segunda = partes[1] ? partes[1].charAt(0) : partes[0].charAt(1);
        return (primera + segunda).toUpperCase();
    };

    const ICONOS = {
        ok: 'fa-circle-check',
        warn: 'fa-clock',
        err: 'fa-circle-xmark',
    };

    const icono = function (tipo) {
        return 'fa-solid ' + (ICONOS[tipo] || 'fa-circle-info');
    };

    // Mensaje simple (DNI inexistente o inválido): icono + texto.
    const pintar = function (texto, tipo) {
        preview.innerHTML = '<i class="' + icono(tipo) + ' dni-preview__icon"></i>' + escapar(texto);
        preview.className = 'dni-preview dni-preview--' + tipo;
    };

    // Tarjeta de empleado: avatar con iniciales, nombre/cargo y chip de estado.
    const pintarCard = function (emp, mensaje, tipo) {
        preview.innerHTML =
            '<div class="dni-card">' +
                '<div class="dni-card__avatar">' + escapar(iniciales(emp.nombre)) + '</div>' +
                '<div class="dni-card__info">' +
                    '<p class="dni-card__nombre">' + escapar(emp.nombre) + '</p>' +
                    '<p class="dni-card__cargo">' + escapar(emp.cargo) + '</p>' +
                '</div>' +
                '<span class="dni-card__chip dni-card__chip--' + tipo + '">' +
                    '<i class="' + icono(tipo) + '"></i>' +
                    '<span class="dni-card__chip-texto">' + escapar(mensaje) + '</span>' +
                '</span>' +
            '</div>';
        preview.className = 'dni-preview dni-preview--card dni-preview--' + tipo;
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
        const modo = modoActual();

        if (!emp.activo) {
            pintarCard(emp, 'Empleado inactivo', 'err');
            return;
        }

        if (modo === 'entrada') {
            if (emp.entrada_hoy) {
                pintarCard(emp, 'Entrada de hoy ya registrada · ' + emp.entrada_hoy, 'warn');
            } else if (emp.salida_hoy) {
                pintarCard(emp, 'Salida de hoy registrada · ' + emp.salida_hoy, 'warn');
            } else {
                pintarCard(emp, 'Listo para registrar entrada', 'ok');
            }
            return;
        }

        // Modo salida
        if (!emp.entrada_hoy) {
            pintarCard(emp, 'Sin entrada hoy: primero registra la entrada', 'err');
        } else if (emp.salida_hoy) {
            pintarCard(emp, 'Salida de hoy registrada · ' + emp.salida_hoy, 'warn');
        } else {
            pintarCard(emp, 'Entrada ' + emp.entrada_hoy + ' · Listo para registrar salida', 'ok');
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
