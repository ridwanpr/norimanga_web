import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/nori.css',
                'resources/js/reader.js',
                'resources/css/select2.css',
                'resources/js/bookmark.js',
                'resources/css/reader.css',
                'resources/css/welcome.css',
                'resources/css/manga-show.css'
            ],
            refresh: true,
        }),
    ],
});
