<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias - Grupo MDL E.I.R.L.</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/sidebar_topbar.css', 'resources/js/sidebar_topbar.js'])
</head>

<body class="bg-gray-100 text-gray-800 font-sans antialiased flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-[260px] bg-white flex flex-col border-r border-gray-200 z-20 shrink-0 overflow-y-auto">

        <div class="h-[72px] flex items-center px-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
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

        <!-- NAVEGACIÓN -->
        <nav class="flex-1 py-4 px-3 space-y-1">

            <a href="/dashboard" class="sidebar-item flex items-center px-4 py-2.5 text-sm text-gray-600">
                <i class="fa-solid fa-chart-pie w-5 mr-3"></i> Dashboards
            </a>

            <div class="pt-3">
                <button type="button" onclick="toggleMenu('inventario')"
                    class="sidebar-item w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fa-solid fa-boxes-stacked w-5 mr-3"></i> Inventario
                    </div>
                    <i id="inventario-arrow" class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                </button>

                <div id="inventario-submenu" class="hidden flex-col ml-4 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
                    <a href="/productos" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-tag w-4 mr-2"></i> Productos
                    </a>
                    <a href="/categorias" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-layer-group w-4 mr-2"></i> Categorías
                    </a>
                    <a href="/marcas" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-copyright w-4 mr-2"></i> Marcas
                    </a>
                    <a href="/ventas" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-shopping-cart w-4 mr-2"></i> Ventas
                    </a>
                    <a href="/clientes" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-user-group w-4 mr-2"></i> Clientes
                    </a>
                    <a href="/proveedores" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-truck-field w-4 mr-2"></i> Proveedores
                    </a>
                </div>
            </div>

            <div class="pt-3">
                <button type="button" onclick="toggleMenu('general')"
                    class="sidebar-item w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fa-solid fa-gear w-5 mr-3"></i> General
                    </div>
                    <i id="general-arrow" class="fa-solid fa-chevron-down text-[10px] text-gray-400"
                        style="transform: rotate(180deg);"></i>
                </button>

                <div id="general-submenu" class="flex flex-col ml-4 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
                    <a href="/users" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-id-badge w-4 mr-2"></i> Empleados
                    </a>

                    <a href="/asistencias" class="sidebar-item active flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-user-check w-4 mr-2"></i> Asistencias
                    </a>

                    <div class="pt-2">
                        <div class="px-3 py-1 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Reportes</div>
                        <a href="/reportes/productos" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                            <i class="fa-solid fa-file-lines w-4 mr-2"></i> Reportes Productos
                        </a>
                        <a href="/reportes/asistencias" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                            <i class="fa-solid fa-file-lines w-4 mr-2"></i> Reportes asistencias
                        </a>
                        <a href="/reportes/ventas" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                            <i class="fa-solid fa-file-lines w-4 mr-2"></i> Reportes Ventas
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- CERRAR SESIÓN -->
        <div class="p-3 border-t border-gray-100 space-y-1">
            <a href="#" class="sidebar-item flex items-center px-4 py-2.5 text-sm text-gray-600">
                <i class="fa-solid fa-sliders w-5 mr-3"></i> Configuración
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="sidebar-item flex items-center w-full px-4 py-2.5 text-sm text-gray-600 bg-transparent border-none cursor-pointer text-left">
                    <i class="fa-solid fa-right-from-bracket w-5 mr-3"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <!-- TOPBAR -->
        <header class="h-[72px] bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10 shrink-0">
            <div class="flex items-center gap-4">
                <button type="button" class="text-gray-500 hover:text-blue-600 transition-colors">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl font-bold text-blue-600">Panel Administrativo</h1>
            </div>

            <div class="flex items-center gap-4">
                <button type="button" class="relative text-gray-400 hover:text-blue-600">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center">3</span>
                </button>

                <div class="h-8 w-px bg-gray-200"></div>

                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Admin+MDL&background=2563eb&color=fff"
                        alt="Avatar" class="h-9 w-9 rounded-full object-cover border-2 border-blue-100">

                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-gray-800 leading-tight">Administrador</p>
                        <p class="text-[11px] text-gray-400">Administrador</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN -->
        <main class="flex-1 overflow-y-auto bg-gray-100 p-6">

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Control de Asistencias</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Registra las entradas y consulta las asistencias del personal.
                </p>
            </div>

            <!-- MENSAJE DE ÉXITO -->
            @if (session('success'))
                <div class="mb-5 p-4 rounded-lg border border-green-200 bg-green-50 text-green-800">
                    <i class="fa-solid fa-circle-check mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- MENSAJE DE ERROR -->
            @if (session('error'))
                <div class="mb-5 p-4 rounded-lg border border-red-200 bg-red-50 text-red-800">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-4 rounded-lg border border-red-200 bg-red-50 text-red-800">
                    <p class="font-semibold mb-1">No se pudo registrar la asistencia:</p>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- FORMULARIO DE REGISTRO -->
            <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <div class="flex items-center gap-3 mb-5">
                    <div class="h-11 w-11 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-user-clock text-xl"></i>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Registrar entrada</h3>
                        <p class="text-sm text-gray-500">Selecciona al trabajador para registrar su asistencia.</p>
                    </div>
                </div>

                @if ($empleados->isEmpty())
                    <div class="p-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                        No hay usuarios registrados. Primero registra un empleado en el módulo Empleados.
                    </div>
                @else
                    <form action="/asistencias/entrada" method="POST"
                        class="flex flex-col md:flex-row gap-4 items-end">
                        @csrf

                        <div class="flex-1 w-full">
                            <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Seleccionar empleado
                            </label>

                            <select name="user_id" id="user_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecciona un empleado</option>

                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}"
                                        {{ old('user_id') == $empleado->id ? 'selected' : '' }}>
                                        {{ $empleado->nombre_usuario }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full md:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                            <i class="fa-solid fa-clock mr-2"></i>
                            Registrar entrada
                        </button>
                    </form>
                @endif

                <p class="text-xs text-gray-500 mt-4">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    La fecha y la hora de entrada se registran automáticamente.
                </p>
            </section>

            <!-- TABLA DE ASISTENCIAS -->
            <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Asistencias registradas</h3>
                        <p class="text-sm text-gray-500 mt-1">Historial de entradas del personal.</p>
                    </div>

                    <div class="text-sm text-gray-500">
                        Total de registros:
                        <span class="font-bold text-blue-600">{{ $asistencias->count() }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4">#</th>
                                <th class="px-6 py-4">Empleado</th>
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Hora de entrada</th>
                                <th class="px-6 py-4">Hora de salida</th>
                                <th class="px-6 py-4">Estado</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($asistencias as $asistencia)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $asistencia->id }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                        {{ $asistencia->user?->nombre_usuario ?? 'Usuario no disponible' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $asistencia->fecha }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $asistencia->hora_entrada ?? '--:--:--' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $asistencia->hora_salida ?? 'Pendiente' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($asistencia->hora_entrada)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                <i class="fa-solid fa-circle-check mr-1"></i>
                                                Presente
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                                Sin entrada
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 mb-3">
                                            <i class="fa-solid fa-calendar-xmark text-4xl"></i>
                                        </div>
                                        <p class="font-semibold text-gray-600">No hay asistencias registradas</p>
                                        <p class="text-gray-400 mt-1">Registra la primera entrada utilizando el formulario.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <p class="text-xs text-gray-400 mt-5 text-center">
                Grupo MDL E.I.R.L. — Módulo de Recursos Humanos
            </p>

        </main>
    </div>

</body>
</html>
