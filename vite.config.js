import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  // server: {
  //   host: '0.0.0.0', // Pour écouter sur toutes les interfaces réseau
  //   port: 3000,      // Vous pouvez définir le port que vous voulez
  // },
});
