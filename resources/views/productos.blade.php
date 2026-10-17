<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Grupo MDL E.I.R.L.</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fuentes e Iconos -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <a href="/productos" class="sidebar-item active flex items-center px-3 py-2 text-[13px] text-gray-500">
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

            <!-- Título + acción -->
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Productos</h2>
                    <p class="text-sm text-gray-500 mt-1">Escanea el código QR o código de barras para registrar un nuevo producto.</p>
                </div>
                <button onclick="abrirModal('modal-producto-crear')"
                        class="px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-plus"></i> Nuevo producto
                </button>
            </div>

            <!-- Alerta de éxito -->
            @if (session('exito'))
                <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
                    <i class="fa-solid fa-circle-check"></i> {{ session('exito') }}
                </div>
            @endif

            <!-- ESCÁNER -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-11 w-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-barcode text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Escanear código</h3>
                        <p class="text-xs text-gray-500">Apunta el lector al QR o código de barras y presiona Enter.</p>
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
                        <i class="fa-solid fa-camera"></i> Usar cámara
                    </button>
                </div>
                <p class="text-[11px] text-gray-400 mt-3"><i class="fa-solid fa-circle-info mr-1"></i> Si el código existe se suma +1 al stock; si es nuevo, se abre el formulario de registro.</p>
            </div>

            <!-- TABLA STOCK -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mt-6">

                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fa-solid fa-boxes-stacked text-blue-600"></i>
                        <span>Stock de productos — Total: <span class="font-semibold text-gray-800">{{ $productos->count() }}</span></span>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" id="buscar-stock" oninput="filtrarStock()" placeholder="Buscar por nombre, código o categoría..."
                               class="pl-8 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 w-72">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-5 py-3">Producto</th>
                                <th class="px-5 py-3">Código</th>
                                <th class="px-5 py-3">Categoría · Marca</th>
                                <th class="px-5 py-3 text-center">Stock</th>
                                <th class="px-5 py-3 text-center">Mínimo</th>
                                <th class="px-5 py-3">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($productos as $producto)
                                <tr data-stock-id="{{ $producto->id }}"
                                    data-stock-minimo="{{ $producto->stock_minimo }}"
                                    data-buscar="{{ e(strtolower($producto->nombre . ' ' . $producto->codigo_qr . ' ' . ($producto->categoria?->nombre_categoria ?? '') . ' ' . ($producto->marca?->nombre ?? ''))) }}"
                                    class="hover:bg-gray-50/70 transition-colors">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-box text-sm"></i>
                                            </div>
                                            <span class="font-semibold text-gray-800">{{ $producto->nombre }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center font-mono text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">{{ $producto->codigo_qr }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-500">{{ $producto->categoria?->nombre_categoria }} · {{ $producto->marca?->nombre }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span data-stock-valor class="text-base font-bold text-gray-900">{{ $producto->stock_actual }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center text-gray-500">{{ $producto->stock_minimo }}</td>
                                    <td class="px-5 py-3.5">
                                        @if ($producto->stock_actual <= $producto->stock_minimo)
                                            <span data-stock-estado class="inline-flex items-center text-[11px] font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">Stock bajo</span>
                                        @else
                                            <span data-stock-estado class="inline-flex items-center text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Normal</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="h-12 w-12 rounded-2xl bg-gray-50 text-gray-300 flex items-center justify-center">
                                                <i class="fa-solid fa-box-open text-xl"></i>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500">No hay productos registrados</p>
                                            <p class="text-xs text-gray-400">Registra el primero con el escáner o con el botón "Nuevo producto".</p>
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

    <!-- MODAL: Registrar producto -->
    <div id="modal-producto-crear" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" onclick="cerrarModal('modal-producto-crear')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Registrar nuevo producto</h3>
                </div>
                <button onclick="cerrarModal('modal-producto-crear')" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            @include('partials.form_producto', ['modo' => 'crear'])
        </div>
    </div>

    <!-- MODAL: Cámara -->
    <div id="modal-camara" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" onclick="cerrarCamara()"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Escáner con cámara</h3>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="voltearCamara()" title="Cambiar cámara"
                            class="h-9 w-9 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-rotate"></i>
                    </button>
                    <button onclick="cerrarCamara()" class="text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
            </div>
            <div id="lector-camara" class="rounded-xl overflow-hidden bg-black min-h-[280px]"></div>
            <p id="camara-estado" class="text-xs text-gray-500 mt-3 text-center">Iniciando cámara…</p>
        </div>
    </div>

    <!-- TOAST de escaneo -->
    <div id="toast-escaneo" class="hidden fixed bottom-6 right-6 max-w-sm rounded-2xl px-5 py-4 shadow-2xl text-white bg-emerald-600 z-50">
        <p id="toast-escaneo-texto" class="text-sm font-semibold"></p>
    </div>

    <script>
        function abrirModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function cerrarModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const scannerInput = document.getElementById('scanner-input');

        // ---- Toast ----
        let toastTimer = null;
        function mostrarToast(texto, color) {
            const toast = document.getElementById('toast-escaneo');
            document.getElementById('toast-escaneo-texto').textContent = texto;
            toast.className = toast.className.replace(/bg-\S+/, 'bg-' + color);
            toast.classList.remove('hidden');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast.classList.add('hidden'), 2500);
        }

        // ---- Escaneo: código existente = +1 stock, desconocido = registrar ----
        async function procesarEscaneo(codigo) {
            try {
                const r = await fetch('/productos/escanear', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ codigo }),
                });
                const d = await r.json();

                if (d.encontrado) {
                    actualizarFilaStock(d.producto.id, d.producto.stock_actual);
                    mostrarToast(`+1 ${d.producto.nombre} — stock: ${d.producto.stock_actual}`, 'emerald-600');
                    return;
                }

                cerrarCamara();
                abrirModal('modal-producto-crear');
                const campo = document.getElementById('codigo_qr-crear');
                campo.value = d.codigo;
                campo.focus();
                mostrarToast('Código nuevo: completa el registro del producto', 'blue-600');
            } catch (e) {
                mostrarToast('Error al procesar el código', 'red-600');
            }
        }

        // ---- Escáner USB/bluetooth: Enter procesa el código ----
        scannerInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const codigo = this.value.trim();
                if (!codigo) return;
                this.value = '';
                procesarEscaneo(codigo);
            }
        });

        // ---- Cámara (celular o laptop) ----
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

        // ---- Tabla de stock ----
        function actualizarFilaStock(id, stockNuevo) {
            const fila = document.querySelector('tr[data-stock-id="' + id + '"]');
            if (!fila) return;

            fila.querySelector('[data-stock-valor]').textContent = stockNuevo;

            const minimo = parseInt(fila.getAttribute('data-stock-minimo'), 10) || 0;
            const badge = fila.querySelector('[data-stock-estado]');
            if (badge) {
                if (stockNuevo <= minimo) {
                    badge.className = 'inline-flex items-center text-[11px] font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full';
                    badge.textContent = 'Stock bajo';
                } else {
                    badge.className = 'inline-flex items-center text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full';
                    badge.textContent = 'Normal';
                }
            }

            fila.classList.add('bg-emerald-50');
            setTimeout(() => fila.classList.remove('bg-emerald-50'), 1200);
        }

        function filtrarStock() {
            const q = document.getElementById('buscar-stock').value.toLowerCase();
            document.querySelectorAll('tr[data-stock-id]').forEach(function (tr) {
                tr.style.display = tr.getAttribute('data-buscar').includes(q) ? '' : 'none';
            });
        }

        // Si se volvió con errores de validación, reabrir el formulario
        @if ($errors->any())
            abrirModal('modal-producto-crear');
        @endif
    </script>
</body>
</html>
