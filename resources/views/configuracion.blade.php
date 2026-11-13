@extends('layouts.admin')

@section('title', 'Configuración')

@section('styles')
    @vite(['resources/css/configuracion.css'])
@endsection

@section('content')
    <!-- Título de Sección -->
    <div class="section-header">
        <div>
            <h2 class="section-title">Configuración</h2>
            <p class="section-subtitle">Define el horario, la tolerancia de tardanza y los valores por defecto del sistema.</p>
        </div>
    </div>

    <!-- Alertas -->
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-list">
            <p><i class="fa-solid fa-triangle-exclamation"></i> No se pudo guardar la configuración:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORMULARIO DE HORARIO -->
    <section class="config-card">
        <div class="config-header">
            <div class="config-icon">
                <i class="fa-solid fa-business-time"></i>
            </div>
            <div>
                <h3 class="config-title">Horario laboral</h3>
                <p class="config-subtitle">Estas reglas aplican a todo el personal y a los productos nuevos.</p>
            </div>
        </div>

        <form action="/configuracion" method="POST" class="config-form">
            @csrf
            <div class="config-field">
                <label class="form-label" for="hora_entrada">Hora de entrada</label>
                <input type="time" name="hora_entrada" id="hora_entrada" class="form-input"
                       value="{{ old('hora_entrada', $horaEntrada) }}" required>
                <p class="config-hint">
                    <i class="fa-solid fa-circle-info"></i>
                    Quien registre su entrada después de esta hora se marcará como <strong>Tardanza</strong>.
                </p>
            </div>

            <div class="config-field">
                <label class="form-label" for="hora_salida">Hora de salida</label>
                <input type="time" name="hora_salida" id="hora_salida" class="form-input"
                       value="{{ old('hora_salida', $horaSalida) }}" required>
                <p class="config-hint">
                    <i class="fa-solid fa-circle-info"></i>
                    Quien registre su salida antes de esta hora se marcará como <strong>Salida anticipada</strong>.
                </p>
            </div>

            <div class="config-field">
                <label class="form-label" for="tolerancia_tardanza">Tolerancia de tardanza (minutos)</label>
                <input type="number" name="tolerancia_tardanza" id="tolerancia_tardanza" class="form-input"
                       value="{{ old('tolerancia_tardanza', $toleranciaTardanza) }}"
                       min="0" max="120" step="1" inputmode="numeric" required>
                <p class="config-hint">
                    <i class="fa-solid fa-circle-info"></i>
                    Minutos de gracia después de la hora de entrada. Con entrada a las
                    <strong>{{ $horaEntrada }}</strong> y <strong>{{ (int) old('tolerancia_tardanza', $toleranciaTardanza) }} min</strong>,
                    quien marque hasta las <strong>{{ \Carbon\Carbon::parse($horaEntrada)->addMinutes((int) old('tolerancia_tardanza', $toleranciaTardanza))->format('H:i') }}</strong> pasa como <strong>presente</strong>.
                </p>
            </div>

            <div class="config-field">
                <label class="form-label" for="stock_minimo_defecto">Stock mínimo por defecto</label>
                <input type="number" name="stock_minimo_defecto" id="stock_minimo_defecto" class="form-input"
                       value="{{ old('stock_minimo_defecto', $stockMinimoDefecto) }}"
                       min="0" max="9999" step="1" inputmode="numeric" required>
                <p class="config-hint">
                    <i class="fa-solid fa-circle-info"></i>
                    Valor con el que se prellena el campo <strong>Stock mínimo</strong> al registrar un producto nuevo.
                </p>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Guardar configuración
            </button>
        </form>

        <p class="config-note">
            <i class="fa-solid fa-shield-halved"></i>
            Solo los administradores pueden modificar esta configuración. Los cambios aplican desde el siguiente registro de asistencia.
        </p>
    </section>

    <p style="font-size: 12px; color: var(--ink-faint); margin-top: 20px; text-align: center;">
        Grupo MDL E.I.R.L. — Módulo de Recursos Humanos
    </p>
@endsection
