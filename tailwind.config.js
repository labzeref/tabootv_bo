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
        // screens: {
        //     'sm' : '600px',
        //     'md' : '960px',
        //     'lg' : '1280px',
        //     'xl' : '1620px',
        //     '2xl': '1940px',
        //   },
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primaryLight: 'var(--primary-light-color)',
                primary: 'var(--primary-color)',
                primaryDark: 'var(--primary-dark-color)',
                secondary: 'var(--secondary-color)',
                accent: 'var(--accent-color)',
                dark: 'var(--dark-color)',
                success: 'var(--success-color)',
                warning: 'var(--warning-color)',
                gray26: 'var(--gray-color-26)',
                purpledo: 'var(--purple-d0)',
            },
        },
    },

    plugins: [forms],
};
