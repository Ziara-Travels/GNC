import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';   // if using Laravel
import tailwindcss from 'tailwindcss';

export default defineConfig({
  plugins: [
    laravel({ input: ['resources/js/app.js', 'resources/css/app.css'] }),
    tailwindcss()
  ],
});
