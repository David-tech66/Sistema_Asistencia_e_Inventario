/* ==========================================
   REPORTE DE ASISTENCIAS - PANEL ADMINISTRATIVO (GRUPO MDL)
   Recursos para resources/views/reporte_asistencias.blade.php
   Filtros de solo lectura (no hay acciones sobre los registros).
   ========================================== */

window.aplicarFiltros = function () {
    const desde = document.getElementById('filtro-desde').value;
    const hasta = document.getElementById('filtro-hasta').value;
    const estado = document.getElementById('filtro-estado').value;
    const texto = document.getElementById('filtro-texto').value.toLowerCase().trim();

    const filas = document.querySelectorAll('.asistencia-fila');
    let visibles = 0;

    filas.forEach(function (fila) {
        const fecha = fila.dataset.fecha;
        const coincideFecha = (!desde || fecha >= desde) && (!hasta || fecha <= hasta);
        const coincideTexto = !texto || fila.dataset.buscar.includes(texto);

        let coincideEstado = true;
        if (estado === 'sin-salida') {
            coincideEstado = fila.dataset.salida === '0' && fila.dataset.estado !== 'sin';
        } else if (estado) {
            coincideEstado = fila.dataset.estado === estado;
        }

        const visible = coincideFecha && coincideTexto && coincideEstado;
        fila.classList.toggle('hidden', !visible);
        if (visible) visibles++;
    });

    const contador = document.getElementById('contador-mostradas');
    if (contador) contador.textContent = visibles;

    const sinResultados = document.getElementById('sin-resultados');
    if (sinResultados) {
        sinResultados.classList.toggle('hidden', visibles !== 0);
    }
};

window.limpiarFiltros = function () {
    document.getElementById('filtro-desde').value = '';
    document.getElementById('filtro-hasta').value = '';
    document.getElementById('filtro-estado').value = '';
    document.getElementById('filtro-texto').value = '';
    window.aplicarFiltros();
};
