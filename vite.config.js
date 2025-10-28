import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS files to be processed
                'resources/css/app.css', 

                // SCSS files to be processed
                'resources/sass/app.scss', 

                // JavaScript files to be processed
                'resources/js/app.js',
                'resources/js/main.js',

                    'resources/assets/js/layouts/header.js',
                    'resources/assets/js/layouts/sidebar.js',

                        'resources/assets/js/custom/alert-custom.js',
                        'resources/assets/js/custom/toast-custom.js',
            ],
            refresh: true,
        }),
        // tailwindcss(),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true,
            },
        },
    },
    build: {
        rollupOptions: {
            output: {
                entryFileNames: 'js/[name].[hash].js',
                chunkFileNames: 'js/[name].[hash].js',
                assetFileNames: assetInfo => {
                    let ext = assetInfo.name.split('.').pop();

                    if (ext === 'css') {
                        return 'css/[name].[hash].[ext]';
                    }

                    if (['woff', 'woff2', 'ttf', 'otf', 'eot'].includes(ext)) {
                        return 'fonts/[name].[hash].[ext]';
                    }

                    if (['png', 'jpg', 'jpeg', 'gif', 'svg'].includes(ext)) {
                        return 'images/[name].[hash].[ext]';
                    }

                    return 'assets/[name].[hash].[ext]';
                }
            }
        }
    }
});
