@extends('layouts.admin')

@section('title', 'Reporte de Asistencias')

@section('styles')
    @vite(['resources/css/crud-components.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <div>
            <h2 class="section-title">Reporte de Asistencias</h2>
            <p class="section-subtitle">Resumen de asistencias registradas en el sistema.</p>
        </div>
    </div>

    <div class="table-card">
        <div class="table-wrap">
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <p class="empty-title">Reporte en preparación</p>
                <p class="empty-text">Próximamente se mostrará aquí el desglose de asistencias por empleado y rango de fechas.</p>
            </div>
        </div>
    </div>
@endsection
