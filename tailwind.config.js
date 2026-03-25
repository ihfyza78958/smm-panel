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
        'min-w-[760px]',
        'min-w-[980px]',
        'min-w-[1180px]',
        'max-w-[130px]',
        'max-w-[140px]',
        'max-w-[180px]',
        'max-w-[260px]',
        'max-w-[280px]',
        'max-w-[14rem]',
        'max-w-[20rem]',
        'max-w-[30rem]',
        'min-w-[140px]',
        'min-w-[180px]',
        'min-w-[260px]',
        'min-w-[280px]',
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
