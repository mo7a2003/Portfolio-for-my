/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        obsidian: {
          950: '#030712',
          900: '#060a16',
          850: '#0a0f1d',
          800: '#0f172a',
          700: '#1e293b',
        },
        cyber: {
          blue: '#00f0ff',
          deep: '#0b192c',
          glow: '#0ea5e9',
          dark: '#030b15',
        },
        gold: {
          100: '#fef3c7',
          200: '#fde68a',
          300: '#fcd34d',
          400: '#fbbf24',
          500: '#d4af37',
          600: '#b89228',
          700: '#8c6b16',
          glow: '#f59e0b',
        }
      },
      fontFamily: {
        sans: ['Space Grotesk', 'Inter', 'sans-serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
      boxShadow: {
        'gold-glow': '0 0 25px rgba(212, 175, 55, 0.35)',
        'gold-glow-lg': '0 0 45px rgba(212, 175, 55, 0.55)',
        'blue-glow': '0 0 25px rgba(0, 240, 255, 0.35)',
        'blue-glow-lg': '0 0 45px rgba(0, 240, 255, 0.55)',
        'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)',
        'glass-gold': '0 8px 32px 0 rgba(212, 175, 55, 0.15)',
      },
      backgroundImage: {
        'gold-gradient': 'linear-gradient(135deg, #FFF0B3 0%, #D4AF37 50%, #996515 100%)',
        'gold-text-grad': 'linear-gradient(180deg, #FFFFFF 0%, #F5D77F 40%, #D4AF37 75%, #A87920 100%)',
        'cyber-gradient': 'linear-gradient(135deg, #00f0ff 0%, #0070f3 100%)',
      },
      animation: {
        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'float': 'float 6s ease-in-out infinite',
        'spin-slow': 'spin 20s linear infinite',
        'reverse-spin': 'reverse-spin 25s linear infinite',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-12px)' },
        },
        'reverse-spin': {
          from: { transform: 'rotate(360deg)' },
          to: { transform: 'rotate(0deg)' },
        }
      }
    },
  },
  plugins: [],
}

