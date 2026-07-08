import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import swal from 'sweetalert';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss', // Updated to reference SCSS
                'resources/js/app.js',
                // JTC storefront homepage (design layout — Alpine via Livewire)
                'resources/sass/storefront/storefront.scss',
                'resources/js/storefront/app.js',
            ],
            refresh: true,
        }),
    ],
});
