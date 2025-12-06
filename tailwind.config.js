/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/**/*.php",
        "./resources/**/*.ts",
        "./resources/**/*.volt.php",
        "./vendor/livewire/**/*.blade.php",
        "./vendor/livewire/**/*.php",
    ],
    darkMode: 'class',
    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: '1rem',
                sm: '1.5rem',
                lg: '2rem',
                xl: '3rem',
                '2xl': '4rem',
            },
            screens: {
                sm: '640px',
                md: '768px',
                lg: '1024px',
                xl: '1150px',
                '2xl': '1320px',
            },
        },
        extend: {
            fontFamily: {
                sans: ['"Hind Siliguri"', 'system-ui', 'sans-serif'],
            },
            colors: {
                primary: {
                    DEFAULT: '#0d6efd',   // Main Blue
                    dark: '#0b5ed7',
                    light: '#e3f2fd'
                },
                secondary: {
                    DEFAULT: '#1e293b', // Dark slate text
                    light: '#475569'
                },
                muted: {
                    DEFAULT: '#6b7280', // Muted paragraph / meta info
                    light: '#9ca3af'
                },
                soft: {
                    DEFAULT: '#e2e8f0', // Light borders, cards
                    dark: '#cbd5e1'
                },
                accent: {
                    DEFAULT: '#dc2626',  // Red – Breaking news, Alerts
                    light: '#fecaca'
                },
                darkbg: {
                    DEFAULT: '#0f172a', // Dark mode background
                    soft: '#1e293b'
                }
            }
        }
    },
    plugins: [],
}
