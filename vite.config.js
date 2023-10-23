import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import babel from '@rollup/plugin-babel'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts', 'resources/scss/app.scss'],
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
    ],
});
