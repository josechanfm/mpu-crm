import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
// import basicSsl from '@vitejs/plugin-basic-ssl'
import i18n from 'laravel-vue-i18n/vite'; 

export default defineConfig({
    server: {
        // https: true,
        host: '0.0.0.0',
        hmr: {
            host: 'localhost'
        },
    },
    plugins: [
        // basicSsl(),
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        i18n()
    ],
    optimizeDeps: {
        include: ["quill"]
    },
    resolve: {
        alias: {
            
        }
    }

});
