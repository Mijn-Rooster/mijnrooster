/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./src/**/*.{html,js,svelte,ts}",
    "./node_modules/flowbite-svelte/**/*.{html,js,svelte,ts}",
  ],
  theme: {
    fontFamily: {
      sans: ["Overpass", "sans-serif"],
    },
    extend: {
      colors: {
        primary: {
          DEFAULT: "#291C5B",
          50: "#AD9FE1",
          100: "#9F8FDD",
          200: "#8470D3",
          300: "#6A51C9",
          400: "#5339B9",
          500: "#452F99",
          600: "#37267A",
          700: "#291C5B",
          800: "#22174B",
          900: "#1B123C",
          950: "#171034",
        },
        secondary: {
          DEFAULT: "#AEE5B6",
          50: "#F9FDFA",
          100: "#F3FBF5",
          200: "#E8F8EA",
          300: "#DCF4E0",
          400: "#D1F0D5",
          500: "#C5ECCB",
          600: "#BAE9C0",
          700: "#AEE5B6",
          800: "#8FDB9A",
          900: "#70D17E",
          950: "#61CC70",
        },
        warning: {
          DEFAULT: "#FF9895",
          50: "#FFD8D7",
          100: "#FFD3D2",
          200: "#FFCAC8",
          300: "#FFC0BE",
          400: "#FFB6B4",
          500: "#FFACA9",
          600: "#FFA29F",
          700: "#FF9895",
          800: "#FF7A76",
          900: "#FF5D58",
          950: "#FF4E49",
        },
      },
    },
  },
  plugins: [require("@tailwindcss/typography"), require("flowbite/plugin")],
};
