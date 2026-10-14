@extends('layouts.app')

@section('title', 'Iniciar Sesión - Grupo MDL E.I.R.L.')

@section('styles')
    @vite(['resources/css/login.css'])
@endsection

@section('content')
<div class="mdl-login">

    <!-- Panel Izquierdo: Imagen Institucional y Servicios -->
    <div class="mdl-panel-left">
        <!-- Imagen de fondo con overlay -->
        <div class="mdl-panel-bg">
            <img src="{{ asset('images/logo-empresa-MDL.jpeg') }}" alt="Setup Informático MDL">
            <div class="mdl-panel-overlay"></div>
        </div>

        <!-- Logo superior -->
        <div class="mdl-panel-header">
            <span class="mdl-panel-brand">
                <img src="{{ asset('images/logo-MDL.png') }}" alt="Logo MDL">
            </span>
        </div>

        <!-- Contenido central con los textos de los servicios -->
        <div class="mdl-panel-content">
            <h2 class="mdl-panel-title">
                Mantenimiento de equipos informáticos<br>
                Venta de productos tecnológicos<br>
                Soporte y soluciones
            </h2>
            <div class="mdl-panel-divider"></div>

            <!-- Iconos de servicios inferiores -->
            <div class="mdl-panel-icons">
                <div class="mdl-panel-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="mdl-panel-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="mdl-panel-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div class="mdl-panel-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                </div>
            </div>
        </div>

        <div class="mdl-panel-footer">
            GRUPO MDL E.I.R.L.
        </div>
    </div>

    <!-- Panel Derecho: Formulario de Login -->
    <div class="mdl-panel-right">
        <!-- Botón de cambio de tema (Luna / Sol) -->
        <button id="themeToggle" data-theme-toggle type="button" class="mdl-theme-toggle" aria-label="Cambiar tema">
            <!-- Luna (Visible en modo claro) -->
            <svg id="moonIcon" class="mdl-icon-moon" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
            <!-- Sol (Visible en modo oscuro) -->
            <svg id="sunIcon" class="mdl-icon-sun" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
        </button>

        <div class="mdl-form-card">

            <div class="mdl-brand-mobile">
                <span>
                    GRUPO <span class="mdl-red">MDL</span> <span class="mdl-blue">E.I.R.L.</span>
                </span>
            </div>

            <div>
                <h2 class="mdl-title">Iniciar Sesión</h2>
                <p class="mdl-subtitle">Ingrese su correo y contraseña para acceder</p>
            </div>

            @if ($errors->any())
                <div class="mdl-alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="#" class="mdl-form">
                @csrf

                <!-- Correo Electrónico -->
                <div>
                    <label class="mdl-label">Correo Electrónico</label>
                    <div class="mdl-input-wrap">
                        <span class="mdl-input-icon">@</span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="mdl-input"
                            placeholder="correo@ejemplo.com">
                    </div>
                </div>

                <!-- Contraseña -->
                <div>
                    <div class="mdl-field-header">
                        <label class="mdl-label">Contraseña</label>
                        <a href="/recuperar-contrasena" class="mdl-forgot">¿Olvidaste tu contraseña?</a>
                    </div>
                    <div class="mdl-input-wrap">
                        <span class="mdl-input-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input type="password" id="password" name="password" required
                            class="mdl-input mdl-input--password"
                            placeholder="••••••••••">
                        <button type="button" onclick="togglePassword()" class="mdl-eye-btn" aria-label="Mostrar contraseña">
                            <svg id="eyeIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7C19.27 16.057 15.632 19 12 19c-3.633 0-7.27-2.943-8.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Recordar Sesión -->
                <div class="mdl-remember">
                    <label>
                        <input type="checkbox" name="remember" class="mdl-checkbox">
                        <span>Recordar Sesión</span>
                    </label>
                </div>

                <!-- Botón Iniciar Sesión con efectos de hover claros -->
                <button type="submit" class="mdl-btn">
                    Iniciar Sesión
                </button>
            </form>

            <div class="mdl-footer">
                <span>GRUPO MDL E.I.R.L.</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7C19.27 16.057 15.632 19 12 19c-3.633 0-7.27-2.943-8.542-7z"/>';
        }
    }
</script>
@endpush    