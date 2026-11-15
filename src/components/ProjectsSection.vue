<template>
  <section id="projects" class="projects-section relative py-24 sm:py-32 overflow-hidden">
    
    <!-- Dynamic Ambient Glow Morphing to Active Project's Accent Color -->
    <div 
      class="pointer-events-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] sm:w-[750px] h-[450px] rounded-full blur-[160px] opacity-25 transition-all duration-700 z-0"
      :style="{ background: activeAmbientColor }"
    ></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
      
      <!-- Section Header + Live Telemetry Status Badges + Category Filters -->
      <div class="section-header flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12 sm:mb-16">
        <div>
          <div class="section-badge inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] text-[#00f2fe] font-mono text-xs tracking-wider uppercase mb-3 shadow-[0_0_20px_rgba(0,242,254,0.15)]">
            <span class="w-2 h-2 rounded-full bg-[#00f2fe] animate-pulse"></span>
            <span>// CURATED 3D &amp; FRONT-END CASE STUDIES</span>
          </div>
          <h2 class="section-title text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
            Selected <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#ff5500] via-amber-400 to-[#00f2fe]">Projects</span>
          </h2>
        </div>

        <!-- Controls Toolbar: Permanent Telemetry Badges & Category Pills -->
        <div class="flex flex-wrap items-center gap-3 font-mono text-xs">
          
          <!-- 1. Always-On X-Ray Blueprint Status Badge -->
          <div
            class="px-3.5 py-2 rounded-2xl border backdrop-blur-xl flex items-center gap-2 bg-[#00f2fe]/15 border-[#00f2fe]/60 text-[#00f2fe] shadow-[0_0_25px_rgba(0,242,254,0.3)] select-none"
            title="Laser X-Ray Architecture Blueprint Scanner Always Active"
          >
            <span class="w-2 h-2 rounded-full bg-[#00f2fe] animate-ping"></span>
            <span class="font-bold tracking-wider">● X-RAY BLUEPRINT: ACTIVE</span>
          </div>

          <!-- 2. 3D Auto-Flip Running Status Pill (Click to Pause/Resume + Mini Progress Bar) -->
          <button
            type="button"
            @click="toggleAutoFlip"
            class="relative overflow-hidden px-3.5 py-2 rounded-2xl border backdrop-blur-xl flex items-center gap-2 transition-all duration-300 cursor-pointer"
            :class="isAutoFlipEnabled && !isStageHovered
              ? 'bg-amber-500/15 border-amber-500/50 text-amber-300 shadow-[0_0_25px_rgba(245,158,11,0.28)]'
              : 'bg-white/[0.03] border-white/[0.1] text-slate-400 hover:text-white'"
            title="Click to Pause / Resume 3D Auto-Flip Carousel"
          >
            <span 
              class="w-2 h-2 rounded-full" 
              :class="isAutoFlipEnabled && !isStageHovered ? 'bg-amber-400 animate-pulse' : 'bg-slate-500'"
            ></span>
            <span class="font-bold tracking-wider">
              ● 3D AUTO-FLIP: {{ !isAutoFlipEnabled ? 'PAUSED' : (isStageHovered ? 'HOLD' : 'RUNNING') }}
            </span>

            <!-- Live Mini Progress Bar on Bottom of Pill -->
            <span class="absolute bottom-0 left-0 right-0 h-[2px] bg-white/[0.06]">
              <span 
                class="block h-full bg-gradient-to-r from-amber-400 via-[#ff5500] to-[#00f2fe] transition-all duration-75"
                :style="{ width: `${autoFlipProgress}%` }"
              ></span>
            </span>
          </button>

          <!-- 3. Category Filter Pills -->
          <div class="flex items-center p-1 rounded-2xl backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] overflow-x-auto max-w-full">
            <button
              v-for="cat in categories"
              :key="cat"
              type="button"
              @click="selectCategory(cat)"
              class="px-3 py-1.5 rounded-xl transition-all duration-200 capitalize font-medium cursor-pointer shrink-0"
              :class="activeCategory === cat 
                ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-[0_0_12px_rgba(245,158,11,0.25)]' 
                : 'text-slate-400 hover:text-white'"
            >
              {{ cat }}
            </button>
          </div>

        </div>
      </div>

      <!-- Loading Skeleton Cards -->
      <div v-if="isLoading && filteredProjects.length === 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <div 
          v-for="n in 3" 
          :key="n"
          class="rounded-3xl p-6 bg-white/[0.03] border border-white/[0.08] animate-pulse min-h-[440px] flex flex-col justify-between"
        >
          <div class="flex items-center justify-between mb-4">
            <div class="w-28 h-5 bg-slate-800/80 rounded-full"></div>
            <div class="w-20 h-5 bg-slate-800/60 rounded-full"></div>
          </div>
          <div class="flex-1 w-full bg-slate-800/40 rounded-2xl mb-5 flex items-center justify-center">
            <div class="w-10 h-10 rounded-full border-2 border-amber-500/20 animate-spin border-t-amber-400"></div>
          </div>
          <div class="space-y-3">
            <div class="w-2/3 h-6 bg-slate-800/80 rounded-lg"></div>
            <div class="w-full h-4 bg-slate-800/50 rounded-lg"></div>
          </div>
        </div>
      </div>

      <!-- Empty State (No Projects in Selected Category) -->
      <div 
        v-else-if="filteredProjects.length === 0" 
        class="py-16 px-6 rounded-3xl backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] text-center max-w-2xl mx-auto shadow-2xl"
      >
        <div class="w-16 h-16 rounded-2xl bg-[#ff5500]/10 border border-[#ff5500]/30 text-amber-400 flex items-center justify-center mx-auto mb-4 shadow-[0_0_25px_rgba(255,85,0,0.2)]">
          <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">
          {{ activeCategory === 'all' ? 'No Projects Registered Yet' : `No Projects in "${activeCategory}"` }}
        </h3>
        <p class="text-xs sm:text-sm text-slate-400 font-mono mb-6 leading-relaxed">
          Dynamic projects will appear here as soon as they are added and activated via the Alhwaiti Core Admin Dashboard.
        </p>
        <router-link 
          to="/admin/projects" 
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#ff5500] to-amber-500 text-white font-bold text-xs shadow-[0_0_20px_rgba(255,85,0,0.3)] hover:scale-105 transition-transform"
        >
          <span>Open Projects Studio</span>
          <span>→</span>
        </router-link>
      </div>

      <!-- ======================================================== -->
      <!-- PERMANENT 3D CINEMA COVERFLOW STAGE (Auto-Flip + X-Ray)  -->
      <!-- ======================================================== -->
      <div v-else class="space-y-8">
        <div 
          class="projects-3d-stage relative w-full h-[540px] sm:h-[580px] flex items-center justify-center select-none"
          style="perspective: 1400px; transform-style: preserve-3d;"
          @mouseenter="isStageHovered = true"
          @mouseleave="isStageHovered = false"
        >
          <div
            v-for="(project, idx) in filteredProjects"
            :key="project.id || project.title || idx"
            class="project-card absolute w-full max-w-[380px] sm:max-w-[480px] md:max-w-[540px] cursor-pointer"
            :style="getCoverflowStyle(idx)"
            @click="handleCoverflowClick(idx, project)"
          >
            <!-- Outer Glass Shell (Triggers Physical 3D Flip-In Keyframe When Active) -->
            <div 
              class="project-card-shell relative rounded-3xl p-6 sm:p-7 backdrop-blur-2xl bg-[#090b14]/92 border transition-all duration-500 overflow-hidden flex flex-col justify-between"
              :class="idx === activeProjectIndex 
                ? 'is-active-3d-flip border-[#ff5500]/65 shadow-[0_30px_80px_rgba(0,0,0,0.9),_0_0_50px_rgba(255,85,0,0.25)]' 
                : 'border-white/[0.08] shadow-[0_20px_50px_rgba(0,0,0,0.75)] hover:border-white/20'"
            >
              <!-- Top Glowing Progress Line on Active Center Card -->
              <div 
                v-if="idx === activeProjectIndex" 
                class="absolute top-0 left-0 right-0 h-1 bg-white/[0.05] overflow-hidden z-40"
              >
                <div 
                  class="h-full bg-gradient-to-r from-amber-500 via-cyan-400 to-amber-500 shadow-[0_0_12px_#00f2fe] transition-all duration-75"
                  :style="{ width: `${autoFlipProgress}%` }"
                ></div>
              </div>

              <!-- Top Status & Badge Bar -->
              <div class="flex items-center justify-between gap-3 mb-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#05060b]/90 border border-emerald-500/30 text-emerald-400 font-mono text-[10px] uppercase tracking-wider">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                  <span>{{ project.status || 'Production Ready' }}</span>
                </div>
                <span class="px-3 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase font-mono bg-[#ff5500]/15 text-amber-300 border border-[#ff5500]/35">
                  {{ project.badge || project.category || 'Featured' }}
                </span>
              </div>

              <!-- Dark-Luxury Browser/App Mockup Frame + Always-On Laser X-Ray Scanner -->
              <div class="relative rounded-2xl overflow-hidden bg-[#05060b] border border-white/[0.1] mb-5 shadow-inner group/mockup">
                <!-- macOS Window Dots & URL Bar -->
                <div class="h-8 px-3.5 bg-[#090b14] border-b border-white/[0.07] flex items-center justify-between">
                  <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400/80"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                  </div>
                  <div class="px-3 py-0.5 rounded-md bg-[#05060b] border border-white/[0.06] text-[10px] font-mono text-slate-400 truncate max-w-[200px] sm:max-w-[260px] flex items-center gap-1.5">
                    <span class="text-emerald-400">🔒</span>
                    <span>{{ formatMockupDomain(project) }}</span>
                  </div>
                  <span class="text-[10px] font-mono text-[#00f2fe]">X-RAY</span>
                </div>

                <!-- Viewport Container with Dark Contrast Filter for White/Varied Logos -->
                <div class="relative h-52 sm:h-60 w-full bg-[radial-gradient(circle_at_center,#111625_0%,#05060b_100%)] flex items-center justify-center overflow-hidden p-3">
                  <!-- Subtle Inner Contrast Vignette for Bright Logos -->
                  <div class="pointer-events-none absolute inset-0 ring-1 ring-inset ring-white/10 bg-slate-950/20 z-10 rounded-xl"></div>

                  <img 
                    :src="project.image || project.thumbnail_url || project.thumbnail || project.previewImage || project.preview_image || '/projects/wasla-preview.svg'" 
                    :alt="project.title"
                    @error="(e) => e.target.src = '/projects/wasla-preview.svg'"
                    class="w-full h-full object-contain rounded-xl transition-transform duration-700 group-hover/mockup:scale-105 filter contrast-105 drop-shadow-[0_12px_28px_rgba(0,0,0,0.8)]"
                  />

                  <!-- Always-On Laser X-Ray Blueprint Overlay on Active Center Card -->
                  <div 
                    v-if="isXRayActive && idx === activeProjectIndex"
                    class="xray-blueprint-layer absolute inset-0 pointer-events-none z-20 flex flex-col justify-between p-4 bg-[#05060b]/85 backdrop-blur-xs border-r-2 border-[#00f2fe]"
                  >
                    <div class="flex items-center justify-between text-[10px] font-mono text-[#00f2fe]">
                      <span>// WIREFRAME TOPOLOGY</span>
                      <span>MESH: VERIFIED</span>
                    </div>
                    <div class="my-auto grid grid-cols-2 gap-2 font-mono text-[10px]">
                      <div class="p-2 rounded-lg bg-[#00f2fe]/10 border border-[#00f2fe]/30 text-cyan-200">
                        <span class="text-slate-400 block">ARCHITECTURE</span>
                        <strong class="text-[#00f2fe]">{{ (project.tech || project.technologies || ['Vue 3', 'WebGL'])[0] }}</strong>
                      </div>
                      <div class="p-2 rounded-lg bg-[#ff5500]/10 border border-[#ff5500]/30 text-amber-200">
                        <span class="text-slate-400 block">RENDER PIPELINE</span>
                        <strong class="text-amber-300">GPU Accelerated</strong>
                      </div>
                    </div>
                    <div class="text-[9px] font-mono text-slate-400 flex justify-between">
                      <span>ID: #{{ project.id || idx + 1 }}</span>
                      <span class="text-emerald-400">STATUS: 200 OK</span>
                    </div>
                  </div>

                  <!-- Vertical Sweeping Cyan Laser Beam (5% <-> 92%) -->
                  <div 
                    v-if="isXRayActive && idx === activeProjectIndex"
                    class="xray-laser-beam absolute top-0 bottom-0 w-[3px] bg-gradient-to-b from-transparent via-[#00f2fe] to-transparent shadow-[0_0_20px_#00f2fe,0_0_8px_#ffffff] z-30 pointer-events-none"
                  ></div>
                </div>
              </div>

              <!-- Project Title, Description & Tech Pills -->
              <div>
                <div class="flex items-center justify-between gap-2 mb-1.5">
                  <h3 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                    {{ project.title }}
                  </h3>
                  <span class="text-xs font-mono text-amber-400 font-bold">
                    {{ project.year || '2025' }}
                  </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-300/85 line-clamp-2 mb-4">
                  {{ project.tagline || project.subtitle || project.description }}
                </p>

                <div class="flex items-center justify-between gap-3 pt-3 border-t border-white/[0.08]">
                  <div class="flex flex-wrap items-center gap-1.5">
                    <span 
                      v-for="t in (project.tech || project.technologies || []).slice(0, 3)" 
                      :key="t"
                      class="px-2.5 py-1 rounded-lg text-[10px] font-mono bg-white/[0.04] border border-white/[0.08] text-slate-300"
                    >
                      {{ t }}
                    </span>
                  </div>
                  <button
                    type="button"
                    @click.stop="$emit('open-project', project)"
                    class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#ff5500] to-amber-500 text-white font-bold text-xs shadow-[0_0_20px_rgba(255,85,0,0.35)] hover:scale-105 transition-transform shrink-0 cursor-pointer"
                  >
                    Inspect Case →
                  </button>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Coverflow Stage Navigation Controls -->
        <div class="flex items-center justify-center gap-4">
          <button
            type="button"
            @click="prevSlide"
            class="w-11 h-11 rounded-full backdrop-blur-xl bg-white/[0.04] hover:bg-[#ff5500]/20 border border-white/[0.1] hover:border-[#ff5500]/50 text-white flex items-center justify-center transition-all cursor-pointer"
            aria-label="Previous Project"
          >
            ←
          </button>

          <div class="flex items-center gap-2 px-4 py-2 rounded-full backdrop-blur-xl bg-white/[0.03] border border-white/[0.08]">
            <button
              v-for="(p, idx) in filteredProjects"
              :key="p.id || idx"
              type="button"
              @click="goToSlide(idx)"
              class="h-2 rounded-full transition-all duration-300 cursor-pointer"
              :class="idx === activeProjectIndex ? 'w-8 bg-gradient-to-r from-[#ff5500] to-[#00f2fe]' : 'w-2 bg-slate-600 hover:bg-slate-400'"
              :aria-label="`Go to project ${idx + 1}`"
            ></button>
          </div>

          <button
            type="button"
            @click="nextSlide"
            class="w-11 h-11 rounded-full backdrop-blur-xl bg-white/[0.04] hover:bg-[#ff5500]/20 border border-white/[0.1] hover:border-[#ff5500]/50 text-white flex items-center justify-center transition-all cursor-pointer"
            aria-label="Next Project"
          >
            →
          </button>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'
import { projects as defaultProjects } from '../data/portfolioData'

const props = defineProps({
  projects: {
    type: Array,
    default: () => []
  },
  isLoading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['open-project'])

// Dynamic projects from Database with instant fallback so 3D Stage is never empty
const allProjects = computed(() => {
  return Array.isArray(props.projects) && props.projects.length > 0 ? props.projects : defaultProjects
})

const activeCategory = ref('all')
const isXRayActive = ref(true) // Permanently active X-Ray Blueprint Scanner
const activeProjectIndex = ref(0)

// Auto-Flip 3D Carousel State
const isAutoFlipEnabled = ref(true)
const isStageHovered = ref(false)
const autoFlipProgress = ref(0)
let autoFlipInterval = null

const categories = computed(() => {
  const set = new Set(['all'])
  allProjects.value.forEach(p => {
    if (p.category) set.add(p.category)
  })
  return Array.from(set)
})

const filteredProjects = computed(() => {
  const current = (activeCategory.value || '').toLowerCase()
  if (current === 'all' || !current) return allProjects.value
  return allProjects.value.filter(p => (p.category || '').toLowerCase().includes(current))
})

const selectCategory = (cat) => {
  activeCategory.value = cat
  activeProjectIndex.value = 0
  autoFlipProgress.value = 0
}

// Watch category & filtered list to keep activeProjectIndex strictly within bounds
watch(activeCategory, () => {
  activeProjectIndex.value = 0
  autoFlipProgress.value = 0
})

watch(filteredProjects, (list) => {
  if (!list.length || activeProjectIndex.value >= list.length) {
    activeProjectIndex.value = 0
  }
  autoFlipProgress.value = 0
})

const activeAmbientColor = computed(() => {
  const activeProj = filteredProjects.value[activeProjectIndex.value]
  return activeProj?.accentColor || activeProj?.accent_color || '#ff5500'
})

const formatMockupDomain = (project) => {
  if (project.liveUrl || project.live_url) {
    return (project.liveUrl || project.live_url).replace(/^https?:\/\//, '')
  }
  const slug = (project.title || 'app').toLowerCase().replace(/[^a-z0-9]+/g, '-')
  return `${slug}.alhwaiti.dev`
}

// Circular Modulo Offset & 3D Transform Binding for Every Card
const getCoverflowStyle = (idx) => {
  const total = filteredProjects.value.length
  if (total === 0) return {}

  // Compute circular relative offset
  let offset = idx - activeProjectIndex.value
  if (total > 2) {
    if (offset > total / 2) offset -= total
    if (offset < -total / 2) offset += total
  }

  // Center Card (offset === 0)
  if (offset === 0) {
    return {
      transform: 'translate3d(0, 0, 110px) rotateY(0deg) scale(1)',
      zIndex: 30,
      opacity: 1,
      pointerEvents: 'auto'
    }
  }

  // Left Card (offset === -1 or last item wrapping left)
  if (offset === -1 || (activeProjectIndex.value === 0 && idx === total - 1 && total > 1)) {
    return {
      transform: 'translate3d(-58%, 0, -70px) rotateY(32deg) rotateZ(-2deg) scale(0.84)',
      zIndex: 15,
      opacity: 0.55,
      pointerEvents: 'auto'
    }
  }

  // Right Card (offset === 1 or first item wrapping right)
  if (offset === 1 || (activeProjectIndex.value === total - 1 && idx === 0 && total > 1)) {
    return {
      transform: 'translate3d(58%, 0, -70px) rotateY(-32deg) rotateZ(2deg) scale(0.84)',
      zIndex: 15,
      opacity: 0.55,
      pointerEvents: 'auto'
    }
  }

  // Hidden / Back Cards
  return {
    transform: 'translate3d(0, 0, -220px) rotateY(180deg) scale(0.65)',
    zIndex: 5,
    opacity: 0,
    pointerEvents: 'none'
  }
}

const handleCoverflowClick = (idx, project) => {
  if (idx === activeProjectIndex.value) {
    emit('open-project', project)
  } else {
    activeProjectIndex.value = idx
    autoFlipProgress.value = 0
  }
}

const goToSlide = (idx) => {
  activeProjectIndex.value = idx
  autoFlipProgress.value = 0
}

const nextSlide = () => {
  if (!filteredProjects.value.length) return
  activeProjectIndex.value = (activeProjectIndex.value + 1) % filteredProjects.value.length
  autoFlipProgress.value = 0
}

const prevSlide = () => {
  if (!filteredProjects.value.length) return
  activeProjectIndex.value = (activeProjectIndex.value - 1 + filteredProjects.value.length) % filteredProjects.value.length
  autoFlipProgress.value = 0
}

const toggleAutoFlip = () => {
  isAutoFlipEnabled.value = !isAutoFlipEnabled.value
  if (!isAutoFlipEnabled.value) {
    autoFlipProgress.value = 0
  }
}

// Unconditional Global Interval with Async Readiness (60ms tick -> 3200ms cycle)
onMounted(() => {
  const TICK_MS = 60
  const CYCLE_DURATION_MS = 3200
  const STEP = (TICK_MS / CYCLE_DURATION_MS) * 100

  autoFlipInterval = setInterval(() => {
    if (
      filteredProjects.value?.length > 1 &&
      !isStageHovered.value &&
      isAutoFlipEnabled.value
    ) {
      autoFlipProgress.value += STEP
      if (autoFlipProgress.value >= 100) {
        autoFlipProgress.value = 0
        activeProjectIndex.value = (activeProjectIndex.value + 1) % filteredProjects.value.length
      }
    }
  }, TICK_MS)
})

onBeforeUnmount(() => {
  if (autoFlipInterval) {
    clearInterval(autoFlipInterval)
    autoFlipInterval = null
  }
})
</script>

<style scoped>
/* Smooth 3D Stage Card Transitions */
.project-card {
  transition: transform 0.75s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.6s ease;
  transform-style: preserve-3d;
  will-change: transform, opacity;
}

/* Physical 3D Flip-In Keyframe Triggered Every Time Card Becomes Active */
.is-active-3d-flip {
  animation: card3DFlipIn 0.72s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes card3DFlipIn {
  0% {
    transform: perspective(1200px) rotateY(-25deg) rotateX(5deg) scale(0.94);
  }
  60% {
    transform: perspective(1200px) rotateY(4deg) rotateX(-1deg) scale(1.01);
  }
  100% {
    transform: perspective(1200px) rotateY(0deg) rotateX(0deg) scale(1);
  }
}

/* Always-On X-Ray Blueprint Laser Sweep (5% <-> 92%) */
@keyframes xrayScanClip {
  0%, 100% { clip-path: inset(0 95% 0 0); }
  50% { clip-path: inset(0 8% 0 0); }
}

@keyframes xrayLaserMove {
  0%, 100% { left: 5%; }
  50% { left: 92%; }
}

.xray-blueprint-layer {
  animation: xrayScanClip 4.2s ease-in-out infinite;
}

.xray-laser-beam {
  animation: xrayLaserMove 4.2s ease-in-out infinite;
}
</style>
