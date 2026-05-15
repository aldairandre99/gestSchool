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
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#0062ff',
                    soft: '#e0edff',
                    600: '#0050d4',
                },
                success: {
                    DEFAULT: '#44ce42',
                    soft: '#e3f8e3',
                },
                danger: {
                    DEFAULT: '#fc5a5a',
                    soft: '#feebeb',
                },
                warning: {
                    DEFAULT: '#ffc542',
                    soft: '#fff5d5',
                },
                info: {
                    DEFAULT: '#a461d8',
                    soft: '#f1e4fa',
                },
                accent: '#f2a654',
                navy: '#001737',
                body: '#a7afb7',
                muted: '#76838f',
                sidebar: {
                    DEFAULT: '#181824',
                    hover: '#161621',
                    text: '#bfbfd0',
                },
                'secondary-text': '#8e94a9',
            },
            borderRadius: {
                btn: '3px',
                input: '2px',
            },
            spacing: {
                sidebar: '258px',
                navbar: '64px',
                'sidebar-collapsed': '70px',
            },
            boxShadow: {
                card: '0 1px 3px 0 rgba(0, 23, 55, 0.04)',
                'card-hover': '0 4px 12px 0 rgba(0, 23, 55, 0.08)',
            },
        },
    },

    plugins: [forms],
};
