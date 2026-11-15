<template>
  <section id="about" class="about-section relative py-24 sm:py-32">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
      
      <!-- Section Header -->
      <div class="section-header flex flex-col md:flex-row md:items-end justify-between mb-12 sm:mb-16">
        <div>
          <div class="section-badge inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] text-amber-400 font-mono text-xs tracking-wider uppercase mb-3 shadow-[0_0_20px_rgba(255,85,0,0.15)]">
            <span class="w-2 h-2 rounded-full bg-[#ff5500] animate-pulse"></span>
            <span>// THE ARCHITECT BEHIND THE CODE</span>
          </div>
          <h2 class="section-title text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
            About <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#ff5500] via-amber-400 to-amber-200">Mohammed</span>
          </h2>
        </div>
        <p class="mt-4 md:mt-0 text-slate-400 max-w-md text-sm sm:text-base leading-relaxed">
          Fusing mathematical logic, WebGL 3D immersion, and pixel-perfect design systems to build unforgettable web experiences.
        </p>
      </div>

      <!-- Asymmetric Bento Architectural Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
        
        <!-- Left Bento Column: Developer Bio Card, Core Pillars & Performance Metrics -->
        <div class="lg:col-span-5 space-y-6">
          
          <!-- Bento Card 1: Verified Architect Persona & Core Pillars -->
          <div 
            class="bento-card relative p-7 sm:p-8 rounded-3xl backdrop-blur-2xl bg-white/[0.03] border border-white/[0.08] hover:border-[#ff5500]/50 shadow-[0_25px_60px_rgba(0,0,0,0.6),_inset_0_1px_1px_rgba(255,255,255,0.08)] transition-all duration-300 overflow-hidden group"
            :style="bentoTilt['bio']?.tiltStyle || {}"
            @mousemove="handleBentoMouseMove($event, 'bio')"
            @mouseleave="handleBentoMouseLeave('bio')"
          >
            <!-- Radial Mouse Spotlight Glow -->
            <div 
              class="pointer-events-none absolute inset-0 transition-opacity duration-300 z-0"
              :style="bentoTilt['bio']?.spotlightStyle || { opacity: 0 }"
            ></div>

            <div class="relative z-10">
              <!-- Persona Header with Verified Badge -->
              <div class="flex items-center gap-4 mb-6">
                <div class="relative w-16 h-16 rounded-2xl p-[2px] bg-gradient-to-br from-[#ff5500] via-amber-400 to-[#00f2fe] shadow-[0_0_25px_rgba(255,85,0,0.35)] shrink-0">
                  <img 
                    :src="resolveProfileAvatar(currentProfile)" 
                    :alt="currentProfile.name" 
                    class="w-full h-full object-cover rounded-[14px] bg-[#05060b]"
                    @error="(e) => e.target.src = '/developer-portrait.jpg'"
                  />
                  <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-[#05060b] flex items-center justify-center text-[10px] text-black font-bold" title="Verified Principal Architect">
                    ✓
                  </span>
                </div>
                <div>
                  <div class="flex items-center gap-2">
                    <h3 class="text-lg font-extrabold text-white tracking-wide">
                      {{ currentProfile.name }}
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-mono uppercase bg-[#ff5500]/15 text-amber-300 border border-[#ff5500]/30">
                      VERIFIED
                    </span>
                  </div>
                  <p class="text-xs font-mono text-amber-400 mt-0.5">
                    {{ currentProfile.title }}
                  </p>
                  <p class="text-[11px] text-slate-400 font-mono mt-0.5">
                    📍 {{ currentProfile.location }}
                  </p>
                </div>
              </div>

              <!-- Biography Copy -->
              <p class="text-sm text-slate-300/90 leading-relaxed mb-6">
                {{ currentProfile.fullBio || currentProfile.full_bio }}
              </p>

              <!-- Core Architectural Pillars -->
              <div class="space-y-3 pt-5 border-t border-white/[0.08] font-mono text-xs">
                <div 
                  v-for="point in philosophyPoints" 
                  :key="point.id || point.number" 
                  class="p-3 rounded-2xl bg-[#05060b]/60 border border-white/[0.06] hover:border-amber-500/40 transition-colors flex items-start gap-3"
                >
                  <span 
                    class="px-2 py-0.5 rounded-lg font-bold shrink-0 text-[11px] bg-white/[0.04] border border-white/[0.08]" 
                    :style="{ color: point.color || '#ff5500' }"
                  >
                    {{ point.number }}
                  </span>
                  <span class="text-slate-300 leading-relaxed">
                    <strong class="text-white">{{ point.title }}:</strong> {{ point.description }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Bento Card 2: Performance Telemetry Metrics (60 FPS, 99.8% / 100%) -->
          <div class="grid grid-cols-2 gap-4">
            <div 
              v-for="(metric, idx) in aboutMetrics" 
              :key="metric.id || metric.label" 
              class="bento-card relative p-5 rounded-3xl backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] hover:border-[#00f2fe]/50 text-center transition-all duration-300 overflow-hidden group"
              :style="bentoTilt[`metric_${idx}`]?.tiltStyle || {}"
              @mousemove="handleBentoMouseMove($event, `metric_${idx}`)"
              @mouseleave="handleBentoMouseLeave(`metric_${idx}`)"
            >
              <div 
                class="pointer-events-none absolute inset-0 transition-opacity duration-300 z-0"
                :style="bentoTilt[`metric_${idx}`]?.spotlightStyle || { opacity: 0 }"
              ></div>
              <div class="relative z-10">
                <div 
                  class="text-2xl sm:text-3xl font-black font-mono tracking-tight" 
                  :style="{ color: metric.color || (idx === 0 ? '#ff5500' : '#00f2fe') }"
                >
                  {{ metric.value }}
                </div>
                <div class="text-[11px] font-mono text-slate-400 mt-1 uppercase tracking-wider">
                  {{ metric.label }}
                </div>
              </div>
            </div>
          </div>

          <!-- Bento Card 3: Official CV Document Download -->
          <button
            type="button"
            @click="handleDownloadCv"
            :disabled="isDownloadingCv"
            class="bento-card w-full py-4 px-6 rounded-2xl backdrop-blur-xl bg-gradient-to-r from-[#ff5500]/15 via-amber-500/10 to-[#00f2fe]/10 hover:from-[#ff5500]/25 hover:to-[#00f2fe]/20 border border-[#ff5500]/40 hover:border-amber-400 text-amber-300 hover:text-white font-mono text-xs flex items-center justify-between transition-all duration-300 group shadow-[0_0_25px_rgba(255,85,0,0.2)] cursor-pointer disabled:opacity-60"
          >
            <span class="flex items-center gap-3">
              <span v-if="isDownloadingCv" class="w-4 h-4 rounded-full border-2 border-amber-400 border-t-transparent animate-spin"></span>
              <svg v-else-if="downloadSuccess" class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <svg v-else class="w-4 h-4 text-[#ff5500] group-hover:translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <span class="font-bold">{{ isDownloadingCv ? 'Preparing Official CV Document...' : (downloadSuccess ? 'CV Download Initiated ✓' : 'Download Complete CV Document') }}</span>
            </span>
            <span class="px-2.5 py-1 rounded-lg bg-[#ff5500]/20 text-[10px] font-bold text-amber-300 border border-[#ff5500]/30">
              PDF // VERIFIED
            </span>
          </button>

        </div>

        <!-- Right Bento Column: Interactive Career Trajectory & Milestones -->
        <div class="lg:col-span-7">
          <div 
            class="bento-card relative p-7 sm:p-10 rounded-3xl backdrop-blur-2xl bg-white/[0.03] border border-white/[0.08] hover:border-amber-500/40 shadow-[0_25px_60px_rgba(0,0,0,0.6),_inset_0_1px_1px_rgba(255,255,255,0.08)] transition-all duration-300 overflow-hidden"
            :style="bentoTilt['timeline']?.tiltStyle || {}"
            @mousemove="handleBentoMouseMove($event, 'timeline')"
            @mouseleave="handleBentoMouseLeave('timeline')"
          >
            <!-- Radial Mouse Spotlight Glow -->
            <div 
              class="pointer-events-none absolute inset-0 transition-opacity duration-300 z-0"
              :style="bentoTilt['timeline']?.spotlightStyle || { opacity: 0 }"
            ></div>

            <div class="relative z-10">
              <div class="flex items-center justify-between pb-6 mb-8 border-b border-white/[0.08]">
                <div class="flex items-center gap-3">
                  <span class="w-2.5 h-2.5 rounded-full bg-[#ff5500] shadow-[0_0_10px_#ff5500]"></span>
                  <h3 class="text-sm sm:text-base font-extrabold text-white tracking-wider uppercase font-mono">
                    Career Trajectory &amp; Milestones
                  </h3>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-mono bg-[#ff5500]/15 text-amber-300 border border-[#ff5500]/30">
                  {{ currentProfile.timeline_header || currentProfile.timelineHeader || '2019 — Present' }}
                </span>
              </div>

              <!-- Interactive Timeline Cards -->
              <div class="relative border-l-2 border-gradient-to-b from-[#ff5500]/50 via-amber-500/30 to-[#00f2fe]/30 border-amber-500/25 pl-6 sm:pl-8 space-y-6">
                <div 
                  v-for="m in milestonesList" 
                  :key="m.id || m.title" 
                  class="relative p-5 rounded-2xl bg-[#05060b]/70 border border-white/[0.07] hover:border-amber-400/50 transition-all duration-300 group/ms hover:-translate-y-0.5 hover:shadow-[0_10px_30px_rgba(255,85,0,0.12)]"
                >
                  <!-- Glowing Timeline Node -->
                  <div 
                    class="absolute -left-[33px] sm:-left-[41px] top-6 w-4 h-4 rounded-full bg-[#05060b] border-2 group-hover/ms:scale-125 transition-transform"
                    :style="{ 
                      borderColor: m.color || '#ff5500',
                      boxShadow: `0 0 12px ${m.color || '#ff5500'}`
                    }"
                  ></div>

                  <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                    <h4 class="text-base font-bold text-white group-hover/ms:text-amber-300 transition-colors">
                      {{ m.title }}
                    </h4>
                    <span 
                      class="px-2.5 py-0.5 rounded-full text-[10px] font-mono border"
                      :style="{ 
                        backgroundColor: `${m.color || '#ff5500'}18`, 
                        borderColor: `${m.color || '#ff5500'}45`,
                        color: m.color || '#f59e0b'
                      }"
                    >
                      {{ m.period }}
                    </span>
                  </div>

                  <p 
                    v-if="m.company" 
                    class="text-xs font-mono mb-2"
                    :style="{ color: m.color === '#d4af37' ? '#00f2fe' : (m.color || '#f59e0b') }"
                  >
                    {{ m.company }}
                  </p>

                  <p class="text-xs sm:text-sm text-slate-300/85 leading-relaxed">
                    {{ m.description }}
                  </p>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>

    </div>
  </section>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { personalInfo, defaultMilestones, defaultPhilosophy, defaultAboutMetrics } from '@/data/portfolioData'
import { portfolioService } from '@/services/portfolioService'
import { profileState, resolveProfileAvatar } from '@/services/profileState'

const props = defineProps({
  profile: {
    type: Object,
    default: () => ({})
  }
})

const currentProfile = computed(() => {
  return { ...personalInfo, ...profileState, ...(props.profile || {}) }
})

const philosophyPoints = computed(() => {
  if (Array.isArray(props.profile?.philosophy) && props.profile.philosophy.length) {
    return props.profile.philosophy
  }
  return defaultPhilosophy
})

const milestonesList = computed(() => {
  if (Array.isArray(props.profile?.milestones) && props.profile.milestones.length) {
    return props.profile.milestones
  }
  if (Array.isArray(props.profile?.timeline) && props.profile.timeline.length) {
    return props.profile.timeline
  }
  return defaultMilestones
})

const aboutMetrics = computed(() => {
  if (Array.isArray(props.profile?.about_metrics) && props.profile.about_metrics.length) {
    return props.profile.about_metrics
  }
  if (Array.isArray(props.profile?.metrics) && props.profile.metrics.length) {
    return props.profile.metrics
  }
  return defaultAboutMetrics
})

// ==========================================
// 3D Mouse Tilt & Radial Spotlight Handler
// ==========================================
const bentoTilt = reactive({})

const handleBentoMouseMove = (e, key) => {
  const card = e.currentTarget
  const rect = card.getBoundingClientRect()
  const x = e.clientX - rect.left
  const y = e.clientY - rect.top
  const centerX = rect.width / 2
  const centerY = rect.height / 2

  const maxTilt = 4.5
  const rotX = ((y - centerY) / centerY) * -maxTilt
  const rotY = ((x - centerX) / centerX) * maxTilt

  bentoTilt[key] = {
    tiltStyle: {
      transform: `perspective(1100px) rotateX(${rotX.toFixed(2)}deg) rotateY(${rotY.toFixed(2)}deg) translate3d(0, -2px, 0)`,
      transition: 'transform 80ms ease-out',
      willChange: 'transform'
    },
    spotlightStyle: {
      opacity: 1,
      background: `radial-gradient(420px circle at ${x}px ${y}px, rgba(255, 85, 0, 0.14), rgba(0, 242, 254, 0.05) 45%, transparent 80%)`
    }
  }
}

const handleBentoMouseLeave = (key) => {
  bentoTilt[key] = {
    tiltStyle: {
      transform: 'perspective(1100px) rotateX(0deg) rotateY(0deg) translate3d(0, 0, 0)',
      transition: 'transform 450ms cubic-bezier(0.16, 1, 0.3, 1)'
    },
    spotlightStyle: {
      opacity: 0
    }
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
    console.warn('[AboutSection] CV download note:', err)
  } finally {
    isDownloadingCv.value = false
  }
}
</script>
