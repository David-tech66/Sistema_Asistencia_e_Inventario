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
                <h3 class="register-title">Registrar asistencia</h3>
                <p class="register-subtitle">Elige el modo, escribe o escanea el DNI del empleado.</p>
            </div>
        </div>

        @if ($empleados->isEmpty())
            <div class="alert alert-warning">
                <i class="fa-solid fa-triangle-exclamation"></i>
                No hay empleados activos. Primero registra un empleado en el módulo Empleados.
            </div>
        @else
            <!-- Zona fija para el mensaje del DNI: su espacio ya está reservado,
                 así el mensaje aparece sin mover el resto de la página. -->
            <div class="dni-slot">
                <div id="dni-preview" class="dni-preview" aria-live="polite"></div>
            </div>
            <form action="/asistencias/registrar" method="POST" class="register-form">
                @csrf
                <div class="register-field">
                    <span class="form-label">Modo de registro</span>
                    <div class="modo-switch" role="radiogroup" aria-label="Modo de registro">
                        <label class="modo-option">
                            <input type="radio" name="modo" value="entrada"
                                   {{ old('modo', 'entrada') === 'entrada' ? 'checked' : '' }}>
                            <i class="fa-solid fa-right-to-bracket"></i> Entrada
                        </label>
                        <label class="modo-option">
                            <input type="radio" name="modo" value="salida"
                                   {{ old('modo') === 'salida' ? 'checked' : '' }}>
                            <i class="fa-solid fa-right-from-bracket"></i> Salida
                        </label>
                    </div>
                </div>
                <div class="register-field">
                    <label class="form-label" for="dni">DNI del empleado</label>
                    <input type="text" name="dni" id="dni" class="form-input"
                           value="{{ old('dni') }}" required maxlength="15"
                           pattern="[0-9]*" inputmode="numeric" autocomplete="off"
                           autofocus                            placeholder="Escribe o escanea el DNI (8 dígitos)">
                </div>
                <button type="submit" class="btn btn-primary" id="btn-registrar">
                    <i class="fa-solid fa-clock"></i> <span id="btn-registrar-texto">Registrar entrada</span>
                </button>
            </form>
        @endif

        <p class="register-note">
            <i class="fa-solid fa-circle-info"></i>
            Escanea el código de barras del DNI o escríbelo y presiona Enter. La fecha y la hora se capturan automáticamente.
            Horario: entrada {{ $horarioEntrada }} · salida {{ $horarioSalida }}. Primero registra la entrada; la salida solo está disponible cuando ya existe entrada hoy.
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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($asistencias as $asistencia)
                        <tr>
                            <td>{{ $asistencia->id }}</td>
                            <td>
                                <span class="entity-name">{{ $asistencia->empleado?->nombre_completo ?? 'Empleado no disponible' }}</span>
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
                                @if (!$asistencia->hora_entrada)
                                    <span class="asistencia-badge asistencia-badge--sin">Sin entrada</span>
                                @elseif ($asistencia->estado === 'tardanza')
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
                            <td class="cell-actions">
                                <div class="actions-group">
                                    <button type="button" class="btn-delete"
                                            onclick="eliminarAsistencia({{ $asistencia->id }}, '{{ addslashes($asistencia->empleado?->nombre_completo ?? 'Empleado no disponible') }}', '{{ $asistencia->fecha }}')">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
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

    <!-- MODAL: Confirmar eliminación -->
    <div id="modal-eliminar" class="modal-overlay hidden">
        <div class="modal-backdrop" onclick="cerrarModal('modal-eliminar')"></div>
        <div class="modal-dialog" style="text-align: center;">
            <div class="modal-icon modal-icon--red">
                <i class="fa-solid fa-trash"></i>
            </div>
            <h3 class="modal-title">Eliminar asistencia</h3>
            <p style="font-size: 14px; color: #6b7280; margin-top: 8px;">¿Seguro que deseas eliminar la asistencia de <span id="eliminar-nombre" style="font-weight: 600; color: #111827;"></span>? Esta acción no se puede deshacer.</p>
            <form id="form-eliminar" method="POST" action="/asistencias/0">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modal-eliminar')">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/asistencias.js'])
@endpush
