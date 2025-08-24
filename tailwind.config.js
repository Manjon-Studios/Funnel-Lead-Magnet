/** @type {import('tailwindcss').Config} */
module.exports = {
  important: true,
  content: [
    './*.php',                // 👈 todos los php de la raíz del theme
    './**/*.php',                // 👈 todos los php de la raíz del theme
    './inc/**/*.php',         // php dentro de inc/
    './templates/**/*.php',   // php dentro de templates/
    './resources/**/*.php',   // por si usas views ahí
    './assets/src/js/**/*.{js,jsx,ts,tsx}',  // scripts fuente
    './assets/src/css/**/*.{css,scss}',      // estilos fuente
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

