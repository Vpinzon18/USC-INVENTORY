import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({       
    server: {
        host: '0.0.0.0', // Esto permite que escuche en todas las interfaces de red
        hmr: {
             host: '172.16.136.62', //host: '192.168.18.40', // Aquí pones tu IP fija para el Live Reload
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});