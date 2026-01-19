/** @type {import('tailwindcss').Config} */
export default {
  presets: [
        require('./vendor/wireui/wireui/tailwind.config.js')
    ],
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './app/Livewire/**/*.php',
    './vendor/power-components/livewire-powergrid/resources/views/**/*.blade.php',
    './vendor/wireui/wireui/resources/views/**/*.blade.php',
    './vendor/wireui/wireui/resources/**/*.blade.php',
    './vendor/wireui/wireui/ts/**/*.ts',
    './vendor/wireui/wireui/src/View/**/*.php'
  ],
  darkMode: 'class',
  theme: {
    extend: {},
  },
  plugins: [],
};
