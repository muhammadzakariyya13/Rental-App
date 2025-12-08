import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        // Background colors
        'bg-blue-100',
        'bg-green-100',
        'bg-orange-100',
        'bg-yellow-100',
        'bg-red-100',
        'bg-emerald-100',
        // Text colors
        'text-blue-600',
        'text-green-600',
        'text-green-800',
        'text-orange-600',
        'text-yellow-800',
        'text-red-800',
        'text-emerald-600',
        // Border colors
        'border-blue-200',
        'border-green-200',
        'border-orange-200',
        'border-yellow-200',
        'border-red-200',
        // SVG and icon sizes
        'w-3',
        'h-3',
        'w-6',
        'h-6',
        'w-8',
        'h-8',
        'w-12',
        'h-12',
        'w-16',
        'h-16',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
