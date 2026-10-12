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
            <div class="rcv-logo-wrap">
                <img src="{{ asset('images/logo-MDL.png') }}" alt="Logo MDL" class="rcv-logo">
            </div>

            <div>
                <h2 class="rcv-title">Recuperar Contraseña</h2>
                <p class="rcv-subtitle">Ingresa tu Correo Electronico para enviarte un código de verificación para tu contraseña</p>
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
                            class="rcv-input"
                            placeholder="correo@ejemplo.com">
                    </div>
                </div>

                <!-- Botón Siguiente con efecto de hover claro -->
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

            <div class="rcv-footer">
                <span>GRUPO MDL E.I.R.L.</span>
            </div>
        </div>
    </div>

    <!-- Panel Derecho: Imagen y Listado de Servicios -->
    <div class="rcv-panel-right">
        <div class="rcv-panel-bg">
            <img src="{{ asset('images/logo-empresa-MDL.jpeg') }}" alt="Setup Informático MDL">
            <div class="rcv-panel-overlay"></div>
        </div>

        <div class="rcv-panel-top"></div>

        <!-- Contenido con los 4 servicios -->
        <div class="rcv-panel-list">
            <div class="rcv-service">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                <span>Mantenimiento de Equipos Informaticos</span>
            </div>

            <div class="rcv-service">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Inventario de Productos</span>
            </div>

            <div class="rcv-service">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Ventas en tiempo real</span>
            </div>

            <div class="rcv-service">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                <span>Soporte de Servicios</span>
            </div>
        </div>

        <div class="rcv-panel-footer">
            GRUPO MDL E.I.R.L.
        </div>
    </div>
</div>
@endsection
