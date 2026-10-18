<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Grupo MDL E.I.R.L.</title>

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
                    <a href="/marcas" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
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
                    <a href="/asistencias" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
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
                        <a href="/reportes/ventas" class="sidebar-item active flex items-center px-3 py-2 text-[13px] text-gray-500">
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

            <!-- Título -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Reporte de Ventas</h2>
                <p class="text-sm text-gray-500 mt-1">Resumen de ventas registradas en el sistema.</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="h-11 w-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-sack-dollar text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Ingresos totales</p>
                        <p class="text-xl font-bold text-gray-900">S/ {{ number_format($totalIngresos, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="h-11 w-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-receipt text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Ventas</p>
                        <p class="text-xl font-bold text-gray-900">{{ $ventas->count() }}</p>
                        <p class="text-[11px] text-gray-400">{{ $totalBoletas }} boleta(s) · {{ $totalFacturas }} factura(s)</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="h-11 w-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-calculator text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Ticket promedio</p>
                        <p class="text-xl font-bold text-gray-900">S/ {{ number_format($promedio, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="h-11 w-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-boxes-stacked text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Unidades vendidas</p>
                        <p class="text-xl font-bold text-gray-900">{{ $unidadesVendidas }}</p>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Desde</label>
                    <input type="date" id="filtro-desde" onchange="aplicarFiltros()"
                           class="px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Hasta</label>
                    <input type="date" id="filtro-hasta" onchange="aplicarFiltros()"
                           class="px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Comprobante</label>
                    <select id="filtro-tipo" onchange="aplicarFiltros()"
                            class="px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                        <option value="">Todos</option>
                        <option value="Boleta">Boleta</option>
                        <option value="Factura">Factura</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Buscar</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" id="filtro-texto" oninput="aplicarFiltros()" placeholder="N° comprobante o cliente..."
                               class="w-full pl-8 pr-4 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                    </div>
                </div>
                <button onclick="limpiarFiltros()"
                        class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Limpiar
                </button>
            </div>

            <!-- TABLA VENTAS -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-table-list text-blue-600"></i>
                        <span>Mostrando <span id="contador-mostradas" class="font-semibold text-gray-800">{{ $ventas->count() }}</span> de {{ $ventas->count() }} venta(s)</span>
                    </div>
                    <span class="text-xs text-gray-400">Haz clic en una fila para ver los productos</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-5 py-3">Comprobante</th>
                                <th class="px-5 py-3">Fecha</th>
                                <th class="px-5 py-3">Cliente</th>
                                <th class="px-5 py-3">Vendedor</th>
                                <th class="px-5 py-3">Tipo</th>
                                <th class="px-5 py-3 text-center">Ítems</th>
                                <th class="px-5 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($ventas as $venta)
                                <tr class="hover:bg-gray-50/70 transition-colors cursor-pointer venta-fila"
                                    data-fecha="{{ $venta->created_at ? $venta->created_at->format('Y-m-d') : '' }}"
                                    data-tipo="{{ $venta->tipo_comprobante }}"
                                    data-buscar="{{ strtolower($venta->numero_comprobante . ' ' . ($venta->cliente?->nombre ?? '')) }}"
                                    onclick="toggleDetalle({{ $venta->id }})">
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center font-mono text-xs text-gray-700 bg-gray-100 px-2 py-1 rounded-lg">{{ $venta->numero_comprobante }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-600">{{ $venta->created_at ? $venta->created_at->format('d/m/Y H:i') : '—' }}</td>
                                    <td class="px-5 py-3.5 font-medium text-gray-800">{{ $venta->cliente?->nombre }}</td>
                                    <td class="px-5 py-3.5 text-gray-500">{{ $venta->user?->nombre_usuario }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center text-[11px] font-semibold {{ $venta->tipo_comprobante === 'Boleta' ? 'text-blue-700 bg-blue-50' : 'text-purple-700 bg-purple-50' }} px-2 py-0.5 rounded-full">{{ $venta->tipo_comprobante }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center text-gray-600">{{ $venta->detalles->sum('cantidad') }}</td>
                                    <td class="px-5 py-3.5 text-right font-semibold text-gray-900">S/ {{ number_format($venta->total, 2) }}</td>
                                </tr>
                                <tr id="detalle-{{ $venta->id }}" class="hidden">
                                    <td colspan="7" class="px-5 py-4 bg-gray-50/70">
                                        <div class="flex items-center gap-2 text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            <i class="fa-solid fa-list text-blue-600"></i> Productos de {{ $venta->numero_comprobante }}
                                        </div>
                                        <div class="rounded-xl border border-gray-100 bg-white overflow-hidden">
                                            <table class="w-full text-xs">
                                                <thead>
                                                    <tr class="bg-gray-50 text-left text-gray-500 uppercase tracking-wider">
                                                        <th class="px-4 py-2">Producto</th>
                                                        <th class="px-4 py-2 text-center">Cantidad</th>
                                                        <th class="px-4 py-2 text-right">P. Unitario</th>
                                                        <th class="px-4 py-2 text-right">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-50">
                                                    @foreach ($venta->detalles as $detalle)
                                                        <tr>
                                                            <td class="px-4 py-2 text-gray-700">{{ $detalle->producto?->nombre ?? 'Producto eliminado' }}</td>
                                                            <td class="px-4 py-2 text-center text-gray-600">{{ $detalle->cantidad }}</td>
                                                            <td class="px-4 py-2 text-right text-gray-600">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                                                            <td class="px-4 py-2 text-right font-semibold text-gray-800">S/ {{ number_format($detalle->subtotal, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr class="bg-gray-50">
                                                        <td colspan="3" class="px-4 py-2 text-right font-bold text-gray-700">Total</td>
                                                        <td class="px-4 py-2 text-right font-bold text-gray-900">S/ {{ number_format($venta->total, 2) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="h-12 w-12 rounded-2xl bg-gray-50 text-gray-300 flex items-center justify-center">
                                                <i class="fa-solid fa-receipt text-xl"></i>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500">No hay ventas registradas</p>
                                            <p class="text-xs text-gray-400">Las ventas realizadas desde el POS aparecerán en este reporte.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <p id="sin-resultados" class="hidden px-5 py-8 text-center text-sm text-gray-400">No se encontraron ventas con esos filtros.</p>
                </div>
            </div>

        </main>
    </div>

    <script>
        function toggleDetalle(id) {
            const fila = document.getElementById('detalle-' + id);
            fila.classList.toggle('hidden');
        }

        function aplicarFiltros() {
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
                if (detalle && detalle.id) {
                    if (!visible) detalle.classList.add('hidden');
                }

                if (visible) visibles++;
            });

            document.getElementById('contador-mostradas').textContent = visibles;
            document.getElementById('sin-resultados').classList.toggle('hidden', visibles !== 0);
        }

        function limpiarFiltros() {
            document.getElementById('filtro-desde').value = '';
            document.getElementById('filtro-hasta').value = '';
            document.getElementById('filtro-tipo').value = '';
            document.getElementById('filtro-texto').value = '';
            aplicarFiltros();
        }
    </script>
</body>
</html>