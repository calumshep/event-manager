import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel([
            'resources/js/app.js',
        ], {
            refresh: true,
        }),
    ],
    server: {
        host: '192.168.56.56',
        watch: {
            usePolling: true,
        },
    },
});
