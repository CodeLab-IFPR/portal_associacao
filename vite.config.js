import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/styleLogin.css',
                'resources/css/libs.bundle.css',
                // 'resources/css/libs.bundle.css.map',
                'resources/css/theme.bundle.css',
                'resources/css/adminlte.css',
                // 'resources/css/theme.bundle.css.map',
                'resources/css/datatables.min.css',
                'resources/js/app.js',
                'resources/js/theme.bundle.js',
                'resources/js/vendor.bundle.js',
                'resources/js/datatables.min.js',
                'resources/js/adminlte.js',
                'resources/js/menu.js',
                // 'resources/fonts*',
            ],
            refresh: true,
        }),
    ],
});
