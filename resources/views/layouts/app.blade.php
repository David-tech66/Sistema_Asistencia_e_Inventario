<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Inventario')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Aplica el tema guardado antes de renderizar (evita parpadeo)
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <style>
        @keyframes pageIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        body > * { animation: pageIn .4s ease both; }
        body.page-exit > * { opacity: 0; transform: translateY(-8px); transition: all .25s ease; }
    </style>
</head>
<body class="bg-white dark:bg-[#16181d] text-gray-900 dark:text-white transition-colors duration-300 min-h-screen">
    @yield('content')

    <script>
        // Botón para cambiar entre modo claro y oscuro (sincroniza el icono)
        function syncThemeIcons() {
            const dark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('[data-theme-icon]').forEach(i => {
                i.classList.toggle('fa-moon', !dark);
                i.classList.toggle('fa-sun', dark);
            });
        }
        syncThemeIcons();

        document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const dark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', dark ? 'dark' : 'light');
                syncThemeIcons();
            });
        });

        // Transición suave al navegar entre vistas
        document.querySelectorAll('[data-transition]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                document.body.classList.add('page-exit');
                setTimeout(() => { window.location.href = link.getAttribute('href'); }, 250);
            });
        });

        // Mostrar/ocultar contraseña
        document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const input = btn.parentElement.querySelector('input');
                input.type = input.type === 'password' ? 'text' : 'password';
                const icon = btn.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>
