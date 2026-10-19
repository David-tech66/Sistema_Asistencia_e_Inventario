/* ==========================================
   CLIENTES - PANEL ADMINISTRATIVO (GRUPO MDL)
   Recursos para resources/views/clientes.blade.php
   ========================================== */

window.abrirModal = function (id) {
    document.getElementById(id).classList.remove("hidden");
};

window.cerrarModal = function (id) {
    document.getElementById(id).classList.add("hidden");
};

window.editarCliente = function (id) {
    fetch("/clientes/" + id + "/edit")
        .then(function (res) {
            return res.json();
        })
        .then(function (c) {
            const form = document.getElementById("form-cliente-editar");
            form.action = "/clientes/" + c.id;
            form.querySelector('[name="documento"]').value = c.documento || "";
            form.querySelector('[name="nombre"]').value = c.nombre || "";
            form.querySelector('[name="telefono"]').value = c.telefono || "";
            window.abrirModal("modal-cliente-editar");
        })
        .catch(function () {
            alert("No se pudo cargar el cliente.");
        });
};

window.eliminarCliente = function (id, nombre) {
    document.getElementById("form-eliminar").action = "/clientes/" + id;
    document.getElementById("eliminar-nombre").textContent = nombre;
    window.abrirModal("modal-eliminar");
};

window.filtrarClientes = function () {
    const texto = document
        .getElementById("buscar-cliente")
        .value.toLowerCase()
        .trim();
    const filas = document.querySelectorAll(".cliente-fila");
    let visibles = 0;

    filas.forEach(function (fila) {
        const coincide = fila.dataset.buscar.includes(texto);
        if (coincide) {
            fila.style.display = "";
            visibles++;
        } else {
            fila.style.display = "none";
        }
    });

    document.getElementById("sin-resultados").style.display =
        texto && visibles === 0 ? "block" : "none";
};

document.addEventListener("DOMContentLoaded", function () {
    // Reabrir modal si hay errores en el formulario
    const errores = document.getElementById("errores-validacion-clientes");
    if (errores && errores.value === "1") {
        window.abrirModal("modal-cliente-crear");
    }

    // Cerrar modales con la tecla Escape (como en productos)
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            document
                .querySelectorAll(".modal-overlay")
                .forEach(function (modal) {
                    modal.classList.add("hidden");
                });
        }
    });
});
