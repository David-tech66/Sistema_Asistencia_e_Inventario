@extends('layouts.admin')

@section('title', 'Panel Administrativo')

@section('content')
    <!-- Título de Sección -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Resumen Operativo</h2>
        <p class="text-sm text-gray-500 mt-1">Monitoreo en tiempo real de ventas, inventario y asistencia por código QR.</p>
    </div>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- KPI 1: Total de Productos -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col">
            <div class="flex justify-between items-start mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total de Productos</span>
                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">24</h3>
            <div class="mt-4 pt-3 border-t border-gray-50">
                <a href="/reportes/productos" class="text-xs font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    Ver productos <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- KPI 2: Stock Crítico -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col">
            <div class="flex justify-between items-start mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Stock Crítico</span>
                <div class="h-10 w-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">3 <span class="text-sm font-normal text-gray-400">artículos</span></h3>
            <div class="mt-4 pt-3 border-t border-gray-50">
                <span class="inline-flex items-center text-[11px] font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
                    <i class="fa-solid fa-circle-exclamation mr-1 text-[9px]"></i> Requiere reabastecimiento
                </span>
                <a href="/productos" class="text-xs font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1 mt-2">
                    Ver stock <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- KPI 3: Asistencias QR Hoy -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col">
            <div class="flex justify-between items-start mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Asistencias QR Hoy</span>
            </div>
            <!-- Gauge semicircular -->
            <div class="flex flex-col items-center -mt-2">
                <svg viewBox="0 0 120 70" class="w-28">
                    <!-- Fondo del gauge -->
                    <path d="M 10 60 A 50 50 0 0 1 110 60" fill="none" stroke="#e5e7eb" stroke-width="10" stroke-linecap="round"/>
                    <!-- Progreso del gauge (80%) -->
                    <path d="M 10 60 A 50 50 0 0 1 107.6 28.4" fill="none" stroke="#10b981" stroke-width="10" stroke-linecap="round"/>
                    <!-- Texto central -->
                    <text x="60" y="48" text-anchor="middle" class="text-lg font-bold" fill="#1f2937" font-size="14">12</text>
                    <text x="72" y="48" text-anchor="middle" class="text-gray-400" fill="#9ca3af" font-size="10">/15</text>
                    <text x="60" y="62" text-anchor="middle" fill="#6b7280" font-size="8">presentes</text>
                </svg>
            </div>
            <div class="mt-2 pt-3 border-t border-gray-50 w-full">
                <a href="/reportes/asistencias" class="text-xs font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    Ver reportes <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- KPI 4: Ventas del Día -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col">
            <div class="flex justify-between items-start mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Ventas del Día</span>
                <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-cash-register text-lg"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900">$1,450.00</h3>
            <div class="mt-4 pt-3 border-t border-gray-50">
                <span class="inline-flex items-center text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-1 rounded-full">
                    <i class="fa-solid fa-arrow-trend-up mr-1 text-[9px]"></i> +12.4% vs ayer
                </span>
                <a href="/reportes/ventas" class="text-xs font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1 mt-2">
                    Ver reportes <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

    </div>
@endsection
