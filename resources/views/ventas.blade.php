<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Grupo MDL E.I.R.L.</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fuentes e Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

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
            <a href="/dashboard" class="sidebar-item flex items-center px-4 py-2.5 text-sm">
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
                    <a href="/ventas" class="sidebar-item active flex items-center px-3 py-2 text-[13px] text-gray-500">
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
                <h2 class="text-2xl font-bold text-gray-900">Punto de Venta</h2>
                <p class="text-sm text-gray-500 mt-1">Escanea los productos y cobra la venta.</p>
            </div>

            <!-- Toast de escaneo -->
            <div id="toast" class="hidden fixed top-6 right-6 z-50 px-5 py-3 rounded-xl shadow-lg text-sm font-semibold text-white"></div>

            <div class="flex gap-6 items-start">

                <!-- COLUMNA IZQUIERDA: Escáner -->
                <div class="flex-1 space-y-6 min-w-0">

                    <!-- ESCÁNER -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-11 w-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-barcode text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Escanear producto</h3>
                                <p class="text-xs text-gray-500">Apunta el lector al QR y presiona Enter para agregar al voucher.</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="relative flex-1">
                                <i class="fa-solid fa-qrcode absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400"></i>
                                <input type="text" id="scanner-input" autofocus autocomplete="off"
                                       placeholder="Escanea o escribe el código aquí..."
                                       class="w-full pl-12 pr-4 py-4 text-base border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 font-mono">
                            </div>
                            <button onclick="abrirCamara()"
                                    class="px-5 py-4 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm flex items-center gap-2 shrink-0">
                                <i class="fa-solid fa-camera"></i> Cámara
                            </button>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-3"><i class="fa-solid fa-circle-info mr-1"></i> Cada escaneo agrega 1 unidad al voucher. Escanea varias veces para sumar cantidad.</p>
                    </div>

                    <!-- ÚLTIMAS VENTAS -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2 text-sm text-gray-500">
                            <i class="fa-solid fa-receipt text-blue-600"></i>
                            <span>Últimas ventas — Total: <span class="font-semibold text-gray-800">{{ $ventas->count() }}</span></span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                        <th class="px-5 py-3">Comprobante</th>
                                        <th class="px-5 py-3">Cliente</th>
                                        <th class="px-5 py-3">Tipo</th>
                                        <th class="px-5 py-3 text-right">Total</th>
                                        <th class="px-5 py-3">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse ($ventas->take(10) as $venta)
                                        <tr class="hover:bg-gray-50/70 transition-colors">
                                            <td class="px-5 py-3.5">
                                                <span class="inline-flex items-center font-mono text-xs text-gray-700 bg-gray-100 px-2 py-1 rounded-lg">{{ $venta->numero_comprobante }}</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-gray-700">{{ $venta->cliente?->nombre }}</td>
                                            <td class="px-5 py-3.5">
                                                <span class="inline-flex items-center text-[11px] font-semibold {{ $venta->tipo_comprobante === 'Boleta' ? 'text-blue-700 bg-blue-50' : 'text-purple-700 bg-purple-50' }} px-2 py-0.5 rounded-full">{{ $venta->tipo_comprobante }}</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-right font-semibold text-gray-900">S/ {{ number_format($venta->total, 2) }}</td>
                                            <td class="px-5 py-3.5 text-gray-500">{{ $venta->created_at?->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400">No hay ventas registradas todavía.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: Voucher -->
                <div class="w-[400px] shrink-0">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm sticky top-0 flex flex-col max-h-[calc(100vh-180px)]">

                        <!-- Encabezado del voucher -->
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Boleta de venta</h3>
                                <p class="text-[11px] text-gray-400">Grupo MDL E.I.R.L.</p>
                            </div>
                        </div>

                        <!-- Cliente + Tipo -->
                        <div class="px-5 py-4 space-y-3 border-b border-gray-100">
                            <div>
                                <label class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Cliente</label>
                                <select id="cliente-select" class="mt-1 w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ $cliente->nombre === 'Cliente General' ? 'selected' : '' }}>
                                            {{ $cliente->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Comprobante</label>
                                <select id="comprobante-select" class="mt-1 w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                </select>
                            </div>
                        </div>

                        <!-- Items del voucher -->
                        <div id="voucher-items" class="flex-1 overflow-y-auto px-5 py-4 space-y-3 min-h-[200px]">
                            <p id="voucher-vacio" class="text-center text-sm text-gray-400 py-8">
                                <i class="fa-solid fa-cart-shopping text-3xl text-gray-200 block mb-3"></i>
                                Escanea un producto para comenzar
                            </p>
                        </div>

                        <!-- Total + Cobrar -->
                        <div class="border-t border-gray-100 px-5 py-4 space-y-3">
                            <div class="flex justify-between items-center text-lg font-bold text-gray-900">
                                <span>Total</span>
                                <span id="voucher-total">S/ 0.00</span>
                            </div>
                            <button id="btn-cobrar" onclick="cobrarVenta()" disabled
                                    class="w-full py-3 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-300 disabled:cursor-not-allowed rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check-double"></i> Cobrar venta
                            </button>
                            <button onclick="cancelarVenta()"
                                    class="w-full py-2.5 text-sm font-medium text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL: Confirmación de venta -->
    <div id="modal-exito" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" onclick="cerrarModalExito()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-center">
            <div class="h-16 w-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-check text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">Venta registrada</h3>
            <p class="text-sm text-gray-500 mb-4">Comprobante generado correctamente.</p>
            <div class="bg-gray-50 rounded-xl p-4 text-left text-sm space-y-2">
                <div class="flex justify-between"><span class="text-gray-500">Comprobante:</span> <span id="exito-comprobante" class="font-mono font-semibold"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Cliente:</span> <span id="exito-cliente" class="font-semibold"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Total:</span> <span id="exito-total" class="font-bold text-emerald-600"></span></div>
            </div>
            <button onclick="cerrarModalExito()" class="mt-5 w-full py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors">Aceptar</button>
        </div>
    </div>

    <!-- MODAL: Cámara -->
    <div id="modal-camara" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" onclick="cerrarCamara()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-900">Escanear con cámara</h3>
                <button onclick="cerrarCamara()" class="h-8 w-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div id="lector-camara" class="rounded-xl overflow-hidden bg-black min-h-[280px]"></div>
            <p id="camara-estado" class="text-xs text-gray-500 mt-3 text-center">Iniciando cámara…</p>
            <button onclick="voltearCamara()" class="mt-3 w-full py-2 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-camera-rotate"></i> Voltear cámara
            </button>
        </div>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        // ---- Carrito (voucher) ----
        let carrito = []; // {id, nombre, precio, cantidad, stock_max}

        function renderVoucher() {
            const cont = document.getElementById('voucher-items');
            const vacio = document.getElementById('voucher-vacio');
            const totalEl = document.getElementById('voucher-total');
            const btnCobrar = document.getElementById('btn-cobrar');

            if (carrito.length === 0) {
                cont.innerHTML = `<p id="voucher-vacio" class="text-center text-sm text-gray-400 py-8">
                    <i class="fa-solid fa-cart-shopping text-3xl text-gray-200 block mb-3"></i>
                    Escanea un producto para comenzar</p>`;
                totalEl.textContent = 'S/ 0.00';
                btnCobrar.disabled = true;
                return;
            }

            btnCobrar.disabled = false;
            let total = 0;

            cont.innerHTML = carrito.map((item, i) => {
                const subtotal = item.precio * item.cantidad;
                total += subtotal;
                return `
                <div class="bg-gray-50 rounded-xl p-3 flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">${item.nombre}</p>
                        <p class="text-xs text-gray-500">S/ ${item.precio.toFixed(2)} c/u</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button onclick="cambiarCantidad(${i}, -1)" class="h-7 w-7 rounded-lg bg-white border border-gray-200 text-gray-500 hover:bg-gray-100 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <span class="w-8 text-center text-sm font-bold text-gray-800">${item.cantidad}</span>
                        <button onclick="cambiarCantidad(${i}, 1)" class="h-7 w-7 rounded-lg bg-white border border-gray-200 text-gray-500 hover:bg-gray-100 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                    <span class="w-16 text-right text-sm font-semibold text-gray-900">S/ ${subtotal.toFixed(2)}</span>
                    <button onclick="eliminarItem(${i})" class="h-7 w-7 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </div>`;
            }).join('');

            totalEl.textContent = 'S/ ' + total.toFixed(2);
        }

        function cambiarCantidad(idx, delta) {
            const item = carrito[idx];
            const nueva = item.cantidad + delta;
            if (nueva < 1) { eliminarItem(idx); return; }
            if (nueva > item.stock_max) { toast('Stock máximo: ' + item.stock_max, 'amber'); return; }
            item.cantidad = nueva;
            renderVoucher();
        }

        function eliminarItem(idx) {
            carrito.splice(idx, 1);
            renderVoucher();
        }

        function cancelarVenta() {
            if (carrito.length === 0) return;
            if (!confirm('¿Cancelar la venta? Se vaciará el voucher.')) return;
            carrito = [];
            renderVoucher();
        }

        // ---- Escaneo ----
        const input = document.getElementById('scanner-input');
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const codigo = this.value.trim();
                if (codigo) procesarEscaneo(codigo);
                this.value = '';
            }
        });

        async function procesarEscaneo(codigo) {
            try {
                const res = await fetch('/ventas/escanear', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ codigo })
                });
                const data = await res.json();

                if (!data.encontrado) {
                    toast('Código no encontrado: ' + codigo, 'red');
                    return;
                }

                if (data.sin_stock) {
                    toast('Sin stock: ' + data.producto.nombre, 'red');
                    return;
                }

                const existente = carrito.find(c => c.id === data.producto.id);
                if (existente) {
                    if (existente.cantidad >= data.producto.stock_actual) {
                        toast('Stock máximo alcanzado para ' + data.producto.nombre, 'amber');
                        return;
                    }
                    existente.cantidad++;
                } else {
                    carrito.push({
                        id: data.producto.id,
                        nombre: data.producto.nombre,
                        precio: data.producto.precio_venta,
                        cantidad: 1,
                        stock_max: data.producto.stock_actual,
                    });
                }

                renderVoucher();
                toast(data.producto.nombre + ' agregado', 'green');
            } catch (err) {
                toast('Error al escanear', 'red');
            }
        }

        // ---- Cobrar ----
        async function cobrarVenta() {
            if (carrito.length === 0) return;

            const clienteId = document.getElementById('cliente-select').value;
            const tipo = document.getElementById('comprobante-select').value;

            const btn = document.getElementById('btn-cobrar');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Procesando...';

            try {
                const res = await fetch('/ventas', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({
                        cliente_id: parseInt(clienteId),
                        tipo_comprobante: tipo,
                        items: carrito.map(c => ({
                            producto_id: c.id,
                            cantidad: c.cantidad,
                        })),
                    })
                });
                const data = await res.json();

                if (!data.exito) {
                    toast(data.mensaje || 'Error al registrar la venta', 'red');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-check-double"></i> Cobrar venta';
                    return;
                }

                document.getElementById('exito-comprobante').textContent = data.venta.numero_comprobante;
                document.getElementById('exito-cliente').textContent = data.venta.cliente;
                document.getElementById('exito-total').textContent = 'S/ ' + data.venta.total.toFixed(2);
                document.getElementById('modal-exito').classList.remove('hidden');

                carrito = [];
                renderVoucher();
            } catch (err) {
                toast('Error de conexión', 'red');
            }

            btn.disabled = carrito.length === 0;
            btn.innerHTML = '<i class="fa-solid fa-check-double"></i> Cobrar venta';
        }

        function cerrarModalExito() {
            document.getElementById('modal-exito').classList.add('hidden');
            document.getElementById('scanner-input').focus();
        }

        // ---- Toast ----
        function toast(msg, color) {
            const t = document.getElementById('toast');
            const colors = { green: 'bg-emerald-600', red: 'bg-red-600', amber: 'bg-amber-500' };
            t.className = 'fixed top-6 right-6 z-50 px-5 py-3 rounded-xl shadow-lg text-sm font-semibold text-white ' + (colors[color] || colors.green);
            t.textContent = msg;
            t.classList.remove('hidden');
            clearTimeout(t._timer);
            t._timer = setTimeout(() => t.classList.add('hidden'), 2200);
        }

        // ---- Cámara ----
        let camaraScanner = null;
        let camaraFacing = 'environment';
        let camaraPausada = false;

        async function abrirCamara() {
            document.getElementById('modal-camara').classList.remove('hidden');
            const estado = document.getElementById('camara-estado');

            if (!camaraScanner) {
                camaraScanner = new Html5Qrcode('lector-camara');
            }

            try {
                await camaraScanner.start(
                    { facingMode: camaraFacing },
                    { fps: 10, qrbox: { width: 230, height: 230 } },
                    (texto) => alDetectarCamara(texto),
                    () => {}
                );
                estado.textContent = 'Apunta al código del producto.';
                estado.className = 'text-xs text-gray-500 mt-3 text-center';
            } catch (e) {
                estado.textContent = 'No se pudo acceder a la cámara. ' + e;
                estado.className = 'text-xs text-red-500 mt-3 text-center';
            }
        }

        async function alDetectarCamara(texto) {
            if (camaraPausada) return;
            camaraPausada = true;

            const estado = document.getElementById('camara-estado');
            estado.textContent = 'Código detectado: ' + texto;
            estado.className = 'text-xs text-blue-600 mt-3 text-center font-semibold';

            try { camaraScanner.pause(true); } catch (e) {}

            await procesarEscaneo(texto);

            setTimeout(async () => {
                try { await camaraScanner.resume(); } catch (e) {}
                estado.textContent = 'Apunta al siguiente código.';
                estado.className = 'text-xs text-gray-500 mt-3 text-center';
                camaraPausada = false;
            }, 1500);
        }

        async function cerrarCamara() {
            document.getElementById('modal-camara').classList.add('hidden');
            if (camaraScanner) {
                try { await camaraScanner.stop(); camaraScanner.clear(); } catch (e) {}
                camaraScanner = null;
            }
            camaraPausada = false;
        }

        async function voltearCamara() {
            camaraFacing = camaraFacing === 'environment' ? 'user' : 'environment';
            if (camaraScanner) {
                try { await camaraScanner.stop(); camaraScanner.clear(); } catch (e) {}
                camaraScanner = null;
            }
            await abrirCamara();
        }
    </script>
</body>
</html>