<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Administrativo') - Grupo MDL E.I.R.L.</title>

    <!-- Fuentes e Iconos -->
    <link rel="icon" type="image/png" href="{{ asset('images/icono-MDL.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/sidebar_topbar.css', 'resources/js/sidebar_topbar.js'])
    @yield('styles')
    @stack('css')
</head>

<body class="app-layout">

    @php
        // Atributos comunes de los SVG (Lucide incrustados: se pintan con el HTML, sin JS)
        $svgAttrs = 'xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';
    @endphp

    <!-- SIDEBAR -->
    <aside class="sidebar" id="app-sidebar">

        <!-- Parte Superior Fija (Logo + Dashboards) -->
        <div class="sidebar-fixed-top">
            <div class="sidebar-header">
                <a href="/dashboard" class="sidebar-brand-link">
                    <img src="{{ asset('images/logo-MDL-2.png') }}" alt="Grupo MDI E.I.R.L." class="sidebar-logo-img">
                </a>
            </div>

            <div class="sidebar-top-group">
                <a href="/dashboard" class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <svg class="sidebar-icon" {!! $svgAttrs !!}><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    <span>Dashboards</span>
                </a>
            </div>
            <hr class="sidebar-divider">
        </div>

        <!-- Navegación Con Scroll (Inventario, Empleados, Asistencias, General) -->
        <nav class="sidebar-nav">

            <!-- Inventario (desplegable) -->
            <div class="sidebar-group">
                <button onclick="toggleMenu('inventario')" class="sidebar-item sidebar-group-btn">
                    <div class="sidebar-item-content">
                        <span>Inventario</span>
                    </div>
                    <i id="inventario-arrow" class="sidebar-arrow {{ request()->is('productos', 'categorias', 'marcas', 'ventas', 'clientes', 'proveedores') ? 'rotated' : '' }}"><svg {!! $svgAttrs !!}><path d="m6 9 6 6 6-6"/></svg></i>
                </button>

                <div id="inventario-submenu" class="sidebar-submenu {{ request()->is('productos', 'categorias', 'marcas', 'ventas', 'clientes', 'proveedores') ? 'open' : '' }}">
                    <a href="/productos" class="submenu-item {{ request()->is('productos') ? 'active' : '' }}">
                        <svg class="submenu-icon" {!! $svgAttrs !!}><path d="M16 10a4 4 0 0 1-8 0"/><path d="M3.103 6.034h17.794"/><path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"/></svg> Productos
                    </a>
                    <a href="/categorias" class="submenu-item {{ request()->is('categorias') ? 'active' : '' }}">
                        <svg class="submenu-icon" {!! $svgAttrs !!}><path d="M13.172 2a2 2 0 0 1 1.414.586l6.71 6.71a2.4 2.4 0 0 1 0 3.408l-4.592 4.592a2.4 2.4 0 0 1-3.408 0l-6.71-6.71A2 2 0 0 1 6 9.172V3a1 1 0 0 1 1-1z"/><path d="M2 7v6.172a2 2 0 0 0 .586 1.414l6.71 6.71a2.4 2.4 0 0 0 3.191.193"/><circle cx="10.5" cy="6.5" r=".5" fill="currentColor"/></svg> Categorías
                    </a>
                    <a href="/marcas" class="submenu-item {{ request()->is('marcas') ? 'active' : '' }}">
                        <svg class="submenu-icon" {!! $svgAttrs !!}><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m16 9-5.5 5.5L8 12"/></svg> Marcas
                    </a>
                    <a href="/ventas" class="submenu-item {{ request()->is('ventas') ? 'active' : '' }}">
                        <svg class="submenu-icon" {!! $svgAttrs !!}><path d="m2.05 2.05 1.099-.028a1 1 0 0 1 1.008.815l2.69 14.347A1 1 0 0 0 7.83 18H18"/><path d="M4.563 5h16.435a1 1 0 0 1 .981 1.204l-1.026 6.226A2 2 0 0 1 18.962 14H6.25"/><circle cx="18" cy="20" r="2"/><circle cx="8" cy="20" r="2"/></svg> Ventas
                    </a>
                    <a href="/clientes" class="submenu-item {{ request()->is('clientes') ? 'active' : '' }}">
                        <svg class="submenu-icon" {!! $svgAttrs !!}><path d="M17 21v-1a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v1"/><path d="M19 10h1a2 2 0 0 1 2 2v1"/><path d="M5 10H4a2 2 0 0 0-2 2v1"/><circle cx="12" cy="11" r="3"/><circle cx="18" cy="4" r="2"/><circle cx="6" cy="4" r="2"/></svg> Clientes
                    </a>
                    <a href="/proveedores" class="submenu-item {{ request()->is('proveedores') ? 'active' : '' }}">
                        <svg class="submenu-icon" {!! $svgAttrs !!}><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg> Proveedores
                    </a>
                </div>
            </div>

            <hr class="sidebar-divider">

            <!-- Empleados -->
            <a href="/users" class="sidebar-item {{ request()->is('users') ? 'active' : '' }}">
                <svg class="sidebar-icon" {!! $svgAttrs !!}><path d="M13.5 8h-3"/><path d="m15 2-1 2h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h3"/><path d="M16 22a4 4 0 0 0-8 0"/><path d="m9 2 3 6"/><circle cx="12" cy="15" r="3"/></svg>
                <span>Empleados</span>
            </a>

            <!-- Asistencias -->
            <a href="/asistencias" class="sidebar-item {{ request()->is('asistencias') ? 'active' : '' }}">
                <svg class="sidebar-icon" {!! $svgAttrs !!}><path d="m16 11 2 2 4-4"/><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                <span>Asistencias</span>
            </a>

            <!-- General (desplegable) -->
            <div class="sidebar-group">
                <button onclick="toggleMenu('general')" class="sidebar-item sidebar-group-btn">
                    <div class="sidebar-item-content">
                        <span>General</span>
                    </div>
                    <i id="general-arrow" class="sidebar-arrow {{ request()->is('reportes', 'reportes/*') ? 'rotated' : '' }}"><svg {!! $svgAttrs !!}><path d="m6 9 6 6 6-6"/></svg></i>
                </button>

                <div id="general-submenu" class="sidebar-submenu {{ request()->is('reportes', 'reportes/*') ? 'open' : '' }}">
                    <a href="/reportes/productos" class="submenu-item {{ request()->is('reportes/productos') ? 'active' : '' }}">
                        <svg class="submenu-icon" {!! $svgAttrs !!}><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M8 18v-1"/><path d="M12 18v-6"/><path d="M16 18v-3"/></svg> Reportes Productos
                    </a>
                    <a href="/reportes/asistencias" class="submenu-item {{ request()->is('reportes/asistencias') ? 'active' : '' }}">
                        <svg class="submenu-icon" {!! $svgAttrs !!}><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M16 22a4 4 0 0 0-8 0"/><circle cx="12" cy="15" r="3"/></svg> Reportes Asistencias
                    </a>
                    <a href="/reportes/ventas" class="submenu-item {{ request()->is('reportes/ventas') ? 'active' : '' }}">
                        <svg class="submenu-icon" {!! $svgAttrs !!}><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/></svg> Reportes Ventas
                    </a>
                </div>
            </div>
        </nav>

        <!-- Parte Inferior Fija (Configuración + Cerrar Sesión) -->
        <div class="sidebar-footer">
            <a href="#" class="sidebar-item">
                <svg class="sidebar-icon" {!! $svgAttrs !!}><path d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915"/><circle cx="12" cy="12" r="3"/></svg>
                <span>Configuración</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="sidebar-item logout-btn">
                    <svg class="sidebar-icon" {!! $svgAttrs !!}><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Fondo oscuro para móviles cuando el sidebar está abierto -->
    <div id="sidebar-backdrop" class="sidebar-backdrop" onclick="toggleSidebar()"></div>

    <!-- MAIN CONTAINER -->
    <div class="main-container">

        <!-- TOPBAR -->
        <header class="topbar">
            <div class="topbar-left">

                <!-- Botón hamburguesa (solo visible en pantallas pequeñas/móviles) -->
                <button class="topbar-menu-btn" onclick="toggleSidebar()">
                    <svg {!! $svgAttrs !!}><path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/></svg>
                </button>
                <h1 class="topbar-title">Panel Administrativo</h1>
            </div>

            <div class="topbar-right">
                <!-- Notificación (Campana limpia sin badge) -->
                <button class="topbar-notification-btn" aria-label="Notificaciones">
                    <svg class="topbar-icon" {!! $svgAttrs !!}><path d="M10.268 21a2 2 0 0 0 3.464 0"/><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/></svg>
                </button>

                <!-- Divisor vertical -->
                <div class="topbar-divider"></div>

                <!-- Perfil de usuario dinámico -->
                <div class="user-profile">
                    @auth
                    @php
                    $u = Auth::user();
                    // Obtener el nombre del array de atributos sin importar el case de la columna
                    $nombre = $u->NOMBRE_USUARIO ?? $u->nombre_usuario ?? $u->name ?? 'Usuario';
                    $rolRaw = strtolower($u->ROL ?? $u->rol ?? $u->role ?? 'admin');
                    $rol = ($rolRaw === 'admin') ? 'Administrador' : ucfirst($rolRaw);
                    @endphp

                    <div class="user-info">
                        <p class="user-name">{{ $nombre }}</p>
                        <p class="user-role">{{ $rol }}</p>
                    </div>

                    <img src="https://ui-avatars.com/api/?name={{ urlencode($nombre) }}&background=2563eb&color=ffffff&bold=true"
                        alt="Avatar de {{ $nombre }}"
                        class="user-avatar">
                    @else
                    <div class="user-info">
                        <p class="user-name">Invitado</p>
                        <p class="user-role">Sin Sesión</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Invitado&background=94a3b8&color=ffffff&bold=true"
                        alt="Avatar Invitado"
                        class="user-avatar">
                    @endauth
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
