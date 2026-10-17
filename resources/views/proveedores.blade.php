<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Grupo MDL E.I.R.L.</title>
    
    <!-- Fuentes e Iconos -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

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
                    <i id="inventario-arrow" class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform"></i>
                </button>
                <div id="inventario-submenu" class="hidden flex-col ml-4 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
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
                    <a href="/proveedores" class="sidebar-item active flex items-center px-3 py-2 text-[13px] text-gray-500">
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
                    <i id="general-arrow" class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform"></i>
                </button>
                <div id="general-submenu" class="hidden flex-col ml-4 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
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

            <!-- Título de Sección -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Proveedores</h2>
                <p class="text-sm text-gray-500 mt-1">Listado de empresas proveedoras registradas en el sistema.</p>
            </div>

            <!-- TABLA PROVEEDORES -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fa-solid fa-truck-field text-blue-600"></i>
                        <span>Total: <span class="font-semibold text-gray-800">{{ $proveedores->count() }}</span> proveedor(es)</span>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" placeholder="Buscar proveedor..." class="pl-8 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 w-64">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-5 py-3">#</th>
                                <th class="px-5 py-3">Razón Social</th>
                                <th class="px-5 py-3">RUC</th>
                                <th class="px-5 py-3">Teléfono</th>
                                <th class="px-5 py-3">Fecha de Registro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($proveedores as $proveedor)
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="px-5 py-3.5 text-gray-400 font-medium">{{ $proveedor->id }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-building text-sm"></i>
                                            </div>
                                            <span class="font-semibold text-gray-800">{{ $proveedor->razon_social }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center font-mono text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">{{ $proveedor->ruc }}</span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if ($proveedor->telefono)
                                            <span class="text-gray-600"><i class="fa-solid fa-phone text-[11px] text-gray-400 mr-1.5"></i>{{ $proveedor->telefono }}</span>
                                        @else
                                            <span class="text-gray-300 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-500">{{ $proveedor->created_at ? $proveedor->created_at->format('d/m/Y') : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="h-12 w-12 rounded-2xl bg-gray-50 text-gray-300 flex items-center justify-center">
                                                <i class="fa-solid fa-truck-field text-xl"></i>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500">No hay proveedores registrados</p>
                                            <p class="text-xs text-gray-400">Cuando se registren proveedores, aparecerán en esta lista.</p>
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
</body>
</html>
