const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  purge: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Nunito', ...defaultTheme.fontFamily.sans],
      },
    },
    boxShadow: {
      DEFAULT: '0 0 5px 0 rgba(0, 0, 0, 0.08)',
    },
    colors: {
        grey: 'rgba(0, 0, 0, 0.4)',
        'grey-light': '#F5F6F9',
        white: '#FFF'
    }
  },

  variants: {
    extend: {
      opacity: ['disabled'],
    },
  },

  plugins: [require('@tailwindcss/forms')],
};
