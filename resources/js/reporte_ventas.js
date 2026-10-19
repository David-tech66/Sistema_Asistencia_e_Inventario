/* ==========================================
   REPORTE DE VENTAS - PANEL ADMINISTRATIVO (GRUPO MDL)
   Recursos para resources/views/reportes/ventas.blade.php
   ========================================== */

window.toggleDetalle = function(id) {
    const fila = document.getElementById('detalle-' + id);
    if (fila) {
        fila.classList.toggle('hidden');
    }
};

window.aplicarFiltros = function() {
    const desde = document.getElementById('filtro-desde').value;
    const hasta = document.getElementById('filtro-hasta').value;
    const tipo = document.getElementById('filtro-tipo').value;
    const texto = document.getElementById('filtro-texto').value.toLowerCase().trim();

    const filas = document.querySelectorAll('.venta-fila');
    let visibles = 0;

    filas.forEach(function (fila) {
        const fecha = fila.dataset.fecha;
        const coincideFecha = (!desde || fecha >= desde) && (!hasta || fecha <= hasta);
        const coincideTipo = !tipo || fila.dataset.tipo === tipo;
        const coincideTexto = !texto || fila.dataset.buscar.includes(texto);
        const visible = coincideFecha && coincideTipo && coincideTexto;

        fila.classList.toggle('hidden', !visible);
        const detalle = fila.nextElementSibling;
        if (detalle && detalle.classList.contains('detail-row')) {
            if (!visible) detalle.classList.add('hidden');
        }

        if (visible) visibles++;
    });

    const contador = document.getElementById('contador-mostradas');
    if (contador) contador.textContent = visibles;

    const sinResultados = document.getElementById('sin-resultados');
    if (sinResultados) {
        sinResultados.style.display = (visibles === 0) ? 'block' : 'none';
    }
};

window.limpiarFiltros = function() {
    document.getElementById('filtro-desde').value = '';
    document.getElementById('filtro-hasta').value = '';
    document.getElementById('filtro-tipo').value = '';
    document.getElementById('filtro-texto').value = '';
    window.aplicarFiltros();
};