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

        <!-- Contenido central con Logo y Servicios -->
        <div class="mdl-panel-content">
            <!-- Logo centrado -->
            <div class="mdl-panel-brand-center">
                <img src="{{ asset('images/logo-MDL.png') }}" alt="Logo MDL" class="mdl-logo-img">
            </div>

            <h2 class="mdl-panel-title">
                Mantenimiento de equipos informáticos<br>
                Venta de productos tecnológicos<br>
                Soporte y soluciones
            </h2>
            <!-- Línea roja divisoria superior -->
            <div class="mdl-divider-line"></div>

            <!-- Iconos de servicios flotantes idénticos a Figma -->
            <div class="mdl-panel-icons">
                <!-- 1. Mantenimiento / Herramientas -->
                <div class="mdl-panel-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.9 6.9a2.12 2.12 0 01-3-3l6.9-6.9a6 6 0 017.94-7.94l-3.76 3.76z" />
                    </svg>
                </div>

                <!-- 2. Inventario / Cajas -->
                <div class="mdl-panel-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 12l8-4.5M12 12v9M12 12L4 7.5"/>
                    </svg>
                </div>

                <!-- 3. Reportes / Gráfico Hexagonal -->
                <div class="mdl-panel-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l8.66 5v10L12 22l-8.66-5V7L12 2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v10M9 11l3-2 3 2"/>
                    </svg>
                </div>

                <!-- 4. Soporte / Auriculares -->
                <div class="mdl-panel-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 18v-6a9 9 0 0118 0v6M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <!-- Panel Derecho: Formulario de Login -->
    <div class="mdl-panel-right">
        <!-- Botón de cambio de tema (Luna / Sol) -->
        <button id="themeToggle" data-theme-toggle type="button" class="mdl-theme-toggle" aria-label="Cambiar tema">
            <!-- Luna (Visible en modo claro) -->
            <svg id="moonIcon" class="mdl-icon-moon" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
            <!-- Sol (Visible en modo oscuro) -->
            <svg id="sunIcon" class="mdl-icon-sun" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
            </svg>
        </button>

        <div class="mdl-form-card">

            <!-- Logo Dual Centrado (Visible solo en Móvil) -->
            <div class="mdl-mobile-logo-container">
                <img src="{{ asset('images/logo-MDL-2.png') }}" alt="Logo MDL Claro" class="mdl-mobile-logo mdl-logo-light">
                <img src="{{ asset('images/logo-MDL.png') }}" alt="Logo MDL Oscuro" class="mdl-mobile-logo mdl-logo-dark">
            </div>

            <div class="mdl-mobile-text-center">
                <h2 class="mdl-title">Iniciar Sesión</h2>
                <p class="mdl-subtitle">Ingrese su correo y contraseña para acceder</p>
            </div>

            @if ($errors->any())
            <div class="mdl-alert-error">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mdl-form">
                @csrf

                <!-- Correo Electrónico -->
                <div>
                    <label class="mdl-label">Correo Electrónico</label>
                    <div class="mdl-input-wrap">
                        <span class="mdl-input-icon">@</span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="mdl-input">
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
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" required
                            class="mdl-input mdl-input--password"
                            placeholder="••••••••••">
                        <button type="button" id="togglePasswordBtn" class="mdl-eye-btn" aria-label="Mostrar contraseña">
                            <svg id="eyeIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7C19.27 16.057 15.632 19 12 19c-3.633 0-7.27-2.943-8.542-7z" />
                            </svg>
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

        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/auth/login.js'])
@endpush