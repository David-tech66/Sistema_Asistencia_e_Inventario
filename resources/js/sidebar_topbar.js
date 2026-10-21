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
