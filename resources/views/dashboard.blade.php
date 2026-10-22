@extends('layouts.admin')

@section('title', 'Panel Administrativo')

@section('styles')
    @vite(['resources/css/dashboard.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <h2 class="section-title">Resumen Operativo</h2>
        <p class="section-subtitle">Monitoreo en tiempo real de ventas, inventario y asistencia por código QR.</p>
    </div>

    <!-- KPI CARDS -->
    <div class="kpi-grid">

        <!-- KPI 1: Total de Productos -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Total de Productos</span>
                <div class="kpi-icon kpi-icon--blue">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
            </div>
            <h3 class="kpi-value">{{ $totalProductos }}</h3>
            <div class="kpi-footer">
                <a href="/reportes/productos" class="kpi-link">
                    Ver reportes <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- KPI 2: Stock Crítico -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Stock Crítico</span>
                <div class="kpi-icon kpi-icon--red">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <h3 class="kpi-value">{{ $stockCritico }} <span class="kpi-unit">artículo{{ $stockCritico === 1 ? '' : 's' }}</span></h3>
            <div class="kpi-footer">
                @if ($stockCritico > 0)
                    <span class="kpi-badge kpi-badge--red">
                        <i class="fa-solid fa-circle-exclamation"></i> Requiere reabastecimiento
                    </span>
                @endif
                <a href="/productos" class="kpi-link">
                    Ver stock <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- KPI 3: Asistencias QR Hoy -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Asistencias QR Hoy</span>
            </div>
            <div class="gauge-container">
                <svg viewBox="0 0 120 70" class="gauge-svg">
                    <path d="M 10 60 A 50 50 0 0 1 110 60" fill="none" stroke="#e5e7eb" stroke-width="10" stroke-linecap="round"/>
                    @if ($pctGauge > 0)
                        <path d="M 10 60 A 50 50 0 0 1 {{ $gaugeFinX }} {{ $gaugeFinY }}" fill="none" stroke="#10b981" stroke-width="10" stroke-linecap="round"/>
                    @endif
                    <text x="55" y="48" text-anchor="middle" class="gauge-value" font-size="14">{{ $presentes }}</text>
                    <text x="72" y="48" text-anchor="middle" class="gauge-max" font-size="10">/{{ $empleadosActivos }}</text>
                    <text x="60" y="62" text-anchor="middle" class="gauge-label" font-size="8">presentes</text>
                </svg>
            </div>
            <div class="kpi-footer" style="width: 100%; text-align: center;">
                <a href="/reportes/asistencias" class="kpi-link">
                    Ver reportes <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- KPI 4: Ventas del Día -->
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Ventas del Día</span>
                <div class="kpi-icon kpi-icon--amber">
                    <i class="fa-solid fa-cash-register"></i>
                </div>
            </div>
            <h3 class="kpi-value">${{ number_format($ventasHoy, 2, '.', ',') }}</h3>
            <div class="kpi-footer">
                <span class="kpi-badge kpi-badge--green">
                    <i class="fa-solid fa-arrow-trend-up"></i> {{ $variacionTexto }}
                </span>
                <a href="/reportes/ventas" class="kpi-link">
                    Ver reportes <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>

    <!-- GRÁFICOS PRINCIPALES -->
    <div class="charts-grid">

        <!-- Gráfico de Línea: Comportamiento de Ventas -->
        <div class="chart-card">
            <div class="chart-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 class="chart-title">Comportamiento de Ventas Tecnológicas</h3>
                    <p class="chart-subtitle">Ingresos acumulados por día de la semana actual ($)</p>
                </div>
                <span class="chart-badge">Semanal</span>
            </div>
            <div class="line-chart-container">
                <svg class="line-chart-svg" viewBox="0 0 700 280" preserveAspectRatio="none">
                    <!-- Grid lines -->
                    <line x1="60" y1="20" x2="680" y2="20" stroke="#f3f4f6" stroke-width="1"/>
                    <line x1="60" y1="60" x2="680" y2="60" stroke="#f3f4f6" stroke-width="1"/>
                    <line x1="60" y1="100" x2="680" y2="100" stroke="#f3f4f6" stroke-width="1"/>
                    <line x1="60" y1="140" x2="680" y2="140" stroke="#f3f4f6" stroke-width="1"/>
                    <line x1="60" y1="180" x2="680" y2="180" stroke="#f3f4f6" stroke-width="1"/>
                    <line x1="60" y1="220" x2="680" y2="220" stroke="#f3f4f6" stroke-width="1"/>

                    <!-- Y-axis labels -->
                    <text x="50" y="24" text-anchor="end" font-size="10" fill="#9ca3af">{{ $etiquetasY[0] }}</text>
                    <text x="50" y="64" text-anchor="end" font-size="10" fill="#9ca3af">{{ $etiquetasY[1] }}</text>
                    <text x="50" y="104" text-anchor="end" font-size="10" fill="#9ca3af">{{ $etiquetasY[2] }}</text>
                    <text x="50" y="144" text-anchor="end" font-size="10" fill="#9ca3af">{{ $etiquetasY[3] }}</text>
                    <text x="50" y="184" text-anchor="end" font-size="10" fill="#9ca3af">{{ $etiquetasY[4] }}</text>
                    <text x="50" y="224" text-anchor="end" font-size="10" fill="#9ca3af">{{ $etiquetasY[5] }}</text>

                    <!-- Area fill -->
                    <path d="{{ $rutaArea }}"
                        fill="url(#lineGradient)" opacity="0.3"/>

                    <!-- Line -->
                    <path d="{{ $rutaLinea }}"
                        fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>

                    <!-- Data points -->
                    @foreach ($puntosGrafico as $punto)
                        <circle cx="{{ $punto['x'] }}" cy="{{ $punto['y'] }}" r="4" fill="#3b82f6" stroke="#ffffff" stroke-width="2"/>
                    @endforeach

                    <!-- X-axis labels -->
                    <text x="80" y="245" text-anchor="middle" font-size="10" fill="#9ca3af">Lun</text>
                    <text x="175" y="245" text-anchor="middle" font-size="10" fill="#9ca3af">Mar</text>
                    <text x="280" y="245" text-anchor="middle" font-size="10" fill="#9ca3af">Mié</text>
                    <text x="385" y="245" text-anchor="middle" font-size="10" fill="#9ca3af">Jue</text>
                    <text x="490" y="245" text-anchor="middle" font-size="10" fill="#9ca3af">Vie</text>
                    <text x="595" y="245" text-anchor="middle" font-size="10" fill="#9ca3af">Sáb</text>
                    <text x="680" y="245" text-anchor="middle" font-size="10" fill="#9ca3af">Dom</text>

                    <!-- Gradient definition -->
                    <defs>
                        <linearGradient id="lineGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#3b82f6"/>
                            <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </div>

        <!-- Gráfico Circular: Puntualidad de Personal -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Puntualidad de Personal</h3>
                <p class="chart-subtitle">Registros de asistencias vía QR</p>
            </div>
            <div class="pie-chart-container">
                <svg class="pie-svg" viewBox="0 0 180 180">
                    <!-- Faltas - Red -->
                    @if ($lenFaltas > 0)
                        <circle cx="90" cy="90" r="60" fill="none" stroke="#ef4444" stroke-width="35"
                                stroke-dasharray="{{ $lenFaltas }} {{ $circunferencia - $lenFaltas }}" stroke-dashoffset="0"
                                transform="rotate(-90 90 90)"/>
                    @endif
                    <!-- Tardanzas - Yellow -->
                    @if ($lenTardanzas > 0)
                        <circle cx="90" cy="90" r="60" fill="none" stroke="#f59e0b" stroke-width="35"
                                stroke-dasharray="{{ $lenTardanzas }} {{ $circunferencia - $lenTardanzas }}" stroke-dashoffset="{{ -$lenFaltas }}"
                                transform="rotate(-90 90 90)"/>
                    @endif
                    <!-- A tiempo - Green -->
                    @if ($lenTiempo > 0)
                        <circle cx="90" cy="90" r="60" fill="none" stroke="#10b981" stroke-width="35"
                                stroke-dasharray="{{ $lenTiempo }} {{ $circunferencia - $lenTiempo }}" stroke-dashoffset="{{ -($lenFaltas + $lenTardanzas) }}"
                                transform="rotate(-90 90 90)"/>
                    @endif

                    <!-- Center text -->
                    <text x="90" y="85" text-anchor="middle" font-size="11" fill="#6b7280">Total</text>
                    <text x="90" y="100" text-anchor="middle" font-size="16" font-weight="700" fill="#111827">{{ $totalPuntualidad }}</text>
                </svg>
                <div class="pie-legend">
                    <div class="pie-legend-item">
                        <span class="pie-legend-label pie-legend-label--green">A tiempo</span>
                        <span class="pie-legend-value">{{ str_pad($aTiempo, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="pie-legend-item">
                        <span class="pie-legend-label pie-legend-label--yellow">Tardanzas</span>
                        <span class="pie-legend-value">{{ str_pad($tardanzas, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="pie-legend-item">
                        <span class="pie-legend-label pie-legend-label--red">Faltas</span>
                        <span class="pie-legend-value">{{ str_pad($faltas, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- GRID INFERIOR -->
    <div class="bottom-grid">

        <!-- Categorías de Mayor Rotación -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Categorías de Mayor Rotación</h3>
                <p class="chart-subtitle">Volumen de salidas en inventario informático</p>
            </div>
            <div class="bar-chart-container">
                @if ($categoriasRotacion->isEmpty())
                    <div class="bar-item">
                        <span class="bar-label">Sin movimientos registrados</span>
                        <div class="bar-track">
                            <div class="bar-fill bar-fill--gray" style="width: 0%;"></div>
                        </div>
                    </div>
                @else
                    @php($coloresBarra = ['dark', 'blue', 'lightblue', 'gray'])
                    @foreach ($categoriasRotacion as $categoria)
                        <div class="bar-item">
                            <span class="bar-label">{{ $categoria->nombre_categoria }}</span>
                            <div class="bar-track">
                                <div class="bar-fill bar-fill--{{ $coloresBarra[$loop->index % count($coloresBarra)] }}"
                                     style="width: {{ number_format($categoria->volumen / $escalaBarras * 100, 1, '.', '') }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                @endif
                <div class="bar-scale">
                    @foreach ($ticksBarras as $tick)
                        <span>{{ $tick }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Alertas de Stock + Últimos Escaneos -->
        <div class="chart-card">
            <!-- Alertas de Stock Mínimo -->
            <div class="alerts-header">
                <span class="alerts-title">
                    <i class="fa-solid fa-circle-exclamation"></i> ALERTAS DE STOCK MÍNIMO
                </span>
                <a href="/productos" class="alerts-link">Ver inventario</a>
            </div>
            <table class="alerts-table">
                <thead>
                    <tr>
                        <th>Producto / Componente</th>
                        <th>Stock Actual</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productosCriticos as $producto)
                        <tr>
                            <td class="alert-product">{{ $producto->nombre }}</td>
                            <td><span class="alert-stock"><i class="fa-solid fa-circle-exclamation"></i> {{ $producto->stock_actual }} {{ (int) $producto->stock_actual === 1 ? 'und' : 'unds' }}</span></td>
                            <td><button class="btn-restock" type="button" onclick="location.href='/productos'">Reabastecer</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="alert-product">Sin alertas de stock mínimo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Últimos Escaneos QR -->
            <div class="qr-section">
                <h4 class="qr-section-title">
                    <i class="fa-solid fa-qrcode"></i> ÚLTIMOS ESCANEOS QR
                </h4>
                <div class="qr-list">
                    @forelse ($escaneos as $escaneo)
                        <div class="qr-item">
                            <div class="qr-avatar qr-avatar--{{ $escaneo['clase'] }}">{{ $escaneo['iniciales'] }}</div>
                            <div class="qr-info">
                                <div class="qr-name">{{ $escaneo['nombre'] }}</div>
                                <div class="qr-detail">{{ $escaneo['detalle'] }}</div>
                            </div>
                            <span class="qr-badge qr-badge--{{ $escaneo['clase'] }}">{{ $escaneo['cargo'] }}</span>
                            <span class="qr-time">{{ $escaneo['horaTexto'] }}</span>
                        </div>
                    @empty
                        <div class="qr-item">
                            <div class="qr-info">
                                <div class="qr-detail">Sin escaneos registrados hoy.</div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection
