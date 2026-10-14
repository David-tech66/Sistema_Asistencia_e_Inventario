<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Operativo - Grupo MDL E.I.R.L.</title>
    
    <!-- Fuentes e Iconos -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js para los gráficos dinámicos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            dark: '#0f172a',    /* Slate 900 */
                            primary: '#1e293b', /* Slate 800 */
                            accent: '#2563eb',  /* Blue 600 */
                            success: '#10b981', /* Emerald 500 */
                        }
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden flex h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex shadow-xl z-20 shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <span class="text-base font-black tracking-wider flex items-center gap-2">
                <svg class="w-7 h-7 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                GRUPO <span class="text-red-600">MDL</span> <span class="text-[10px] text-blue-400 font-semibold px-1.5 py-0.5 bg-blue-900/40 rounded border border-blue-700/30">E.I.R.L.</span>
            </span>
        </div>
        
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a href="#" class="flex items-center px-4 py-2.5 bg-blue-600 text-white rounded-lg font-medium text-sm shadow-sm">
                <i class="fa-solid fa-chart-pie w-5 mr-2"></i> Dashboard
            </a>
            <div class="pt-3 pb-1 px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Inventario</div>
            <a href="#" class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-boxes-stacked w-5 mr-2 text-slate-400"></i> Productos
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-tags w-5 mr-2 text-slate-400"></i> Categorías
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-cart-shopping w-5 mr-2 text-slate-400"></i> Ventas
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-users w-5 mr-2 text-slate-400"></i> Clientes
            </a>

            <div class="pt-3 pb-1 px-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Personal & QR</div>
            <a href="#" class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-id-badge w-5 mr-2 text-slate-400"></i> Empleados
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-qrcode w-5 mr-2 text-emerald-400"></i> Asistencias QR
            </a>
        </nav>
        
        <div class="p-4 border-t border-slate-800">
            <a href="#" class="flex items-center px-3 py-2 text-slate-400 hover:text-white text-sm transition-colors">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 mr-2 text-rose-400"></i> Cerrar Sesión
            </a>
        </div>
    </aside>

    <!-- MAIN CONTAINER -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- TOPBAR -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10 shrink-0 border-b border-slate-100">
            <div class="flex items-center">
                <h1 class="text-lg font-bold text-slate-800">Panel Administrativo</h1>
            </div>
            <div class="flex items-center space-x-4">
                <button class="relative text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center">3</span>
                </button>
                <div class="h-8 w-px bg-slate-200"></div>
                <div class="flex items-center">
                    <img src="https://ui-avatars.com/api/?name=Admin+MDL&background=1e293b&color=fff" alt="Avatar" class="h-9 w-9 rounded-full object-cover border border-slate-200">
                    <div class="ml-3 text-left">
                        <p class="text-sm font-semibold text-slate-700 leading-tight">Administrador</p>
                        <p class="text-xs text-slate-400">Control Central</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- DASHBOARD CONTENT AREA -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50/70 p-6 space-y-6">
            
            <!-- Título de Sección y Fecha -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <div>
                    <h2 class="text-2xl font-black text-slate-900">Resumen Operativo</h2>
                    <p class="text-sm text-slate-500">Monitoreo en tiempo real de ventas, inventario y asistencia por código QR.</p>
                </div>
                <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-xs text-sm font-medium text-slate-600 flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-blue-600"></i> Miércoles, 14 Oct 2026
                </div>
            </div>

            <!-- 1. FILA SUPERIOR: TARJETAS KPI (4 Columnas) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- KPI 1: Ventas -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Ventas del Día</span>
                            <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="fa-solid fa-cash-register text-lg"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">$1,450.00</h3>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md w-fit">
                        <i class="fa-solid fa-arrow-trend-up mr-1.5"></i> +12.4% vs ayer
                    </div>
                </div>

                <!-- KPI 2: Stock Mínimo -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Stock Crítico</span>
                            <div class="h-10 w-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-black text-rose-600">3 <span class="text-sm font-normal text-slate-400">artículos</span></h3>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-medium text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md w-fit">
                        <i class="fa-solid fa-box-open mr-1.5"></i> Requiere reabastecimiento
                    </div>
                </div>

                <!-- KPI 3: Asistencia QR -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Asistencia QR Hoy</span>
                            <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="fa-solid fa-qrcode text-lg"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">12 <span class="text-sm font-normal text-slate-400">/ 15 Presentes</span></h3>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md w-fit">
                        <i class="fa-solid fa-user-check mr-1.5"></i> 80% Puntualidad activa
                    </div>
                </div>

                <!-- KPI 4: Órdenes o Servicios Activos -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Servicios en Taller</span>
                            <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                <i class="fa-solid fa-laptop-code text-lg"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900">7 <span class="text-sm font-normal text-slate-400">equipos</span></h3>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-medium text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md w-fit">
                        <i class="fa-solid fa-clock mr-1.5"></i> En proceso de soporte
                    </div>
                </div>

            </div>

            <!-- 2. ZONA CENTRAL: GRÁFICOS OPERATIVOS (Línea de Ventas y Anillo de Asistencia QR) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Gráfico de Líneas: Ventas Tecnológicas (Ocupa 2 columnas) -->
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Comportamiento de Ventas Tecnológicas</h3>
                            <p class="text-xs text-slate-400">Ingresos acumulados por día de la semana actual ($)</p>
                        </div>
                        <span class="text-xs bg-blue-50 text-blue-600 font-semibold px-2.5 py-1 rounded-lg">Semanal</span>
                    </div>
                    <div class="flex-1 relative h-64">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Anillo / Donut: Puntualidad QR (Ocupa 1 columna) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Puntualidad de Personal</h3>
                            <p class="text-xs text-slate-400">Registros de asistencia vía QR</p>
                        </div>
                    </div>
                    <div class="flex-1 relative flex items-center justify-center h-52">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mt-4 text-center">
                        <div class="bg-emerald-50 p-2 rounded-lg">
                            <span class="block text-xs text-slate-500">A tiempo</span>
                            <span class="font-bold text-emerald-600 text-sm">10</span>
                        </div>
                        <div class="bg-amber-50 p-2 rounded-lg">
                            <span class="block text-xs text-slate-500">Tardanzas</span>
                            <span class="font-bold text-amber-600 text-sm">2</span>
                        </div>
                        <div class="bg-rose-50 p-2 rounded-lg">
                            <span class="block text-xs text-slate-500">Faltas</span>
                            <span class="font-bold text-rose-600 text-sm">3</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- 3. FILA INFERIOR: GRÁFICO DE BARRAS HORIZONTALES Y TABLAS OPERATIVAS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Barras Horizontales: Categorías Más Vendidas -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Categorías de Mayor Rotación</h3>
                            <p class="text-xs text-slate-400">Volumen de salidas en inventario informático</p>
                        </div>
                    </div>
                    <div class="flex-1 relative h-64">
                        <canvas id="categoriesChart"></canvas>
                    </div>
                </div>

                <!-- Panel de Alertas de Stock & Últimos Registros QR -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col space-y-6">
                    
                    <!-- Tabla Stock Crítico -->
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Alertas de Stock Mínimo
                            </h3>
                            <span class="text-xs text-blue-600 font-semibold cursor-pointer hover:underline">Ver inventario</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="text-xs text-slate-400 bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="py-2 px-3 font-medium">Producto / Componente</th>
                                        <th class="py-2 px-3 font-medium text-center">Stock Actual</th>
                                        <th class="py-2 px-3 font-medium text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <tr>
                                        <td class="py-2.5 px-3 font-medium text-slate-800">Memoria RAM DDR4 16GB</td>
                                        <td class="py-2.5 px-3 text-center"><span class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded">2 unds</span></td>
                                        <td class="py-2.5 px-3 text-right"><button class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-2.5 py-1 rounded font-medium">Reabastecer</button></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 px-3 font-medium text-slate-800">Cartucho Tinta Negra HP</td>
                                        <td class="py-2.5 px-3 text-center"><span class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded">1 und</span></td>
                                        <td class="py-2.5 px-3 text-right"><button class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-2.5 py-1 rounded font-medium">Reabastecer</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mini Lista: Últimos Registros QR Asistencia -->
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Últimos Escaneos QR
                            </h3>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Carlos+Mendoza&background=0D8ABC&color=fff" class="w-7 h-7 rounded-full" alt="User">
                                    <div>
                                        <p class="font-semibold text-slate-800">Carlos Mendoza <span class="text-slate-400 font-normal">(Soporte T.)</span></p>
                                        <p class="text-[10px] text-emerald-600 font-medium">Validación QR exitosa</p>
                                    </div>
                                </div>
                                <span class="font-mono text-slate-500 font-semibold">08:02 AM</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Lucia+Gomez&background=F59E0B&color=fff" class="w-7 h-7 rounded-full" alt="User">
                                    <div>
                                        <p class="font-semibold text-slate-800">Lucía Gómez <span class="text-slate-400 font-normal">(Ventas)</span></p>
                                        <p class="text-[10px] text-amber-600 font-medium">Tardanza registrada</p>
                                    </div>
                                </div>
                                <span class="font-mono text-slate-500 font-semibold">08:15 AM</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </main>
    </div>

    <!-- Script de Inicialización de Gráficos (Chart.js) -->
    <script>
        // 1. Gráfico de Líneas: Ventas Tecnológicas
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Ventas ($)',
                    data: [820, 950, 1450, 1100, 1300, 1600, 750],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Inter' } } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Inter' } } }
                }
            }
        });

        // 2. Gráfico de Anillo: Puntualidad QR
        const ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctxAttendance, {
            type: 'doughnut',
            data: {
                labels: ['A tiempo', 'Tardanzas', 'Faltas'],
                datasets: [{
                    data: [10, 2, 3],
                    backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'],
                    borderWidth: 0,
                    cutout: '75%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // 3. Gráfico de Barras Horizontales: Categorías Más Vendidas
        const ctxCategories = document.getElementById('categoriesChart').getContext('2d');
        new Chart(ctxCategories, {
            type: 'bar',
            data: {
                labels: ['Laptops y PCs', 'Componentes/Placas', 'Periféricos', 'Servicios Soporte'],
                datasets: [{
                    label: 'Unidades / Servicios',
                    data: [42, 35, 28, 19],
                    backgroundColor: ['#1e293b', '#2563eb', '#38bdf8', '#94a3b8'],
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Inter' } } },
                    y: { grid: { display: false }, ticks: { font: { family: 'Inter' } } }
                }
            }
        });
    </script>
</body>
</html>