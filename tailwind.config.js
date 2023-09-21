import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'greenyslate': '#98A9B0',
                'tealgray': '#E4F0F0',
                'tealblack': '#4C7071',
                'grass': '#C3DEC0',
                'grassgray': '#E6EFE5',
                'grassyellow': '#E0E0CA',
                'purplesmoke': '#C5C5DB',
                'purplegray': '#E4E4F0',
            },
        },
    },

    plugins: [forms],
};

// #98A9B0
