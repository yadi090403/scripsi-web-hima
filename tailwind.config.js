/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            slide: {
                '0%': { transform: 'translateX(0%)' },
                '100%': { transform: 'translateX(-200%)' },
            },
            animation: {
            slide: 'slide 30s linear infinite',
        },

            colors:{
                'hima-blue': '#0D47A1',
            },
        },
    },
    plugins: [],
}
