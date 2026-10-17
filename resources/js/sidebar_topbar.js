window.toggleMenu = function (menuId) {
            const submenu = document.getElementById(menuId + '-submenu');
            const arrow = document.getElementById(menuId + '-arrow');
            
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                submenu.classList.add('flex');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('flex');
                arrow.style.transform = 'rotate(0deg)';
            }
        };

        // Mantener abierto el submenú del módulo activo (según la URL actual)
        document.addEventListener('DOMContentLoaded', function () {
            const ruta = window.location.pathname;
            const grupos = {
                inventario: ['/productos', '/categorias', '/marcas', '/ventas', '/clientes', '/proveedores'],
                general: ['/users', '/asistencias', '/reportes'],
            };

            Object.keys(grupos).some(function (grupo) {
                const coincide = grupos[grupo].some(function (rutaGrupo) {
                    return ruta === rutaGrupo || ruta.indexOf(rutaGrupo + '/') === 0;
                });

                if (!coincide) return false;

                const submenu = document.getElementById(grupo + '-submenu');
                const arrow = document.getElementById(grupo + '-arrow');

                if (submenu) {
                    submenu.classList.remove('hidden');
                    submenu.classList.add('flex');
                }
                if (arrow) {
                    arrow.style.transform = 'rotate(180deg)';
                }

                return true;
            });
        });

