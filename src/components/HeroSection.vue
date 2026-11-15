<template>
  <section 
    id="hero" 
    class="relative min-h-screen pt-28 pb-16 lg:pt-32 lg:pb-20 flex flex-col justify-between overflow-hidden"
  >
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full z-10 flex-1 flex flex-col justify-center">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">
        
        <!-- Left Hero Column: Status Pill, Monumental Typography, Magnetic CTAs -->
        <div class="lg:col-span-7 flex flex-col justify-center">
          
          <!-- Live Status Pill: Real-time Gaza Time & Technical Availability -->
          <div class="hero-badge inline-flex flex-wrap items-center gap-2.5 sm:gap-3 px-4 py-2 rounded-full backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] w-fit mb-6 shadow-[0_0_25px_rgba(255,85,0,0.15)]">
            <div class="flex items-center gap-2">
              <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 shadow-[0_0_8px_#10b981]"></span>
              </span>
              <span class="text-xs font-mono font-bold text-white tracking-wider flex items-center gap-1.5">
                <span class="text-emerald-400">GAZA, PS</span>
                <span class="text-amber-300 font-mono text-[11px] bg-[#ff5500]/15 px-2 py-0.5 rounded-md border border-[#ff5500]/30">{{ gazaTime }}</span>
              </span>
            </div>
            <span class="hidden sm:inline text-slate-600 text-xs font-mono">/</span>
            <span class="text-[11px] font-mono tracking-wider text-slate-300">
              AVAILABLE FOR ELITE 3D &amp; FRONTEND ARCHITECTURE
            </span>
          </div>

          <!-- Monumental Display Typography with 3D Skew Stagger & Kinetic Scramble -->
          <div 
            class="hero-title cursor-pointer group select-none mb-5"
            style="perspective: 900px;"
            @mouseenter="triggerScramble"
            @click="triggerScramble"
            title="Hover or click to re-scramble"
          >
            <h1 class="text-4xl sm:text-6xl xl:text-7xl font-black tracking-tight leading-[1.04]">
              <span class="block overflow-hidden pb-1">
                <span class="hero-title-line block bg-clip-text text-transparent bg-gradient-to-r from-white via-amber-100 to-amber-300 drop-shadow-[0_4px_25px_rgba(255,85,0,0.25)] transition-all duration-300 group-hover:drop-shadow-[0_0_30px_rgba(255,85,0,0.55)]">
                  {{ displayedFirstName }}
                </span>
              </span>
              <span class="block overflow-hidden pb-1">
                <span class="hero-title-line block bg-clip-text text-transparent bg-gradient-to-r from-[#ff5500] via-[#f59e0b] to-amber-200 drop-shadow-[0_4px_30px_rgba(255,85,0,0.4)] transition-all duration-300 group-hover:drop-shadow-[0_0_35px_rgba(255,85,0,0.75)]">
                  {{ displayedLastName }}
                </span>
              </span>
            </h1>
          </div>

          <!-- High-Contrast Cyber Subtitle -->
          <h2 class="hero-subtitle text-sm sm:text-base lg:text-lg font-bold tracking-[0.2em] text-transparent bg-clip-text bg-gradient-to-r from-[#00f2fe] via-cyan-200 to-[#7c3aed] uppercase mb-5 flex items-center gap-3 font-mono">
            <span class="w-2 h-2 rounded-full bg-[#00f2fe] shadow-[0_0_10px_#00f2fe] animate-pulse"></span>
            <span>{{ currentProfile.title || 'PRINCIPAL FRONT-END & 3D WEB ARCHITECT' }}</span>
          </h2>

          <!-- Bio Description -->
          <p class="hero-desc text-slate-300/90 text-sm sm:text-base lg:text-lg max-w-xl leading-relaxed mb-8">
            {{ currentProfile.shortBio || currentProfile.short_bio }}
          </p>

          <!-- Action Buttons: Magnetic Neon Eclipse CTA & Glassmorphic CV Download -->
          <div class="hero-buttons flex flex-wrap items-center gap-4 mb-8">
            <!-- Magnetic Primary CTA Button -->
            <a 
              ref="primaryCtaRef"
              href="#projects" 
              @mousemove="handlePrimaryMagnetic"
              @mouseleave="resetPrimaryMagnetic"
              :style="primaryMagneticStyle"
              class="px-7 py-3.5 rounded-2xl font-bold text-sm tracking-wide text-white bg-gradient-to-r from-[#ff5500] via-amber-500 to-[#f59e0b] border border-amber-300/40 shadow-[0_0_30px_rgba(255,85,0,0.5)] hover:shadow-[0_0_45px_rgba(255,85,0,0.8)] flex items-center gap-2.5 group cursor-pointer transition-shadow"
            >
              <span>Explore Selected Works</span>
              <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>

            <!-- Secondary Glassmorphic Download CV Action -->
            <button 
              type="button"
              @click="handleDownloadCv"
              :disabled="isDownloadingCv"
              class="px-6 py-3.5 rounded-2xl font-semibold text-sm tracking-wide text-slate-200 hover:text-white backdrop-blur-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/[0.1] hover:border-[#00f2fe]/50 shadow-[0_10px_30px_rgba(0,0,0,0.4)] hover:shadow-[0_0_25px_rgba(0,242,254,0.25)] flex items-center gap-2.5 cursor-pointer transition-all disabled:opacity-60"
            >
              <span v-if="isDownloadingCv" class="w-4 h-4 rounded-full border-2 border-[#00f2fe] border-t-transparent animate-spin"></span>
              <svg v-else-if="downloadSuccess" class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <svg v-else class="w-4 h-4 text-[#00f2fe]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              <span>{{ isDownloadingCv ? 'Preparing CV...' : (downloadSuccess ? 'Downloaded ✓' : 'Download CV') }}</span>
            </button>
          </div>

          <!-- Cyber Neon Social Connectivity Bar -->
          <div class="hero-social flex items-center gap-3">
            <span class="text-[11px] font-mono text-slate-400 tracking-wider uppercase hidden sm:inline-block">CHANNELS:</span>
            <div class="flex items-center gap-2 p-1.5 rounded-2xl backdrop-blur-xl bg-white/[0.03] border border-white/[0.08]">
              <!-- WhatsApp -->
              <a 
                :href="currentProfile.whatsapp || 'https://wa.me/970599000000'" 
                target="_blank" 
                rel="noopener noreferrer"
                class="group relative w-9 h-9 rounded-xl bg-[#090b14]/90 border border-white/[0.08] text-slate-400 hover:text-emerald-400 hover:border-emerald-400/60 hover:bg-emerald-500/10 hover:shadow-[0_0_18px_rgba(16,185,129,0.45)] flex items-center justify-center transition-all duration-300"
                aria-label="WhatsApp"
                title="WhatsApp"
              >
                <svg class="w-4 h-4 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24">
                  <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
              </a>

              <!-- LinkedIn -->
              <a 
                :href="currentProfile.linkedin || 'https://linkedin.com'" 
                target="_blank" 
                rel="noopener noreferrer"
                class="group relative w-9 h-9 rounded-xl bg-[#090b14]/90 border border-white/[0.08] text-slate-400 hover:text-[#00f2fe] hover:border-[#00f2fe]/60 hover:bg-[#00f2fe]/10 hover:shadow-[0_0_18px_rgba(0,242,254,0.45)] flex items-center justify-center transition-all duration-300"
                aria-label="LinkedIn"
                title="LinkedIn"
              >
                <svg class="w-4 h-4 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                </svg>
              </a>

              <!-- Instagram -->
              <a 
                :href="currentProfile.instagram || 'https://instagram.com'" 
                target="_blank" 
                rel="noopener noreferrer"
                class="group relative w-9 h-9 rounded-xl bg-[#090b14]/90 border border-white/[0.08] text-slate-400 hover:text-pink-400 hover:border-pink-400/60 hover:bg-pink-500/10 hover:shadow-[0_0_18px_rgba(236,72,153,0.45)] flex items-center justify-center transition-all duration-300"
                aria-label="Instagram"
                title="Instagram"
              >
                <svg class="w-4 h-4 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
              </a>

              <!-- X / Twitter -->
              <a 
                :href="currentProfile.x || 'https://x.com'" 
                target="_blank" 
                rel="noopener noreferrer"
                class="group relative w-9 h-9 rounded-xl bg-[#090b14]/90 border border-white/[0.08] text-slate-400 hover:text-white hover:border-white/60 hover:bg-white/10 hover:shadow-[0_0_18px_rgba(255,255,255,0.45)] flex items-center justify-center transition-all duration-300"
                aria-label="X (Twitter)"
                title="X (Twitter)"
              >
                <svg class="w-3.5 h-3.5 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24">
                  <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
              </a>

              <!-- Facebook -->
              <a 
                :href="currentProfile.facebook || 'https://facebook.com'" 
                target="_blank" 
                rel="noopener noreferrer"
                class="group relative w-9 h-9 rounded-xl bg-[#090b14]/90 border border-white/[0.08] text-slate-400 hover:text-[#7c3aed] hover:border-[#7c3aed]/60 hover:bg-[#7c3aed]/10 hover:shadow-[0_0_18px_rgba(124,58,237,0.45)] flex items-center justify-center transition-all duration-300"
                aria-label="Facebook"
                title="Facebook"
              >
                <svg class="w-4 h-4 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
              </a>
            </div>
          </div>

        </div>

        <!-- Right Hero Column: 3D Neon Eclipse Portrait & Orbiting HUD Cards -->
        <div class="lg:col-span-5 relative flex items-center justify-center lg:justify-end hero-card-3d">
          <HeroCanvas3D 
            :projects="projects"
            :profile="currentProfile"
            @open-project="$emit('open-project', $event)" 
          />
        </div>

      </div>

      <!-- ======================================================== -->
      <!-- Bottom Hero Telemetry Bar & Tech Strip                   -->
      <!-- ======================================================== -->
      <div class="hero-impact mt-10 lg:mt-12 space-y-6">
        
        <!-- Full-Width 4-Column Glassmorphic Telemetry Bar -->
        <div class="p-5 sm:p-6 rounded-3xl backdrop-blur-2xl bg-white/[0.03] border border-white/[0.08] shadow-[0_20px_50px_rgba(0,0,0,0.6),_inset_0_1px_1px_rgba(255,255,255,0.08)]">
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 divide-y sm:divide-y-0 sm:divide-x divide-white/[0.08]">
            <div 
              v-for="(stat, idx) in currentStats" 
              :key="stat.label" 
              class="flex items-center gap-4 pt-3 sm:pt-0 first:pt-0 sm:pl-6 first:pl-0"
            >
              <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#ff5500]/20 via-amber-500/10 to-transparent border border-[#ff5500]/30 flex items-center justify-center text-amber-400 font-mono text-xs font-bold shrink-0 shadow-[0_0_20px_rgba(255,85,0,0.2)]">
                0{{ idx + 1 }}
              </div>
              <div>
                <div class="text-2xl sm:text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-white via-amber-200 to-[#ff5500] font-mono tracking-tight">
                  {{ animatedStats[idx] !== undefined ? animatedStats[idx] : stat.value }}{{ (stat.highlight || stat.value || '').replace(/[0-9]/g, '') }}
                </div>
                <div class="text-xs font-mono text-slate-400 uppercase tracking-wider mt-0.5">
                  {{ stat.label }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Bottom Row: Tech I Work With Strip & Scroll To Explore Mouse Indicator -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-2">
          
          <!-- Tech I Work With Icon Strip -->
          <div class="flex flex-wrap items-center justify-center md:justify-start gap-2.5">
            <span class="text-[10px] font-mono uppercase tracking-[0.2em] text-slate-500 mr-2">
              TECH I WORK WITH //
            </span>
            <span 
              v-for="tech in ['Vue 3', 'Three.js / WebGL', 'GSAP', 'Tailwind CSS', 'Laravel API', 'TypeScript']" 
              :key="tech"
              class="px-3 py-1 rounded-full text-[11px] font-mono text-slate-300 bg-white/[0.03] border border-white/[0.08] hover:border-[#ff5500]/50 hover:text-amber-300 transition-colors"
            >
              {{ tech }}
            </span>
          </div>

          <!-- Scroll To Explore Mouse Indicator -->
          <a 
            href="#about"
            class="inline-flex items-center gap-3 text-[11px] font-mono uppercase tracking-[0.2em] text-slate-400 hover:text-amber-300 transition-colors group cursor-pointer"
          >
            <span>SCROLL TO EXPLORE</span>
            <span class="w-5 h-8 rounded-full border border-white/20 group-hover:border-[#ff5500] flex items-start justify-center p-1 transition-colors">
              <span class="w-1 h-2 rounded-full bg-[#ff5500] animate-bounce"></span>
            </span>
          </a>

        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted, onBeforeUnmount } from 'vue'
import { personalInfo } from '@/data/portfolioData'
import { portfolioService } from '@/services/portfolioService'
import HeroCanvas3D from './HeroCanvas3D.vue'

const props = defineProps({
  profile: {
    type: Object,
    default: () => ({})
  },
  projects: {
    type: Array,
    default: () => []
  },
  socialLinks: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['open-project'])

const currentProfile = computed(() => {
  return Object.keys(props.profile || {}).length ? { ...personalInfo, ...props.profile } : personalInfo
})

const resolveSocialUrl = (platformKey, fallbackUrl) => {
  const key = (platformKey || '').toLowerCase()
  if (Array.isArray(props.socialLinks) && props.socialLinks.length > 0) {
    const match = props.socialLinks.find(item => {
      if (item.is_active === false || item.is_active === 0) return false
      const p = (item.platform || item.icon || item.name || '').toLowerCase()
      if (key === 'x') return p === 'x' || p.includes('twitter')
      return p.includes(key)
    })
    if (match && match.url) return match.url
  }
  return fallbackUrl
}

const currentStats = computed(() => {
  if (props.stats && props.stats.length) return props.stats
  if (currentProfile.value.stats && currentProfile.value.stats.length) return currentProfile.value.stats
  return personalInfo.stats
})

// Magnetic Primary CTA Button
const primaryCtaRef = ref(null)
const primaryMagneticStyle = ref({ transform: 'translate3d(0px, 0px, 0px)', transition: 'transform 200ms ease-out' })

const handlePrimaryMagnetic = (e) => {
  if (!primaryCtaRef.value) return
  const rect = primaryCtaRef.value.getBoundingClientRect()
  const x = (e.clientX - rect.left - rect.width / 2) * 0.25
  const y = (e.clientY - rect.top - rect.height / 2) * 0.25
  primaryMagneticStyle.value = {
    transform: `translate3d(${x.toFixed(1)}px, ${y.toFixed(1)}px, 0px)`,
    transition: 'transform 60ms linear'
  }
}

const resetPrimaryMagnetic = () => {
  primaryMagneticStyle.value = {
    transform: 'translate3d(0px, 0px, 0px)',
    transition: 'transform 400ms cubic-bezier(0.16, 1, 0.3, 1)'
  }
}

// ==========================================
// CV Download Handler (GET /api/cv/download)
// ==========================================
const isDownloadingCv = ref(false)
const downloadSuccess = ref(false)

const handleDownloadCv = async () => {
  if (isDownloadingCv.value) return
  isDownloadingCv.value = true
  downloadSuccess.value = false

  try {
    await portfolioService.downloadCv(currentProfile.value?.cv_url || currentProfile.value?.cv)
    downloadSuccess.value = true
    setTimeout(() => {
      downloadSuccess.value = false
    }, 3500)
  } catch (err) {
    console.warn('[HeroSection] CV download note:', err)
  } finally {
    isDownloadingCv.value = false
  }
}

// ==========================================
// 1. Animated Roll-up Metrics Counters
// ==========================================
const animatedStats = reactive([0, 0, 0, 0])

const startCounterAnimation = () => {
  const duration = 1600
  const startTime = performance.now()
  const targetValues = currentStats.value.map(s => {
    const num = parseInt((s.value || s.highlight || '0').replace(/[^0-9]/g, ''), 10)
    return isNaN(num) ? 0 : num
  })

  const step = (currentTime) => {
    const elapsed = currentTime - startTime
    const progress = Math.min(elapsed / duration, 1)
    const easeOut = 1 - Math.pow(1 - progress, 3)

    targetValues.forEach((target, idx) => {
      animatedStats[idx] = Math.round(target * easeOut)
    })

    if (progress < 1) {
      requestAnimationFrame(step)
    } else {
      targetValues.forEach((target, idx) => {
        animatedStats[idx] = target
      })
    }
  }

  requestAnimationFrame(step)
}

watch(currentStats, () => {
  startCounterAnimation()
})

// ==========================================
// 2. Cyber Kinetic Text Scramble Engine
// ==========================================
const fullNameParts = computed(() => {
  const raw = (currentProfile.value.name || 'MOHAMMED ALHWAITI').trim().split(' ')
  return {
    first: raw[0] || 'MOHAMMED',
    last: raw.slice(1).join(' ') || 'ALHWAITI'
  }
})

const displayedFirstName = ref(fullNameParts.value.first)
const displayedLastName = ref(fullNameParts.value.last)

watch(fullNameParts, (val) => {
  displayedFirstName.value = val.first
  displayedLastName.value = val.last
})

const GLYPHS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#%&*+<>'
let scrambleFrame = null

const scrambleWord = (targetText, outputRef, duration = 650) => {
  const startTime = performance.now()
  const length = targetText.length

  const update = (now) => {
    const elapsed = now - startTime
    const progress = Math.min(elapsed / duration, 1)
    const revealIndex = Math.floor(progress * length)

    let out = ''
    for (let i = 0; i < length; i++) {
      if (targetText[i] === ' ') {
        out += ' '
      } else if (i < revealIndex) {
        out += targetText[i]
      } else {
        out += GLYPHS[Math.floor(Math.random() * GLYPHS.length)]
      }
    }

    outputRef.value = out

    if (progress < 1) {
      scrambleFrame = requestAnimationFrame(update)
    } else {
      outputRef.value = targetText
    }
  }

  requestAnimationFrame(update)
}

const triggerScramble = () => {
  if (scrambleFrame) cancelAnimationFrame(scrambleFrame)
  scrambleWord(fullNameParts.value.first, displayedFirstName, 550)
  scrambleWord(fullNameParts.value.last, displayedLastName, 700)
}

// ==========================================
// 3. Live Gaza Real-Time Clock
// ==========================================
const gazaTime = ref('')
let clockInterval = null

const updateGazaClock = () => {
  try {
    const formatter = new Intl.DateTimeFormat('en-US', {
      timeZone: 'Asia/Gaza',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
      hour12: false
    })
    gazaTime.value = `${formatter.format(new Date())} EET`
  } catch (e) {
    const now = new Date()
    gazaTime.value = now.toTimeString().slice(0, 8)
  }
}

onMounted(() => {
  updateGazaClock()
  clockInterval = setInterval(updateGazaClock, 1000)
  setTimeout(() => {
    triggerScramble()
    startCounterAnimation()
  }, 250)
})

onBeforeUnmount(() => {
  if (clockInterval) clearInterval(clockInterval)
  if (scrambleFrame) cancelAnimationFrame(scrambleFrame)
})
</script>
