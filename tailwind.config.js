const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
   content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],

   theme: {
    extend: {
      colors: {
        // Light and neutral colors
        'light-bg': '#f8fafc',
        'light-border': '#e2e8f0',
        'light-hover': '#f1f5f9',
        'light-lighter': '#f1f5f9',
        'light-lightest': '#fafafa',
        
        // Dark colors
        'dark-bg': '#1e293b',
        'sidebar-bg': '#0f172a',
        'text-muted': '#64748b',
        'text-darker': '#475569',
        
        // Brand colors
        'primary': '#2563eb',
        'primary-light': '#3b82f6',
        'primary-lighter': '#60a5fa',
        'primary-dark': '#1d4ed8',
        'secondary': '#e3a300',
        'accent': '#3a7bd5',
        
        // Status colors
        'success': '#10b981',
        'success-light': '#34d399',
        'success-dark': '#059669',
        'warning': '#f59e0b',
        'warning-light': '#fbbf24',
        'warning-dark': '#d97706',
        'danger': '#ef4444',
        'danger-dark': '#dc2626',
        'info': '#06b6d4',
        'info-dark': '#0891b2',
        
        // Role colors
        'user': '#059669',
        'admin': '#dc2626',
        'superadmin': '#7c3aed',
        
        // Other UI colors
        'indigo': {
          DEFAULT: '#6366f1',
          light: '#818cf8',
          dark: '#4f46e5',
        },
        'purple': {
          DEFAULT: '#8b5cf6',
          dark: '#7c3aed',
        },
        'pink': {
          DEFAULT: '#ec4899',
          dark: '#be185d',
        },
        'cyan': {
          DEFAULT: '#06b6d4',
          dark: '#0891b2',
        },
        
        // Gray scale
        'slate': {
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
        },
      },
      boxShadow: {
        'sidebar': '4px 0 20px rgba(0, 0, 0, 0.1)',
        'card': '0 10px 30px rgba(0, 0, 0, 0.1)',
        'card-hover': '0 20px 50px rgba(0, 0, 0, 0.15)',
        'btn': '0 4px 15px rgba(37, 99, 235, 0.3)',
        'dropdown': '0 10px 40px rgba(0, 0, 0, 0.1)',
      },
      borderRadius: {
        'xl': '12px',
        '2xl': '20px',
      }
    },
  },

    plugins: [require('@tailwindcss/forms'), require('tailwind-scrollbar')({ nocompatible: true })],

};
