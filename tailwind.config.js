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
                    900: "#172554", // biru dongker (identitas: sidebar, aksi gelap)
                    950: "#0b1530", // dongker terdalam (hover/tekan, panel brand auth)
                },
                surface: {
                    DEFAULT: "#eef2f7", // kanvas halaman
                    card: "#ffffff",
                    border: "#e2e8f0",
                },
            },
            boxShadow: {
                card: "0 1px 2px 0 rgb(15 23 42 / 0.04), 0 6px 20px -6px rgb(15 23 42 / 0.08)",
                "card-hover":
                    "0 2px 4px 0 rgb(15 23 42 / 0.05), 0 12px 28px -8px rgb(15 23 42 / 0.14)",
                // Panel melayang: sidebar & kartu hero (bayangan bernuansa dongker)
                float: "0 2px 4px 0 rgb(15 23 42 / 0.06), 0 16px 40px -12px rgb(11 21 48 / 0.28)",
            },
            borderRadius: {
                card: "1.25rem",
            },
        },
    },

    plugins: [forms, typography],
};