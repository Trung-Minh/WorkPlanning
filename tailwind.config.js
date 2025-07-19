/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: "class",
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require("@tailwindcss/forms"),
        plugin(function ({ addComponents }) {
            addComponents({
                ".dark-card": {
                    "@apply bg-white text-dark dark:bg-gray-800 dark:text-white":
                        {},
                },
            });
        }),
    ],
};
