/** @type {import('tailwindcss').Config} */
export default {
  content: [
       "./resources/**/*.blade.php",
       "./resources/**/*.js",
  ],
  theme: {
      extend: {},
  },
  plugins: [
      require('postcss-import'),
      require('tailwindcss/nesting'),
      require('autoprefixer'),
      require('tailwindcss'),
  ],
};
