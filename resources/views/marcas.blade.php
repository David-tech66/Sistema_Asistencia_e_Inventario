<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Grupo MDL E.I.R.L.</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fuentes e Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/sidebar_topbar.css', 'resources/js/sidebar_topbar.js'])
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-[260px] bg-white flex flex-col border-r border-gray-200 z-20 shrink-0 overflow-y-auto">
        
        <!-- Logo -->
        <div class="h-[72px] flex items-center px-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <!-- Logo ladrillos -->
                <div class="flex flex-col gap-[2px]">
                    <div class="flex gap-[2px]">
                        <div class="w-[14px] h-[10px] bg-blue-600 rounded-[2px]"></div>
                        <div class="w-[14px] h-[10px] bg-red-600 rounded-[2px]"></div>
                        <div class="w-[14px] h-[10px] bg-gray-800 rounded-[2px]"></div>
                    </div>
                    <div class="flex gap-[2px]">
                        <div class="w-[14px] h-[10px] bg-gray-800 rounded-[2px]"></div>
                        <div class="w-[14px] h-[10px] bg-blue-600 rounded-[2px]"></div>
                        <div class="w-[14px] h-[10px] bg-red-600 rounded-[2px]"></div>
                    </div>
                    <div class="flex gap-[2px]">
                        <div class="w-[14px] h-[10px] bg-red-600 rounded-[2px]"></div>
                        <div class="w-[14px] h-[10px] bg-gray-800 rounded-[2px]"></div>
                        <div class="w-[14px] h-[10px] bg-blue-600 rounded-[2px]"></div>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-[15px] font-extrabold tracking-tight text-gray-900 leading-none">GRUPO MD</span>
                    <span class="text-[8px] text-gray-500 font-medium tracking-wider mt-[1px]">E.I.R.L.</span>
                </div>
            </div>
        </div>
        
        <!-- Navegación -->
        <nav class="flex-1 py-4 px-3 space-y-1">
            
            <!-- Dashboards -->
            <a href="/dashboard" class="sidebar-item flex items-center px-4 py-2.5 text-sm text-gray-600">
                <i class="fa-solid fa-chart-pie w-5 mr-3 text-sm"></i> Dashboards
            </a>

            <!-- Inventario (desplegable) -->
            <div class="pt-3">
                <button onclick="toggleMenu('inventario')" class="sidebar-item w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fa-solid fa-boxes-stacked w-5 mr-3 text-sm"></i> Inventario
                    </div>
                    <i id="inventario-arrow" class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform" style="{{ request()->is('productos', 'categorias', 'marcas', 'ventas', 'clientes', 'proveedores') ? 'transform: rotate(180deg);' : '' }}"></i>
                </button>
                <div id="inventario-submenu" class="{{ request()->is('productos', 'categorias', 'marcas', 'ventas', 'clientes', 'proveedores') ? 'flex' : 'hidden' }} flex-col ml-4 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
                    <a href="/productos" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-tag w-4 mr-2 text-[11px]"></i> Productos
                    </a>
                    <a href="/categorias" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-layer-group w-4 mr-2 text-[11px]"></i> Categorías
                    </a>
                    <a href="/marcas" class="sidebar-item active flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-copyright w-4 mr-2 text-[11px]"></i> Marcas
                    </a>
                    <a href="/ventas" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-shopping-cart w-4 mr-2 text-[11px]"></i> Ventas
                    </a>
                    <a href="/clientes" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-user-group w-4 mr-2 text-[11px]"></i> Clientes
                    </a>
                    <a href="/proveedores" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-truck-field w-4 mr-2 text-[11px]"></i> Proveedores
                    </a>
                </div>
            </div>

            <!-- General (desplegable) -->
            <div class="pt-3">
                <button onclick="toggleMenu('general')" class="sidebar-item w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fa-solid fa-gear w-5 mr-3 text-sm"></i> General
                    </div>
                    <i id="general-arrow" class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform" style="{{ request()->is('users', 'asistencias', 'reportes', 'reportes/*') ? 'transform: rotate(180deg);' : '' }}"></i>
                </button>
                <div id="general-submenu" class="{{ request()->is('users', 'asistencias', 'reportes', 'reportes/*') ? 'flex' : 'hidden' }} flex-col ml-4 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
                    <a href="/users" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-id-badge w-4 mr-2 text-[11px]"></i> Empleados
                    </a>
                    <a href="/asistencias   " class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-user-check w-4 mr-2 text-[11px]"></i> Asistencias
                    </a>
                    <div class="pt-2">
                        <div class="px-3 py-1 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Reportes</div>
                        <a href="/reportes/productos" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                            <i class="fa-solid fa-file-lines w-4 mr-2 text-[11px]"></i> Reportes Productos
                        </a>
                        <a href="/reportes/asistencias" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                            <i class="fa-solid fa-file-lines w-4 mr-2 text-[11px]"></i> Reportes asistencias
                        </a>
                        <a href="/reportes/ventas" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                            <i class="fa-solid fa-file-lines w-4 mr-2 text-[11px]"></i> Reportes Ventas
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Bottom: Configuración y Cerrar sesión -->
        <div class="p-3 border-t border-gray-100 space-y-1">
            <a href="#" class="sidebar-item flex items-center px-4 py-2.5 text-sm text-gray-600">
                <i class="fa-solid fa-sliders w-5 mr-3 text-sm"></i> Configuración
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="sidebar-item flex items-center w-full px-4 py-2.5 text-sm text-gray-600 bg-transparent border-none cursor-pointer text-left">
                    <i class="fa-solid fa-right-from-bracket w-5 mr-3 text-sm"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTAINER -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- TOPBAR -->
        <header class="h-[72px] bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10 shrink-0">
            <div class="flex items-center gap-4">
                <button class="text-gray-500 hover:text-blue-600 transition-colors">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl font-bold text-blue-600">Panel Administrativo</h1>
            </div>
            <div class="flex items-center gap-4">
                <button class="relative text-gray-400 hover:text-blue-600 transition-colors">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center">3</span>
                </button>
                <div class="h-8 w-px bg-gray-200"></div>
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Admin+MDL&background=2563eb&color=fff" alt="Avatar" class="h-9 w-9 rounded-full object-cover border-2 border-blue-100">
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-gray-800 leading-tight">Administrador</p>
                        <p class="text-[11px] text-gray-400">Administrador</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto bg-gray-100 p-6">

            <!-- Título + acción -->
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Marcas</h2>
                    <p class="text-sm text-gray-500 mt-1">Administra las marcas de los productos.</p>
                </div>
                <button onclick="abrirModal('modal-marca-crear')"
                        class="px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-plus"></i> Nueva marca
                </button>
            </div>

            <!-- Alertas -->
            @if (session('exito'))
                <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
                    <i class="fa-solid fa-circle-check"></i> {{ session('exito') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
                    <p class="font-semibold flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> Revisa los siguientes errores:</p>
                    <ul class="list-disc list-inside mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- TABLA MARCAS -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fa-solid fa-copyright text-blue-600"></i>
                        <span>Total: <span class="font-semibold text-gray-800">{{ $marcas->count() }}</span> marca(s)</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-5 py-3">Nombre</th>
                                <th class="px-5 py-3">Descripción</th>
                                <th class="px-5 py-3">Fecha de Registro</th>
                                <th class="px-5 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($marcas as $marca)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-copyright text-sm"></i>
                                            </div>
                                            <span class="font-semibold text-gray-800">{{ $marca->nombre }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if ($marca->descripcion)
                                            <span class="text-gray-600">{{ $marca->descripcion }}</span>
                                        @else
                                            <span class="text-gray-300 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-500">{{ $marca->created_at ? $marca->created_at->format('d/m/Y') : '—' }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex gap-2">
                                            <button onclick="editarMarca({{ $marca->id }})"
                                                    class="px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors flex items-center gap-1.5">
                                                <i class="fa-solid fa-pen text-[10px]"></i> Editar
                                            </button>
                                            <button onclick="eliminarMarca({{ $marca->id }}, '{{ addslashes($marca->nombre) }}')"
                                                    class="px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors flex items-center gap-1.5">
                                                <i class="fa-solid fa-trash text-[10px]"></i> Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="h-12 w-12 rounded-2xl bg-gray-50 text-gray-300 flex items-center justify-center">
                                                <i class="fa-solid fa-copyright text-xl"></i>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500">No hay marcas registradas</p>
                                            <p class="text-xs text-gray-400">Registra la primera marca con el botón "Nueva marca".</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- MODAL: Registrar marca -->
    <div id="modal-marca-crear" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" onclick="cerrarModal('modal-marca-crear')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-copyright"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Registrar nueva marca</h3>
                </div>
                <button onclick="cerrarModal('modal-marca-crear')" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form method="POST" action="/marcas">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input type="text" name="nombre" required maxlength="100"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                               placeholder="Ej: Lenovo, Samsung, Kingston">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcion" maxlength="255" rows="2"
                                  class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                                  placeholder="Descripción breve (opcional)"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="cerrarModal('modal-marca-crear')"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Editar marca -->
    <div id="modal-marca-editar" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" onclick="cerrarModal('modal-marca-editar')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-pen"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Editar marca</h3>
                </div>
                <button onclick="cerrarModal('modal-marca-editar')" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form id="form-marca-editar" method="POST" action="/marcas/0">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input type="text" name="nombre" required maxlength="100"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcion" maxlength="255" rows="2"
                                  class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="cerrarModal('modal-marca-editar')"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Confirmar eliminación -->
    <div id="modal-eliminar" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" onclick="cerrarModal('modal-eliminar')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-center">
            <div class="h-14 w-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-trash text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Eliminar marca</h3>
            <p class="text-sm text-gray-500 mt-2">¿Seguro que deseas eliminar <span id="eliminar-nombre" class="font-semibold text-gray-800"></span>? Esta acción no se puede deshacer.</p>
            <form id="form-eliminar" method="POST" action="/marcas/0">
                @csrf
                @method('DELETE')
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="cerrarModal('modal-eliminar')"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm">
                        Sí, eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function cerrarModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function editarMarca(id) {
            fetch('/marcas/' + id + '/edit')
                .then(function (res) { return res.json(); })
                .then(function (m) {
                    const form = document.getElementById('form-marca-editar');
                    form.action = '/marcas/' + m.id;
                    form.querySelector('[name="nombre"]').value = m.nombre || '';
                    form.querySelector('[name="descripcion"]').value = m.descripcion || '';
                    abrirModal('modal-marca-editar');
                })
                .catch(function () { alert('No se pudo cargar la marca.'); });
        }

        function eliminarMarca(id, nombre) {
            document.getElementById('form-eliminar').action = '/marcas/' + id;
            document.getElementById('eliminar-nombre').textContent = nombre;
            abrirModal('modal-eliminar');
        }

        // Si se volvió con errores de validación, reabrir el formulario de registro
        @if ($errors->any())
            abrirModal('modal-marca-crear');
        @endif
    </script>
</body>
</html>
