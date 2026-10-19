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
                    <i data-lucide="layout-dashboard" class="sidebar-icon"></i>
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
                    <i id="inventario-arrow" data-lucide="chevron-down" class="sidebar-arrow {{ request()->is('productos', 'categorias', 'marcas', 'ventas', 'clientes', 'proveedores') ? 'rotated' : '' }}"></i>
                </button>

                <div id="inventario-submenu" class="sidebar-submenu {{ request()->is('productos', 'categorias', 'marcas', 'ventas', 'clientes', 'proveedores') ? 'open' : '' }}">
                    <a href="/productos" class="submenu-item {{ request()->is('productos') ? 'active' : '' }}">
                        <i data-lucide="shopping-bag" class="submenu-icon"></i> Productos
                    </a>
                    <a href="/categorias" class="submenu-item {{ request()->is('categorias') ? 'active' : '' }}">
                        <i data-lucide="tags" class="submenu-icon"></i> Categorías
                    </a>
                    <a href="/marcas" class="submenu-item {{ request()->is('marcas') ? 'active' : '' }}">
                        <i data-lucide="badge-check" class="submenu-icon"></i> Marcas
                    </a>
                    <a href="/ventas" class="submenu-item {{ request()->is('ventas') ? 'active' : '' }}">
                        <i data-lucide="shopping-cart" class="submenu-icon"></i> Ventas
                    </a>
                    <a href="/clientes" class="submenu-item {{ request()->is('clientes') ? 'active' : '' }}">
                        <i data-lucide="user-group" class="submenu-icon"></i> Clientes
                    </a>
                    <a href="/proveedores" class="submenu-item {{ request()->is('proveedores') ? 'active' : '' }}">
                        <i data-lucide="truck" class="submenu-icon"></i> Proveedores
                    </a>
                </div>
            </div>

            <hr class="sidebar-divider">

            <!-- Empleados -->
            <a href="/users" class="sidebar-item {{ request()->is('users') ? 'active' : '' }}">
                <i data-lucide="id-card-lanyard" class="sidebar-icon"></i>
                <span>Empleados</span>
            </a>

            <!-- Asistencias -->
            <a href="/asistencias" class="sidebar-item {{ request()->is('asistencias') ? 'active' : '' }}">
                <i data-lucide="user-check" class="sidebar-icon"></i>
                <span>Asistencias</span>
            </a>

            <!-- General (desplegable) -->
            <div class="sidebar-group">
                <button onclick="toggleMenu('general')" class="sidebar-item sidebar-group-btn">
                    <div class="sidebar-item-content">
                        <span>General</span>
                    </div>
                    <i id="general-arrow" data-lucide="chevron-down" class="sidebar-arrow {{ request()->is('reportes/*') ? 'rotated' : '' }}"></i>
                </button>

                <div id="general-submenu" class="sidebar-submenu {{ request()->is('reportes/*') ? 'open' : '' }}">
                    <a href="/reportes/productos" class="submenu-item {{ request()->is('reportes/productos') ? 'active' : '' }}">
                        <i data-lucide="file-chart-column" class="submenu-icon"></i> Reportes Productos
                    </a>
                    <a href="/reportes/asistencias" class="submenu-item {{ request()->is('reportes/asistencias') ? 'active' : '' }}">
                        <i data-lucide="file-user" class="submenu-icon"></i> Reportes Asistencias
                    </a>
                    <a href="/reportes/ventas" class="submenu-item {{ request()->is('reportes/ventas') ? 'active' : '' }}">
                        <i data-lucide="file-spreadsheet" class="submenu-icon"></i> Reportes Ventas
                    </a>
                </div>
            </div>
        </nav>

        <!-- Parte Inferior Fija (Configuración + Cerrar Sesión) -->
        <div class="sidebar-footer">
            <a href="#" class="sidebar-item">
                <i data-lucide="settings" class="sidebar-icon"></i>
                <span>Configuración</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="sidebar-item logout-btn">
                    <i data-lucide="log-out" class="sidebar-icon"></i>
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
                    <i data-lucide="menu"></i>
                </button>
                <h1 class="topbar-title">Panel Administrativo</h1>
            </div>

            <div class="topbar-right">
                <!-- Notificación (Campana limpia sin badge) -->
                <button class="topbar-notification-btn" aria-label="Notificaciones">
                    <i data-lucide="bell" class="topbar-icon"></i>
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

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('scripts')
</body>

</html>