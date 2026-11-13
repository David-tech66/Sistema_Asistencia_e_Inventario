// Abrir/cerrar submenús del sidebar (Inventario, General)
window.toggleMenu = function (menuId) {
    const submenu = document.getElementById(menuId + "-submenu");
    const arrow = document.getElementById(menuId + "-arrow");

    if (submenu) {
        submenu.classList.toggle("open");
    }
    if (arrow) {
        arrow.classList.toggle("rotated");
    }
};

// Mostrar/ocultar el sidebar en móviles
function toggleSidebar() {
    const sidebar = document.getElementById("app-sidebar");
    const backdrop = document.getElementById("sidebar-backdrop");

    if (sidebar && backdrop) {
        sidebar.classList.toggle("mobile-open");
        backdrop.classList.toggle("active");
    }
}

// Hacer la función accesible globalmente
window.toggleSidebar = toggleSidebar;

/**
 * Reabre un modal tras un fallo de validación y, si la página muestra una
 * lista de errores (.alert-list), la copia dentro del modal para que el
 * usuario la vea sin tener que cerrarlo (antes quedaba fuera de pantalla).
 */
window.reabrirModalConErrores = function (modalId) {
    window.abrirModal(modalId);

    const modal = document.getElementById(modalId);
    const alertList = document.querySelector('.alert-list');

    if (!modal || !alertList || !alertList.innerText.trim()) return;
    if (modal.querySelector('.modal-errors')) return; // ya inyectado

    const box = document.createElement('div');
    box.className = 'alert-list modal-errors';
    box.innerHTML = alertList.innerHTML;

    const header = modal.querySelector('.modal-header');
    if (header) {
        header.insertAdjacentElement('afterend', box);
    } else {
        modal.insertBefore(box, modal.firstChild);
    }
};

/**
 * Protección anti doble-envío: al enviar un formulario nativo se deshabilita
 * su botón de envío para evitar POSTs duplicados por doble clic.
 * Si el envío se cancela (p. ej. un onsubmit que retorna false), no se toca.
 */
document.addEventListener('submit', function (event) {
    if (event.defaultPrevented) return;

    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;

    const btn = form.querySelector('button[type="submit"]');
    if (btn) btn.disabled = true;
});

/**
 * Reloj en vivo del topbar: muestra la fecha y la hora con minutos y
 * segundos (ej. 22:41:07 / vie, 13 nov 2026). Se actualiza cada segundo.
 */
(function () {
    const fechaEl = document.getElementById('topbar-clock-date');
    const horaEl = document.getElementById('topbar-clock-time');

    if (!fechaEl || !horaEl) return;

    const fmtHora = new Intl.DateTimeFormat('es-PE', {
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });

    const fmtFecha = new Intl.DateTimeFormat('es-PE', {
        weekday: 'short',
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });

    function tick() {
        const ahora = new Date();
        horaEl.textContent = fmtHora.format(ahora);
        fechaEl.textContent = fmtFecha.format(ahora);
    }

    tick();
    setInterval(tick, 1000);
})();
