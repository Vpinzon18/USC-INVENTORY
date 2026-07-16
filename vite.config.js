import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({       
    server: {
        host: '0.0.0.0', // Esto permite que escuche en todas las interfaces de red
        hmr: {
             host: '10.16.2.97', //host: '172.18.20.85', // Aquí pones tu IP fija para el Live Reload
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});