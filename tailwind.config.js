/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.{php,html,js}",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#6200ff',
        'primary-light': '#f0ebff',
        'primary-dark': '#4a00cc',
      }
    },
  },
  plugins: [],
}