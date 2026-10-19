import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/login.css',
                'resources/css/recuperar.css',
                'resources/css/sidebar_topbar.css',
                'resources/css/dashboard.css',
                'resources/css/crud-components.css',
                'resources/css/productos.css',
                'resources/css/reporte_productos.css',
                'resources/js/app.js',
                'resources/js/sidebar_topbar.js',
                'resources/js/auth/login.js',
                'resources/js/categorias.js',
                'resources/js/marcas.js',
                'resources/js/proveedores.js',
                'resources/js/productos.js',
                'resources/js/reporte_productos.js',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
