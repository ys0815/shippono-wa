import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    safelist: [
        // 動的に使用されるクラスを明示的に指定
        "border-amber-500",
        "text-amber-600",
        "border-transparent",
        "text-gray-500",
        "hover:text-gray-700",
        "hover:border-gray-300",
        "text-blue-500",
        "text-pink-500",
        "text-gray-500",
        "text-blue-400",
        "text-pink-400",
        "text-gray-400",
        "bg-amber-500",
        "bg-gray-300",
        // その他の動的クラスも必要に応じて追加
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
