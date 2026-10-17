
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados - Grupo MDL E.I.R.L.</title>

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

        <nav class="flex-1 py-4 px-3 space-y-1">

            <a href="/dashboard" class="sidebar-item flex items-center px-4 py-2.5 text-sm text-gray-600">
                <i class="fa-solid fa-chart-pie w-5 mr-3"></i> Dashboards
            </a>

            <!-- INVENTARIO -->
            <div class="pt-3">
                <button onclick="toggleMenu('inventario')" class="sidebar-item w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-600">
                    <span><i class="fa-solid fa-boxes-stacked w-5 mr-3"></i> Inventario</span>
                    <i id="inventario-arrow" class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                </button>

                <div id="inventario-submenu" class="hidden flex-col ml-4 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
                    <a href="/productos" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500"><i class="fa-solid fa-tag w-4 mr-2"></i> Productos</a>
                    <a href="/categorias" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500"><i class="fa-solid fa-layer-group w-4 mr-2"></i> Categorías</a>
                    <a href="/marcas" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500"><i class="fa-solid fa-copyright w-4 mr-2"></i> Marcas</a>
                    <a href="/ventas" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500"><i class="fa-solid fa-shopping-cart w-4 mr-2"></i> Ventas</a>
                    <a href="/clientes" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500"><i class="fa-solid fa-user-group w-4 mr-2"></i> Clientes</a>
                    <a href="/proveedores" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500"><i class="fa-solid fa-truck-field w-4 mr-2"></i> Proveedores</a>
                </div>
            </div>

            <!-- GENERAL -->
            <div class="pt-3">
                <button onclick="toggleMenu('general')" class="sidebar-item w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-600">
                    <span><i class="fa-solid fa-gear w-5 mr-3"></i> General</span>
                    <i id="general-arrow" class="fa-solid fa-chevron-down text-[10px] text-gray-400" style="transform: rotate(180deg);"></i>
                </button>

                <div id="general-submenu" class="flex flex-col ml-4 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">
                    <a href="/users" class="sidebar-item active flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-id-badge w-4 mr-2"></i> Empleados
                    </a>

                    <a href="/asistencias" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500">
                        <i class="fa-solid fa-user-check w-4 mr-2"></i> Asistencias
                    </a>

                    <div class="pt-2">
                        <div class="px-3 py-1 text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Reportes</div>
                        <a href="/reportes/productos" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500"><i class="fa-solid fa-file-lines w-4 mr-2"></i> Reportes Productos</a>
                        <a href="/reportes/asistencias" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500"><i class="fa-solid fa-file-lines w-4 mr-2"></i> Reportes asistencias</a>
                        <a href="/reportes/ventas" class="sidebar-item flex items-center px-3 py-2 text-[13px] text-gray-500"><i class="fa-solid fa-file-lines w-4 mr-2"></i> Reportes Ventas</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- CERRAR SESIÓN -->
        <div class="p-3 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-item flex items-center w-full px-4 py-2.5 text-sm text-gray-600 bg-transparent border-none cursor-pointer text-left">
                    <i class="fa-solid fa-right-from-bracket w-5 mr-3"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <!-- TOPBAR -->
        <header class="h-[72px] bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center gap-4">
                <i class="fa-solid fa-bars text-xl text-gray-500"></i>
                <h1 class="text-xl font-bold text-blue-600">Panel Administrativo</h1>
            </div>

            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=Admin+MDL&background=2563eb&color=fff" alt="Avatar" class="h-9 w-9 rounded-full">
                <div>
                    <p class="text-sm font-semibold text-gray-800">Administrador</p>
                    <p class="text-[11px] text-gray-400">Gestión del sistema</p>
                </div>
            </div>
        </header>

        <!-- ÁREA DE TRABAJO -->
        <main class="flex-1 overflow-y-auto bg-gray-100 p-6">

            <!-- ENCABEZADO -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Gestión de empleados</h2>
                    <p class="text-sm text-gray-500 mt-1">Administra trabajadores, practicantes y sus accesos.</p>
                </div>

                <button type="button" onclick="document.getElementById('modalNuevo').classList.remove('hidden')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg text-sm font-semibold shadow">
                    <i class="fa-solid fa-plus mr-2"></i> Nuevo empleado
                </button>
            </div>

            <!-- MENSAJES -->
            @if(session('success'))
                <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 border border-green-200">
                    <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 border border-red-200">
                    <p class="font-semibold mb-2">Revisa los siguientes errores:</p>
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- TARJETAS RESUMEN -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total de cuentas</p>
                            <p class="text-2xl font-bold mt-2">{{ $users->count() }}</p>
                        </div>
                        <i class="fa-solid fa-users text-3xl text-blue-500"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Cuentas activas</p>
                            <p class="text-2xl font-bold mt-2">{{ $users->where('estado', true)->count() }}</p>
                        </div>
                        <i class="fa-solid fa-user-check text-3xl text-green-500"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Cuentas inactivas</p>
                            <p class="text-2xl font-bold mt-2">{{ $users->where('estado', false)->count() }}</p>
                        </div>
                        <i class="fa-solid fa-user-slash text-3xl text-red-500"></i>
                    </div>
                </div>

            </div>

            <!-- TABLA -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="p-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-gray-900">Personal registrado</h3>
                        <p class="text-sm text-gray-500 mt-1">Listado de cuentas del sistema.</p>
                    </div>

                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
                        <input id="buscarEmpleado" type="text" placeholder="Buscar empleado..."
                            class="border border-gray-300 rounded-lg pl-9 pr-4 py-2 text-sm w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-5 py-4">ID</th>
                                <th class="px-5 py-4">Usuario</th>
                                <th class="px-5 py-4">Correo</th>
                                <th class="px-5 py-4">Rol</th>
                                <th class="px-5 py-4">Estado</th>
                                <th class="px-5 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody id="tablaEmpleados" class="divide-y divide-gray-100">

                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-5 py-4">{{ $user->id }}</td>

                                    <td class="px-5 py-4 font-semibold text-gray-800">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                                {{ strtoupper(substr($user->nombre_usuario ?? 'U', 0, 1)) }}
                                            </div>
                                            {{ $user->nombre_usuario }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-gray-600">{{ $user->email }}</td>

                                    <td class="px-5 py-4">
                                        @if($user->rol === 'admin')
                                            <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">Administrador</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">{{ ucfirst($user->rol ?? 'empleado') }}</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($user->estado)
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Activo</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Inactivo</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex justify-center items-center gap-2">

                                            <button type="button"
                                                onclick="document.getElementById('modalEditar{{ $user->id }}').classList.remove('hidden')"
                                                class="px-3 py-2 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200 text-xs font-semibold">
                                                <i class="fa-solid fa-pen mr-1"></i> Editar
                                            </button>

                                            <form action="/users/{{ $user->id }}/estado" method="POST"
                                                onsubmit="return confirm('¿Deseas cambiar el estado de esta cuenta?')">
                                                @csrf
                                                @method('PATCH')

                                                @if($user->estado)
                                                    <button type="submit" class="px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 text-xs font-semibold">
                                                        Desactivar
                                                    </button>
                                                @else
                                                    <button type="submit" class="px-3 py-2 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 text-xs font-semibold">
                                                        Activar
                                                    </button>
                                                @endif
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                                        <i class="fa-solid fa-users text-4xl mb-3 text-gray-300"></i>
                                        <p class="font-semibold">No hay empleados registrados</p>
                                        <p class="text-sm mt-1">Utiliza el botón «Nuevo empleado» para registrar el primero.</p>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-3 border-t border-gray-100 text-xs text-gray-500">
                    Registros encontrados: {{ $users->count() }}
                </div>
            </div>

        </main>
    </div>

    <!-- MODAL NUEVO EMPLEADO -->
    <div id="modalNuevo" class="hidden fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            <div class="p-5 border-b flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold">Registrar empleado</h3>
                    <p class="text-sm text-gray-500">Completa los datos de acceso.</p>
                </div>
                <button type="button" onclick="cerrarModal('modalNuevo')" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            </div>

            <form action="/users" method="POST">
                @csrf

                <div class="p-5 space-y-4">

                    <div>
                        <label class="block text-sm font-medium mb-1">Nombre de usuario</label>
                        <input type="text" name="nombre_usuario" required maxlength="150"
                            value="{{ old('nombre_usuario') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Correo electrónico</label>
                        <input type="email" name="email" required maxlength="150"
                            value="{{ old('email') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Contraseña inicial</label>
                        <input type="password" name="password" required minlength="6"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Rol de acceso</label>
                        <select name="rol" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="empleado">Empleado</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

                </div>

                <div class="p-5 bg-gray-50 border-t flex justify-end gap-3">
                    <button type="button" onclick="cerrarModal('modalNuevo')" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-semibold">
                        <i class="fa-solid fa-floppy-disk mr-1"></i> Guardar empleado
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODALES EDITAR -->
    @foreach($users as $user)
        <div id="modalEditar{{ $user->id }}" class="hidden fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

                <div class="p-5 border-b flex items-center justify-between">
                    <h3 class="text-lg font-bold">Editar empleado #{{ $user->id }}</h3>
                    <button type="button" onclick="cerrarModal('modalEditar{{ $user->id }}')" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
                </div>

                <form action="/users/{{ $user->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nombre de usuario</label>
                            <input type="text" name="nombre_usuario" required maxlength="150"
                                value="{{ $user->nombre_usuario }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Correo electrónico</label>
                            <input type="email" name="email" required maxlength="150"
                                value="{{ $user->email }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Nueva contraseña (opcional)</label>
                            <input type="password" name="password" minlength="6"
                                placeholder="Déjalo vacío para conservar la actual"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Rol de acceso</label>
                            <select name="rol" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="empleado" {{ $user->rol === 'empleado' ? 'selected' : '' }}>Empleado</option>
                                <option value="admin" {{ $user->rol === 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                        </div>
                    </div>

                    <div class="p-5 bg-gray-50 border-t flex justify-end gap-3">
                        <button type="button" onclick="cerrarModal('modalEditar{{ $user->id }}')" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Cancelar</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-semibold">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- SCRIPTS -->
    <script>
        function cerrarModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        document.getElementById('buscarEmpleado').addEventListener('input', function () {
            const texto = this.value.toLowerCase();

            document.querySelectorAll('#tablaEmpleados tr').forEach(function (fila) {
                fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('[id^="modal"]').forEach(function (modal) {
                    modal.classList.add('hidden');
                });
            }
        });

        // Reabrir el formulario si Laravel devolvió errores de validación.
        @if($errors->any())
            document.getElementById('modalNuevo').classList.remove('hidden');
        @endif
    </script>

</body>
</html>
//cartagena mala influencia