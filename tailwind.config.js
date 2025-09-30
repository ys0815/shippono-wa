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
        "space-y-10",
        "space-y-8",
        "space-y-6",
        // 温かみメッセージ用のクラス
        "bg-gradient-to-r",
        "from-amber-100",
        "to-orange-200",
        "border-amber-300",
        "text-amber-800",
        "text-amber-700",
        "text-amber-600",
        "bg-yellow-50",
        "text-yellow-800",
        "bg-gray-100",
        "hover:bg-gray-200",
        "bg-amber-600",
        "hover:bg-amber-700",
        // ペットアイコン用のクラス
        "absolute",
        "-bottom-4",
        "left-1/2",
        "transform",
        "-translate-x-1/2",
        "z-20",
        "w-24",
        "h-24",
        "rounded-full",
        "border-4",
        "border-white",
        "shadow-xl",
        "pt-16",
        "pt-14",
        "pt-12",
        "pt-8",
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
