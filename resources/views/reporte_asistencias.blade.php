@extends('layouts.admin')

@section('title', 'Reporte de Asistencias')

@section('styles')
    @vite(['resources/css/reporte_asistencias.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <h2 class="section-title">Reporte de Asistencias</h2>
        <p class="section-subtitle">Análisis de asistencias: resumen, puntualidad y detalle por empleado.</p>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon--blue">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div style="min-width: 0;">
                <p class="stat-label">Total registros</p>
                <p class="stat-value">{{ $asistencias->count() }}</p>
                <p class="stat-sub">{{ $sinSalida }} sin salida</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--emerald">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div style="min-width: 0;">
                <p class="stat-label">Presentes</p>
                <p class="stat-value">{{ $presentes }}</p>
                <p class="stat-sub">llegadas sin tardanza</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--amber">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div style="min-width: 0;">
                <p class="stat-label">Tardanzas</p>
                <p class="stat-value">{{ $tardanzas }}</p>
                <p class="stat-sub">después de la hora de entrada</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon--purple">
                <i class="fa-solid fa-person-running"></i>
            </div>
            <div style="min-width: 0;">
                <p class="stat-label">Salidas anticipadas</p>
                <p class="stat-value">{{ $salidasAnticipadas }}</p>
                <p class="stat-sub">antes de la hora de salida</p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filters-card">
        <div class="filter-group">
            <label class="filter-label" for="filtro-desde">Desde</label>
            <input type="date" id="filtro-desde" onchange="aplicarFiltros()" class="filter-input">
        </div>
        <div class="filter-group">
            <label class="filter-label" for="filtro-hasta">Hasta</label>
            <input type="date" id="filtro-hasta" onchange="aplicarFiltros()" class="filter-input">
        </div>
        <div class="filter-group">
            <label class="filter-label" for="filtro-estado">Estado</label>
            <select id="filtro-estado" onchange="aplicarFiltros()" class="filter-select">
                <option value="">Todos</option>
                <option value="presente">Presente</option>
                <option value="tardanza">Tardanza</option>
                <option value="sin">Sin entrada</option>
                <option value="sin-salida">Sin salida</option>
            </select>
        </div>
        <div class="search-group">
            <label class="filter-label" for="filtro-texto">Buscar</label>
            <div style="position: relative;">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="filtro-texto" oninput="aplicarFiltros()" placeholder="Nombre o DNI del empleado..." class="filter-input search-input">
            </div>
        </div>
        <button type="button" onclick="limpiarFiltros()" class="btn-clear">
            Limpiar
        </button>
    </div>

    <!-- TABLA DE ASISTENCIAS -->
    <div class="table-card">
        <div class="table-toolbar">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-table-list"></i>
                <span>Mostrando <strong id="contador-mostradas" style="color: #111827;">{{ $asistencias->count() }}</strong> de {{ $asistencias->count() }} registro(s)</span>
            </div>
            <span style="font-size: 12px; color: #9ca3af;">Reporte de solo lectura</span>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Fecha</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($asistencias as $asistencia)
                        @php
                            $estadoFila = $asistencia->hora_entrada ? ($asistencia->estado ?: 'presente') : 'sin';
                        @endphp
                        <tr class="asistencia-fila"
                            data-fecha="{{ $asistencia->fecha }}"
                            data-estado="{{ $estadoFila }}"
                            data-salida="{{ $asistencia->hora_salida ? '1' : '0' }}"
                            data-buscar="{{ e(strtolower(($asistencia->empleado?->nombre_completo ?? '') . ' ' . ($asistencia->empleado?->dni ?? ''))) }}">
                            <td>
                                <div class="cell-empleado-nombre">{{ $asistencia->empleado?->nombre_completo ?? 'Empleado no disponible' }}</div>
                                @if ($asistencia->empleado?->dni)
                                    <div class="cell-empleado-dni">DNI {{ $asistencia->empleado->dni }}</div>
                                @endif
                            </td>
                            <td style="color: #4b5563;">{{ $asistencia->fecha }}</td>
                            <td>{{ $asistencia->hora_entrada ?? '--:--:--' }}</td>
                            <td>
                                @if ($asistencia->hora_salida)
                                    {{ $asistencia->hora_salida }}
                                @else
                                    <span style="color: #9ca3af;">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                @if ($estadoFila === 'sin')
                                    <span class="asistencia-badge asistencia-badge--sin">Sin entrada</span>
                                @elseif ($estadoFila === 'tardanza')
                                    <span class="asistencia-badge asistencia-badge--tardanza">
                                        <i class="fa-solid fa-clock"></i> Tardanza
                                    </span>
                                @else
                                    <span class="asistencia-badge asistencia-badge--presente">
                                        <i class="fa-solid fa-circle-check"></i> Presente
                                    </span>
                                @endif
                                @if ($asistencia->salida_anticipada)
                                    <span class="asistencia-badge asistencia-badge--anticipada">
                                        <i class="fa-solid fa-person-running"></i> Salida anticipada
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-calendar-xmark"></i>
                                    </div>
                                    <p style="font-weight: 500; color: #6b7280;">No hay asistencias registradas</p>
                                    <p style="font-size: 12px; color: #9ca3af; margin-top: 4px;">Las asistencias registradas en el módulo de Recursos Humanos aparecerán en este reporte.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <p id="sin-resultados" class="sin-resultados-msg hidden">No se encontraron asistencias con esos filtros.</p>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/reporte_asistencias.js'])
@endpush
