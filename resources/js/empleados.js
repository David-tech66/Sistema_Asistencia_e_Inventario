/* ==========================================
   EMPLEADOS - PANEL ADMINISTRATIVO (GRUPO MDL)
   Recursos para resources/views/empleados.blade.php
   ========================================== */

window.abrirModal = function (id) {
    document.getElementById(id).classList.remove('hidden');
};

window.cerrarModal = function (id) {
    document.getElementById(id).classList.add('hidden');
};

window.buscarEmpleado = function () {
    const texto = document.getElementById('buscar-empleado').value.toLowerCase().trim();

    document.querySelectorAll('#tabla-empleados tr').forEach(function (fila) {
        fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
    });
};

document.addEventListener('DOMContentLoaded', function () {
    const errores = document.getElementById('errores-validacion');
    if (errores && errores.value === '1') {
        window.abrirModal('modal-empleado-crear');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal-overlay').forEach(function (modal) {
                modal.classList.add('hidden');
            });
        }
    });
});
