import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vuePlugin from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vuePlugin(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('@fortawesome') || id.includes('fontawesome')) {
                            return 'fontawesome';
                        }
                        if (id.includes('vue') || id.includes('@inertiajs')) {
                            return 'vue-vendor';
                        }
                        if (id.includes('html2pdf.js')) {
                            return 'html2pdf';
                        }
                        if (id.includes('quill')) {
                            return 'quill';
                        }
                        if (id.includes('aos')) {
                            return 'aos';
                        }
                        return 'vendor';
                    }
                },
            },
        },
    },
});
