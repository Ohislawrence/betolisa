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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f5f3f0',
                    100: '#e9e1d5',
                    200: '#c9b792',
                    300: '#a68f55',
                    400: '#8c712d',
                    500: '#6f5a18',
                    600: '#4f3f0e',
                    700: '#34290b',
                    800: '#1f1906',
                    900: '#0d0b03',
                },
                accent: {
                    50: '#fff7dd',
                    100: '#ffedb5',
                    200: '#ffdc7b',
                    300: '#ffcb40',
                    400: '#f7b200',
                    500: '#d49f00',
                    600: '#a77a00',
                    700: '#795600',
                    800: '#4f3800',
                    900: '#2c2200',
                },
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.5s ease-out',
                'slide-down': 'slideDown 0.3s ease-out',
                'bounce-slow': 'bounce 3s infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
        },
    },

    plugins: [forms],
};
