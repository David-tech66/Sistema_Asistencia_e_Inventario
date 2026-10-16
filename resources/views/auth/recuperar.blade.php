@extends('layouts.app')

@section('title', 'Recuperar Contraseña - Grupo MDL E.I.R.L.')

@section('styles')
    @vite(['resources/css/recuperar.css'])
@endsection

@section('content')
<div class="rcv-login">

    <!-- Panel Izquierdo: Formulario de Recuperación -->
    <div class="rcv-panel-left">
        
        <!-- Botón de cambio de tema (Luna / Sol) -->
        <button id="themeToggle" data-theme-toggle type="button" class="rcv-theme-toggle" aria-label="Cambiar tema">
            <!-- Luna (Visible en modo claro) -->
            <svg id="moonIcon" class="rcv-icon-moon" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
            <!-- Sol (Visible en modo oscuro) -->
            <svg id="sunIcon" class="rcv-icon-sun" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
        </button>

        <div class="rcv-card">
            <!-- Cabecera unificada y completamente centrada -->
            <div class="rcv-header-wrap">
                <div class="rcv-logo-wrap">
                    <!-- Logo para Modo Claro (Lazo Azul) -->
                    <img src="{{ asset('images/logo-MDL-2.png') }}" alt="Logo MDL Claro" class="rcv-logo rcv-logo-light">
                    
                    <!-- Logo para Modo Oscuro (Lazo Blanco) -->
                    <img src="{{ asset('images/logo-MDL.png') }}" alt="Logo MDL Oscuro" class="rcv-logo rcv-logo-dark">
                </div>

                <div>
                    <h2 class="rcv-title">Recuperar Contraseña</h2>
                    <p class="rcv-subtitle">Ingresa tu Correo Electrónico para enviarte un código de verificación para tu contraseña</p>
                </div>
            </div>

            @if (session('status'))
                <div class="rcv-alert-status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="#" class="rcv-form">
                @csrf

                <div>
                    <label class="rcv-label">Correo Electrónico</label>
                    <div class="rcv-input-wrap">
                        <span class="rcv-input-icon">@</span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="rcv-input">
                    </div>
                </div>

                <!-- Botón Siguiente con efecto de hover -->
                <button type="submit" class="rcv-btn">
                    Siguiente
                </button>
            </form>

            <div class="rcv-back-wrap">
                <a href="/login" class="rcv-back">
                    Regresar al Login
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

        </div>
    </div>

    <!-- Panel Derecho: Imagen y Listado de Servicios (Iconos limpios idénticos a Figma) -->
    <div class="rcv-panel-right">
        <div class="rcv-panel-bg">
            <img src="{{ asset('images/fondo-empresa-MDL.png') }}" alt="Setup Informático MDL">
            <div class="rcv-panel-overlay"></div>
        </div>

        <div class="rcv-panel-top"></div>

        <!-- Contenido con los 4 servicios limpios alineados al diseño -->
        <div class="rcv-panel-list">
            <div class="rcv-service">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.9 6.9a2.12 2.12 0 01-3-3l6.9-6.9a6 6 0 017.94-7.94l-3.76 3.76z" />
                </svg>
                <span>Mantenimiento de Equipos Informáticos</span>
            </div>

            <div class="rcv-service">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12l8-4.5M12 12v9M12 12L4 7.5"/>
                </svg>
                <span>Inventario de Productos</span>
            </div>

            <div class="rcv-service">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l8.66 5v10L12 22l-8.66-5V7L12 2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v10M9 11l3-2 3 2"/>
                </svg>
                <span>Ventas en tiempo real</span>
            </div>

            <div class="rcv-service">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 18v-6a9 9 0 0118 0v6M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z"/>
                </svg>
                <span>Soporte de Servicios</span>
            </div>
        </div>

    </div>
</div>
@endsection