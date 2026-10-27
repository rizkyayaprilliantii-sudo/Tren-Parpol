/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./storage/framework/views/*.php",
    ],

    theme: {
        extend: {
            colors: {
                navy: "#1B2A41",
                cream: "#F4F1E8",
                gold: "#C9A24B",
                "gold-soft": "#E3C878",
                maroon: "#7A2E2E",
                slate: "#5B6472",
                ink: "#1B2A41",
            },

            fontFamily: {
                display: ["Fraunces", "serif"],
                mono: ['"JetBrains Mono"', "monospace"],
                sans: ["Inter", "sans-serif"],
            },
        },
    },

    plugins: [],
};
