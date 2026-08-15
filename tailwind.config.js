import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                'blob':  'blob 7s infinite',
                'float': 'float 6s ease-in-out infinite',
                'fade-in-down': 'fade-in-down 0.8s ease-out forwards',
            },
            keyframes: {
                blob: {
                    '0%':   { transform: 'translate(0px, 0px) scale(1)' },
                    '33%':  { transform: 'translate(30px, -50px) scale(1.1)' },
                    '66%':  { transform: 'translate(-20px, 20px) scale(0.9)' },
                    '100%': { transform: 'translate(0px, 0px) scale(1)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%':      { transform: 'translateY(-20px)' },
                },
                'fade-in-down': {
                    '0%':   { opacity: '0', transform: 'translateY(-10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            typography: (theme) => ({
                DEFAULT: {
                    css: {
                        fontFamily: "'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif",
                        color: theme('colors.slate.700'),
                        lineHeight: '1.8',
                        'h1,h2,h3,h4,h5,h6': {
                            fontWeight: '900',
                            color: theme('colors.slate.800'),
                            letterSpacing: '-0.02em',
                        },
                        a: { color: theme('colors.indigo.600'), fontWeight: '700' },
                        strong: { fontWeight: '800', color: theme('colors.slate.800') },
                        blockquote: {
                            borderLeftColor: theme('colors.indigo.400'),
                            backgroundColor: theme('colors.indigo.50'),
                            borderRadius: '0.5rem',
                            padding: '0.5rem 1rem',
                            fontStyle: 'normal',
                        },
                        'ul > li::marker': { color: theme('colors.indigo.500') },
                        'ol > li::marker': { color: theme('colors.indigo.500'), fontWeight: '700' },
                        code: {
                            backgroundColor: theme('colors.slate.100'),
                            padding: '0.15rem 0.4rem',
                            borderRadius: '0.25rem',
                            fontWeight: '600',
                            color: theme('colors.indigo.700'),
                        },
                        'code::before': { content: '""' },
                        'code::after': { content: '""' },
                        table: { fontSize: '0.875rem' },
                        'thead th': { fontWeight: '800', color: theme('colors.slate.700') },
                        img: { borderRadius: '0.75rem', boxShadow: theme('boxShadow.md') },
                    },
                },
            }),
        },
    },

    plugins: [forms, typography],
};
