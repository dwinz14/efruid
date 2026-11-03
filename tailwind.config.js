import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./app/Enums/**/*.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Inter Variable"', "Inter", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: "#eff6ff",
                    100: "#dbeafe",
                    200: "#bfdbfe",
                    300: "#93c5fd",
                    400: "#60a5fa",
                    500: "#3b82f6",
                    600: "#1d4ed8", // primary
                    700: "#1e40af",
                    800: "#1e3a8a",
                    900: "#1e3056",
                    950: "#0f172a",
                },
                surface: {
                    DEFAULT: "#f8fafc",
                    card: "#ffffff",
                    border: "#e2e8f0",
                },
            },
            boxShadow: {
                card: "0 1px 3px 0 rgb(0 0 0 / 0.07), 0 1px 2px -1px rgb(0 0 0 / 0.07)",
                "card-hover":
                    "0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)",
            },
            borderRadius: {
                card: "0.75rem",
            },
        },
    },

    plugins: [forms, typography],
};