/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50:  '#f0f4ff',
                    100: '#dde8ff',
                    200: '#c3d4fe',
                    300: '#9ab8fd',
                    400: '#6990f9',
                    500: '#4466f3',
                    600: '#2d47e7',
                    700: '#2538d4',
                    800: '#2430aa',
                    900: '#232e86',
                    950: '#181d52',
                },
                surface: {
                    DEFAULT: '#0f0f1a',
                    50:  '#fafafa',
                    100: '#f4f4f8',
                    200: '#e8e8f0',
                    800: '#1a1a2e',
                    900: '#0f0f1a',
                    950: '#080810',
                },
                accent: {
                    cyan:   '#00d4ff',
                    purple: '#8b5cf6',
                    pink:   '#ec4899',
                    gold:   '#f59e0b',
                },
            },
            fontFamily: {
                display: ['"Clash Display"', '"Syne"', 'sans-serif'],
                body:    ['"DM Sans"', 'system-ui', 'sans-serif'],
                mono:    ['"JetBrains Mono"', 'monospace'],
            },
            animation: {
                'fade-up':       'fadeUp 0.6s ease-out forwards',
                'fade-in':       'fadeIn 0.4s ease-out forwards',
                'slide-right':   'slideRight 0.5s ease-out forwards',
                'float':         'float 6s ease-in-out infinite',
                'pulse-slow':    'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'spin-slow':     'spin 8s linear infinite',
                'gradient':      'gradient 8s ease infinite',
                'typing-cursor': 'blink 1s step-end infinite',
            },
            keyframes: {
                fadeUp: {
                    '0%':   { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%':   { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideRight: {
                    '0%':   { opacity: '0', transform: 'translateX(-30px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%':      { transform: 'translateY(-20px)' },
                },
                gradient: {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%':      { backgroundPosition: '100% 50%' },
                },
                blink: {
                    '0%, 100%': { opacity: '1' },
                    '50%':      { opacity: '0' },
                },
            },
            backgroundImage: {
                'gradient-radial':  'radial-gradient(var(--tw-gradient-stops))',
                'gradient-mesh':    'radial-gradient(ellipse at top, #4466f320 0%, transparent 60%), radial-gradient(ellipse at bottom right, #8b5cf620 0%, transparent 60%)',
                'grid-pattern':     "url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234466f3' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")",
            },
            boxShadow: {
                'glow':        '0 0 20px rgba(68, 102, 243, 0.3)',
                'glow-lg':     '0 0 40px rgba(68, 102, 243, 0.4)',
                'glow-purple': '0 0 20px rgba(139, 92, 246, 0.3)',
                'card':        '0 4px 24px rgba(0, 0, 0, 0.1)',
                'card-hover':  '0 8px 40px rgba(0, 0, 0, 0.2)',
                'glass':       '0 8px 32px rgba(0, 0, 0, 0.12)',
            },
            backdropBlur: {
                xs: '2px',
            },
            screens: {
                'xs':   '380px',
                '3xl':  '1920px',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
