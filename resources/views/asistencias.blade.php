@extends('layouts.admin')

@section('title', 'Asistencias')

@section('styles')
    @vite(['resources/css/asistencias.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <div>
            <h2 class="section-title">Control de Asistencias</h2>
            <p class="section-subtitle">Registra las entradas y consulta las asistencias del personal.</p>
        </div>
    </div>

    <!-- Alertas -->
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-list">
            <p><i class="fa-solid fa-triangle-exclamation"></i> No se pudo registrar la asistencia:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORMULARIO DE REGISTRO -->
    <section class="register-card">
        <div class="register-header">
            <div class="register-icon">
                <i class="fa-solid fa-user-clock"></i>
            </div>
            <div>
                <h3 class="register-title">Registrar entrada</h3>
                <p class="register-subtitle">Selecciona al trabajador para registrar su asistencia.</p>
            </div>
        </div>

        @if ($empleados->isEmpty())
            <div class="alert alert-warning">
                <i class="fa-solid fa-triangle-exclamation"></i>
                No hay usuarios registrados. Primero registra un empleado en el módulo Empleados.
            </div>
        @else
            <form action="/asistencias/entrada" method="POST" class="register-form">
                @csrf
                <div class="register-field">
                    <label class="form-label" for="user_id">Seleccionar empleado</label>
                    <select name="user_id" id="user_id" required class="form-input">
                        <option value="">Selecciona un empleado</option>
                        @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->id }}" {{ old('user_id') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->nombre_usuario }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-clock"></i> Registrar entrada
                </button>
            </form>
        @endif

        <p class="register-note">
            <i class="fa-solid fa-circle-info"></i>
            La fecha y la hora de entrada se registran automáticamente.
        </p>
    </section>

    <!-- TABLA DE ASISTENCIAS -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-total">
                <i class="fa-solid fa-user-check table-total-icon"></i>
                <span>Total: <strong>{{ $asistencias->count() }}</strong> registro(s)</span>
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Empleado</th>
                        <th>Fecha</th>
                        <th>Hora de entrada</th>
                        <th>Hora de salida</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($asistencias as $asistencia)
                        <tr>
                            <td>{{ $asistencia->id }}</td>
                            <td>
                                <span class="entity-name">{{ $asistencia->user?->nombre_usuario ?? 'Usuario no disponible' }}</span>
                            </td>
                            <td class="cell-date">{{ $asistencia->fecha }}</td>
                            <td>{{ $asistencia->hora_entrada ?? '--:--:--' }}</td>
                            <td>
                                @if ($asistencia->hora_salida)
                                    {{ $asistencia->hora_salida }}
                                @else
                                    <span class="cell-muted">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                @if ($asistencia->hora_entrada)
                                    <span class="asistencia-badge asistencia-badge--presente">
                                        <i class="fa-solid fa-circle-check"></i> Presente
                                    </span>
                                @else
                                    <span class="asistencia-badge asistencia-badge--sin">Sin entrada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-calendar-xmark"></i>
                                    </div>
                                    <p class="empty-title">No hay asistencias registradas</p>
                                    <p class="empty-text">Registra la primera entrada utilizando el formulario.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p style="font-size: 12px; color: #9ca3af; margin-top: 20px; text-align: center;">
        Grupo MDL E.I.R.L. — Módulo de Recursos Humanos
    </p>
@endsection
