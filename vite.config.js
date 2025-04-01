import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Permite conexiones externas (necesario para Docker)
        port: 5173,
        strictPort: true, // Evita que Vite cambie el puerto automáticamente
        hmr: {
            host: 'localhost', // Usa el host del contenedor
            protocol: 'ws', // Usa WebSocket para HMR
        }
    }
});