@extends('layouts.admin')

@section('title', 'Reporte de Ventas')

@section('styles')
    @vite(['resources/css/reporte_ventas.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <h2 class="section-title">Reporte de Ventas</h2>
        <p class="section-subtitle">Resumen de ventas registradas en el sistema.</p>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon--emerald">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <div style="min-width: 0;">
                <p class="stat-label">Ingresos totales</p>
                <p class="stat-value">S/ {{ number_format($totalIngresos, 2) }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--blue">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div style="min-width: 0;">
                <p class="stat-label">Ventas</p>
                <p class="stat-value">{{ $ventas->count() }}</p>
                <p class="stat-sub">{{ $totalBoletas }} boleta(s) · {{ $totalFacturas }} factura(s)</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--purple">
                <i class="fa-solid fa-calculator"></i>
            </div>
            <div style="min-width: 0;">
                <p class="stat-label">Ticket promedio</p>
                <p class="stat-value">S/ {{ number_format($promedio, 2) }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--amber">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div style="min-width: 0;">
                <p class="stat-label">Unidades vendidas</p>
                <p class="stat-value">{{ $unidadesVendidas }}</p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filters-card">
        <div class="filter-group">
            <label class="filter-label">Desde</label>
            <input type="date" id="filtro-desde" onchange="aplicarFiltros()" class="filter-input">
        </div>
        <div class="filter-group">
            <label class="filter-label">Hasta</label>
            <input type="date" id="filtro-hasta" onchange="aplicarFiltros()" class="filter-input">
        </div>
        <div class="filter-group">
            <label class="filter-label">Comprobante</label>
            <select id="filtro-tipo" onchange="aplicarFiltros()" class="filter-select">
                <option value="">Todos</option>
                <option value="Boleta">Boleta</option>
                <option value="Factura">Factura</option>
            </select>
        </div>
        <div class="search-group">
            <label class="filter-label">Buscar</label>
            <div style="position: relative;">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="filtro-texto" oninput="aplicarFiltros()" placeholder="N° comprobante o cliente..." class="filter-input search-input">
            </div>
        </div>
        <button type="button" onclick="limpiarFiltros()" class="btn-clear">
            Limpiar
        </button>
    </div>

    <!-- TABLA VENTAS -->
    <div class="table-card">
        <div class="table-toolbar">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-table-list"></i>
                <span>Mostrando <strong id="contador-mostradas" style="color: #111827;">{{ $ventas->count() }}</strong> de {{ $ventas->count() }} venta(s)</span>
            </div>
            <span style="font-size: 12px; color: #9ca3af;">Haz clic en una fila para ver los productos</span>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Comprobante</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Tipo</th>
                        <th style="text-align: center;">Ítems</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ventas as $venta)
                        <tr class="venta-fila"
                            data-fecha="{{ $venta->created_at ? $venta->created_at->format('Y-m-d') : '' }}"
                            data-tipo="{{ $venta->tipo_comprobante }}"
                            data-buscar="{{ strtolower($venta->numero_comprobante . ' ' . ($venta->cliente?->nombre ?? '')) }}"
                            onclick="toggleDetalle({{ $venta->id }})">
                            <td>
                                <span class="badge-comprobante">{{ $venta->numero_comprobante }}</span>
                            </td>
                            <td style="color: #4b5563;">{{ $venta->created_at ? $venta->created_at->format('d/m/Y H:i') : '—' }}</td>
                            <td style="font-weight: 500; color: #1f2937;">{{ $venta->cliente?->nombre }}</td>
                            <td style="color: #6b7280;">{{ $venta->user?->nombre_usuario }}</td>
                            <td>
                                <span class="badge-tipo {{ $venta->tipo_comprobante === 'Boleta' ? 'badge-tipo--boleta' : 'badge-tipo--factura' }}">{{ $venta->tipo_comprobante }}</span>
                            </td>
                            <td style="text-align: center; color: #4b5563;">{{ $venta->detalles->sum('cantidad') }}</td>
                            <td style="text-align: right; font-weight: 600; color: #111827;">S/ {{ number_format($venta->total, 2) }}</td>
                        </tr>
                        <tr id="detalle-{{ $venta->id }}" class="detail-row hidden">
                            <td colspan="7" style="padding: 0;">
                                <div class="detail-content">
                                    <div class="detail-title">
                                        <i class="fa-solid fa-list" style="color: #2563eb;"></i> Productos de {{ $venta->numero_comprobante }}
                                    </div>
                                    <div class="detail-table-wrap">
                                        <table class="detail-table">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th style="text-align: center;">Cantidad</th>
                                                    <th style="text-align: right;">P. Unitario</th>
                                                    <th style="text-align: right;">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($venta->detalles as $detalle)
                                                    <tr>
                                                        <td style="color: #374151; font-weight: 500;">{{ $detalle->producto?->nombre ?? 'Producto eliminado' }}</td>
                                                        <td style="text-align: center; color: #4b5563;">{{ $detalle->cantidad }}</td>
                                                        <td style="text-align: right; color: #4b5563;">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                                                        <td style="text-align: right; font-weight: 600; color: #1f2937;">S/ {{ number_format($detalle->subtotal, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="subtotal-row">
                                                    <td colspan="3" style="text-align: right;">Total</td>
                                                    <td style="text-align: right;">S/ {{ number_format($venta->total, 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <p style="font-weight: 500; color: #6b7280;">No hay ventas registradas</p>
                                    <p style="font-size: 12px; color: #9ca3af; margin-top: 4px;">Las ventas realizadas desde el POS aparecerán en este reporte.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <p id="sin-resultados" class="hidden" style="padding: 32px 20px; text-align: center; color: #9ca3af; font-size: 14px;">No se encontraron ventas con esos filtros.</p>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/reporte_ventas.js'])
@endpush