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
            <p class="section-subtitle">Define la hora de entrada y la hora de salida del personal.</p>
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
                <p class="config-subtitle">Estas horas definen las reglas de asistencia para todo el personal.</p>
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

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Guardar horario
            </button>
        </form>

        <p class="config-note">
            <i class="fa-solid fa-shield-halved"></i>
            Solo los administradores pueden modificar esta configuración. Los cambios aplican desde el siguiente registro de asistencia.
        </p>
    </section>

    <p style="font-size: 12px; color: #9ca3af; margin-top: 20px; text-align: center;">
        Grupo MDL E.I.R.L. — Módulo de Recursos Humanos
    </p>
@endsection
