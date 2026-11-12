/**
 * Compartido por todas las vistas (login, recuperar, etc.)
 *
 * Protección anti doble-envío: al enviar un formulario nativo se deshabilita
 * su botón de envío para evitar POSTs duplicados por doble clic.
 * Si el envío se cancela (p. ej. un onsubmit que retorna false), se reactiva.
 */
document.addEventListener('submit', function (event) {
    if (event.defaultPrevented) return;

    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;

    const btn = form.querySelector('button[type="submit"]');
    if (btn) btn.disabled = true;
});
