import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5174,
        hmr: {
            host: "api.localhost",
        },
    },
    plugins: [
        laravel({
            input: [
                // 'resources/css/app.css',
                'resources/sass/app.sass',
                'resources/js/jquery-3.7.1.js',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});