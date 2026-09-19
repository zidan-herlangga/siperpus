import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        forest: {
          DEFAULT: "#006739",
          50: "#eaf3ed",
          100: "#d2e6da",
          200: "#a6cdb7",
          300: "#74ae91",
          400: "#418f69",
          500: "#1d7a52",
          600: "#006739",
          700: "#00502d",
          800: "#004225",
          900: "#00341d",
        },
        emerald: {
          50: "#eaf3ed",
          100: "#d2e6da",
          200: "#a6cdb7",
          300: "#74ae91",
          400: "#418f69",
          500: "#1d7a52",
          600: "#006739",
          700: "#00502d",
          800: "#004225",
          900: "#00341d",
        },
        cta: {
          DEFAULT: "#ffc600",
          dark: "#e0b400",
        },
      },
    },
  },
  plugins: [],
}