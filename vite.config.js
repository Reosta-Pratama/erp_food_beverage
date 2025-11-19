import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS files to be processed
                'resources/css/app.css', 
                'resources/assets/icon-fonts/icons.css', 

                // SCSS files to be processed
                'resources/sass/app.scss', 

                // JavaScript files to be processed
                'resources/js/app.js',
                'resources/js/main.js',

                    'resources/assets/js/layouts/header.js',
                    'resources/assets/js/layouts/sidebar.js',

                        'resources/assets/js/erp/activity-log.js', 
                        'resources/assets/js/erp/audit-log.js', 
                        'resources/assets/js/erp/company-profile.js', 
                        'resources/assets/js/erp/create-new-permission.js',
                        'resources/assets/js/erp/create-new-role.js',
                        'resources/assets/js/erp/create-new-user.js',
                        'resources/assets/js/erp/edit-role.js',
                        'resources/assets/js/erp/edit-user.js',

                        'resources/assets/js/custom/alert-custom.js',
                        'resources/assets/js/custom/auto-complete-custom.js',
                        'resources/assets/js/custom/create-password-custom.js',
                        'resources/assets/js/custom/form-color-picker-custom.js',
                        'resources/assets/js/custom/form-date-time-picker-custom.js',
                        'resources/assets/js/custom/form-file-upload-custom.js',
                        'resources/assets/js/custom/form-input-masks-custom.js',
                        'resources/assets/js/custom/form-range-slider-custom.js',
                        'resources/assets/js/custom/form-select-custom.js',
                        'resources/assets/js/custom/modal-close-custom.js',
                        'resources/assets/js/custom/tagify-custom.js',
                        'resources/assets/js/custom/telephone-input-custom.js',
                        'resources/assets/js/custom/toast-custom.js',
                        'resources/assets/js/custom/sweet-alert-custom.js',
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
