import { ref, computed } from 'vue'

const getInitialTheme = () => {
  if (typeof window === 'undefined') return 'eclipse'
  return localStorage.getItem('theme') || 'eclipse'
}

export const currentTheme = ref(getInitialTheme())
export const isCyanTheme = computed(() => currentTheme.value === 'cyan')

export function applyTheme(theme) {
  currentTheme.value = theme
  if (typeof window !== 'undefined') {
    localStorage.setItem('theme', theme)
    if (theme === 'cyan') {
      document.documentElement.classList.add('theme-cyan')
    } else {
      document.documentElement.classList.remove('theme-cyan')
    }
    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme } }))
  }
}

export function toggleTheme() {
  const next = currentTheme.value === 'eclipse' ? 'cyan' : 'eclipse'
  applyTheme(next)
  return next
}

export function initTheme() {
  if (typeof window === 'undefined') return
  const saved = getInitialTheme()
  applyTheme(saved)
}
