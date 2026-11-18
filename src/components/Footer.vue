<template>
  <footer class="relative py-12 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
      
      <!-- Left: Copyright & Discreet Hidden-in-Plain-Sight Admin Access -->
      <div class="text-xs text-slate-400 font-mono text-center md:text-left flex items-center justify-center md:justify-start gap-1.5 flex-wrap">
        <span>© 2026 Mohammed Alhwaiti. All rights reserved</span>
        
        <!-- Discreet Secret Link to Admin Portal -->
        <router-link
          to="/admin/login"
          class="text-gray-800 hover:text-cyan-400/80 transition-all duration-300 select-none text-[11px] opacity-30 hover:opacity-100 px-1 py-0.5 rounded hover:bg-white/5 cursor-pointer inline-flex items-center gap-1 group"
          title="System Access Portal"
          aria-label="System Console"
        >
          <span class="text-gray-800 group-hover:text-cyan-400 transition-colors">sys://</span>
        </router-link>
      </div>

      <!-- Center: Footer Nav Links -->
      <nav class="flex flex-wrap items-center justify-center gap-6 text-xs text-slate-400 font-medium">
        <a href="#hero" class="hover:text-gold-300 transition-colors">Home</a>
        <a href="#about" class="hover:text-gold-300 transition-colors">About</a>
        <a href="#skills" class="hover:text-gold-300 transition-colors">Skills</a>
        <a href="#projects" class="hover:text-gold-300 transition-colors">Projects</a>
        <a href="#certificates" class="hover:text-gold-300 transition-colors">Certificates</a>
        <a href="#contact" class="hover:text-gold-300 transition-colors">Contact</a>
      </nav>

      <!-- Right: Social Links & Back to Top -->
      <div class="flex items-center gap-4">
        <!-- GitHub -->
        <a
          v-if="resolveSocialUrl('github')"
          :href="resolveSocialUrl('github')"
          target="_blank"
          rel="noopener noreferrer"
          class="w-9 h-9 rounded-full bg-white/5 hover:bg-gold-500/20 border border-white/10 hover:border-gold-500/40 text-slate-300 hover:text-gold-300 flex items-center justify-center transition-all"
          aria-label="GitHub Profile"
        >
          <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
            <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
          </svg>
        </a>

        <!-- LinkedIn -->
        <a
          v-if="resolveSocialUrl('linkedin')"
          :href="resolveSocialUrl('linkedin')"
          target="_blank"
          rel="noopener noreferrer"
          class="w-9 h-9 rounded-full bg-white/5 hover:bg-gold-500/20 border border-white/10 hover:border-gold-500/40 text-slate-300 hover:text-gold-300 flex items-center justify-center transition-all"
          aria-label="LinkedIn Profile"
        >
          <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
          </svg>
        </a>

        <!-- Back to Top Button -->
        <button
          @click="scrollToTop"
          class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/5 hover:bg-gold-500/20 border border-white/10 hover:border-gold-500/40 text-xs font-mono text-slate-300 hover:text-gold-300 transition-all cursor-pointer"
        >
          <span>Back to Top</span>
          <svg class="w-3.5 h-3.5 text-gold-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
          </svg>
        </button>
      </div>

    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue'
import { personalInfo } from '@/data/portfolioData'

const props = defineProps({
  profile: {
    type: Object,
    default: () => ({})
  },
  socialLinks: {
    type: Array,
    default: () => []
  }
})

const currentProfile = computed(() => {
  return Object.keys(props.profile || {}).length ? { ...personalInfo, ...props.profile } : personalInfo
})

const resolveSocialUrl = (platformKey) => {
  const key = (platformKey || '').toLowerCase()
  if (Array.isArray(props.socialLinks) && props.socialLinks.length > 0) {
    const match = props.socialLinks.find(item => {
      if (item.is_active === false || item.is_active === 0) return false
      const p = (item.platform || item.icon || item.name || '').toLowerCase()
      return p.includes(key)
    })
    if (match && match.url && match.url.trim() && match.url !== '#' && !match.url.includes('example.com')) {
      return match.url.trim()
    }
  }
  const profVal = currentProfile.value[key]
  if (profVal && typeof profVal === 'string' && profVal.trim() && profVal !== '#' && !profVal.includes('example.com')) {
    return profVal.trim()
  }
  return ''
}

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>
