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

document.addEventListener("DOMContentLoaded", () => {
    lucide.createIcons();
});

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

// Mantener abierto el submenú del módulo activo (según la URL actual)
document.addEventListener("DOMContentLoaded", function () {
    const ruta = window.location.pathname;
    const grupos = {
        inventario: [
            "/productos",
            "/categorias",
            "/marcas",
            "/ventas",
            "/clientes",
            "/proveedores",
        ],
        general: ["/users", "/asistencias", "/reportes"],
    };

    Object.keys(grupos).some(function (grupo) {
        const coincide = grupos[grupo].some(function (rutaGrupo) {
            return ruta === rutaGrupo || ruta.indexOf(rutaGrupo + "/") === 0;
        });

        if (!coincide) return false;

        const submenu = document.getElementById(grupo + "-submenu");
        const arrow = document.getElementById(grupo + "-arrow");

        if (submenu) {
            submenu.classList.add("open");
        }
        if (arrow) {
            arrow.classList.add("rotated");
        }

        return true;
    });
});