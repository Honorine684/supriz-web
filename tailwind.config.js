/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php', // Templates Blade de Laravel
    './resources/**/*.js',        // Fichiers JavaScript
    './resources/**/*.vue',       // Composants Vue
  ],
  theme: {
    extend: {
      // Personnalisation peut être ajoutée ici
    },
  },
  plugins: [
    require('flowbite/plugin')({
      charts: true, // Active les composants graphiques de Flowbite si nécessaire
    }),
  ],
}
