import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                mono: ['"Space Mono"', '"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                kafe: {
                    sidebar: '#D8B888',
                    'sidebar-hover': '#CEAE7E',
                    orange: '#DE541E',
                    bg: '#FAF9F5',
                    outer: '#EDEAE3',
                    card: '#FFFFFF',
                    border: '#E2DFD7',
                    text: '#1E1B18',
                    subtext: '#524F49',
                    btn: '#A88B5D',
                    'btn-hover': '#967B4E',
                    graybox: '#EAE8E2',
                    logout: '#FF6565',
                }
            }
        },
    },

    plugins: [forms],
};
