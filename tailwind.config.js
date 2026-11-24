import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Enums/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: [
                    '-apple-system',
                    'BlinkMacSystemFont',
                    '"SF Pro Text"',
                    '"SF Pro Display"',
                    '"Segoe UI"',
                    ...defaultTheme.fontFamily.sans,
                ],
            },
            colors: {
                apple: {
                    canvas: '#F5F5F7',
                    surface: '#FFFFFF',
                    ink: '#1D1D1F',
                    muted: '#6E6E73',
                    blue: '#0071E3',
                    'blue-hover': '#0077ED',
                    'blue-active': '#0066CC',
                },
            },
            transitionTimingFunction: {
                apple: 'cubic-bezier(0.23, 1, 0.32, 1)',
                sheet: 'cubic-bezier(0.32, 0.72, 0, 1)',
            },
        },
    },

    plugins: [forms],
};
