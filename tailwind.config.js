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
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                helvetica: ['"Helvetica Neue"', 'Helvetica', 'Arial', 'sans-serif'],
            },
            transitionTimingFunction: {
                'out-ui':    'cubic-bezier(0.23, 1, 0.32, 1)',
                'in-out-ui': 'cubic-bezier(0.77, 0, 0.175, 1)',
                'drawer':    'cubic-bezier(0.32, 0.72, 0, 1)',
            },
            perspective: {
                '500':  '500px',
                '800':  '800px',
                '1000': '1000px',
                '1200': '1200px',
            },
            rotate: {
                'x-2': 'rotateX(2deg)',
                'x-6': 'rotateX(6deg)',
                'y-2': 'rotateY(2deg)',
                'y-6': 'rotateY(6deg)',
            },
        },
    },

    plugins: [
        forms,
        // Custom plugin for perspective & 3D rotate utilities
        function ({ matchUtilities, theme }) {
            matchUtilities(
                {
                    'perspective': (value) => ({
                        perspective: value,
                    }),
                },
                { values: theme('perspective') }
            );
        },
    ],
};
