<template>
  <Transition name="modal-3d">
    <!-- Deep Obsidian Backdrop with Ambient Dual Radial Glows -->
    <div 
      v-if="currentProject" 
      class="fixed inset-0 overflow-hidden bg-[#03050a]/85 backdrop-blur-2xl z-50 flex items-center justify-center p-2.5 sm:p-5 md:p-6"
      @wheel.stop
    >
      <!-- Ambient Radial Glows Behind Modal (Neon Amber Top-Left + Cyber Cyan Bottom-Right) -->
      <div class="pointer-events-none fixed -top-24 -left-24 w-[480px] h-[480px] rounded-full bg-[#f59e0b]/15 blur-[150px] z-0"></div>
      <div class="pointer-events-none fixed -bottom-24 -right-24 w-[480px] h-[480px] rounded-full bg-[#00f2fe]/15 blur-[150px] z-0"></div>

      <!-- Clickable Backdrop Overlay -->
      <div 
        class="fixed inset-0 cursor-pointer z-0"
        @click="closeModal"
      ></div>

      <!-- 3D Command Deck & Bento Case-Study Modal Container -->
      <div 
        class="modal-3d-deck relative z-10 max-w-5xl w-[94vw] max-h-[90vh] flex flex-col rounded-[32px] bg-[#080b14]/95 border border-amber-500/30 shadow-[0_35px_100px_rgba(0,0,0,0.9),0_0_50px_rgba(245,158,11,0.12)] overflow-hidden"
        @click.stop
      >
        
        <!-- ======================================================== -->
        <!-- 1. TOP TELEMETRY HUD BAR (With In-Modal Project Switcher) -->
        <!-- ======================================================== -->
        <div class="shrink-0 border-b border-white/[0.08] bg-white/[0.02] px-5 sm:px-6 py-3.5 flex flex-wrap items-center justify-between gap-3">
          <!-- Left: Category Pill, AI/Featured Badge, Monospace Year -->
          <div class="flex items-center gap-2 sm:gap-2.5 flex-wrap">
            <!-- Glowing Category Pill -->
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] sm:text-[11px] font-mono tracking-wider font-bold uppercase bg-amber-500/12 border border-amber-500/35 text-amber-300 shadow-[0_0_15px_rgba(245,158,11,0.2)]">
              <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
              <span>● {{ currentProject.category || 'PROPTECH / AI & WEB' }}</span>
            </span>

            <!-- Award / AI Powered Badge -->
            <span 
              class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] sm:text-[11px] font-mono tracking-wider font-bold uppercase bg-gradient-to-r from-[#ff5500]/20 via-amber-500/15 to-[#00f2fe]/15 border border-amber-400/35 text-amber-200 shadow-[0_0_16px_rgba(245,158,11,0.2)]"
            >
              <span>✨ {{ currentProject.badge || 'AI POWERED' }}</span>
            </span>

            <!-- Monospace Year -->
            <span class="text-xs font-mono text-slate-400 font-semibold">
              // {{ currentProject.year || '2025' }}
            </span>
          </div>

          <!-- Right: Active System Status + Prev/Next Switcher + Close Button -->
          <div class="flex items-center gap-2 sm:gap-3 ml-auto">
            <!-- Active System Emerald Pulsing Badge -->
            <div class="hidden md:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-[10px] font-mono text-emerald-400 uppercase tracking-wider">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
              <span>● Active System</span>
            </div>

            <!-- In-Modal Prev / Next Project Switcher -->
            <div 
              v-if="projectList.length > 1" 
              class="flex items-center gap-1 p-1 rounded-xl bg-white/[0.03] border border-white/[0.08]"
            >
              <button
                type="button"
                @click="cycleProject(-1)"
                class="px-2.5 py-1 rounded-lg text-[11px] font-mono text-slate-300 hover:text-amber-300 hover:bg-amber-500/15 transition-all cursor-pointer flex items-center gap-1"
                title="Previous Project (Left Arrow)"
              >
                <span>←</span>
                <span class="hidden sm:inline">Prev</span>
              </button>
              <span class="text-[10px] font-mono text-slate-600 px-1">
                {{ currentIndex + 1 }}/{{ projectList.length }}
              </span>
              <button
                type="button"
                @click="cycleProject(1)"
                class="px-2.5 py-1 rounded-lg text-[11px] font-mono text-slate-300 hover:text-[#00f2fe] hover:bg-[#00f2fe]/15 transition-all cursor-pointer flex items-center gap-1"
                title="Next Project (Right Arrow)"
              >
                <span class="hidden sm:inline">Next</span>
                <span>→</span>
              </button>
            </div>

            <!-- Circular Close Button (✕) -->
            <button 
              type="button"
              @click="closeModal"
              class="w-9 h-9 rounded-full bg-white/[0.04] hover:bg-red-500/20 border border-white/[0.1] hover:border-amber-500/60 text-slate-300 hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-[0_0_18px_rgba(245,158,11,0.35)] cursor-pointer group shrink-0"
              aria-label="Close modal"
              title="Close (Esc)"
            >
              <X class="w-4 h-4 transition-transform duration-300 group-hover:rotate-90 group-hover:scale-110" />
            </button>
          </div>
        </div>

        <!-- ======================================================== -->
        <!-- 2. SPLIT-SCREEN 12-COLUMN BENTO COMMAND DECK BODY        -->
        <!-- ======================================================== -->
        <div class="overflow-y-auto custom-scrollbar flex-1 grid grid-cols-1 lg:grid-cols-12 gap-6 p-5 sm:p-7">
          
          <!-- ====================================================== -->
          <!-- LEFT COLUMN (lg:col-span-5): 3D Browser + X-Ray + CTAs -->
          <!-- ====================================================== -->
          <div class="lg:col-span-5 flex flex-col justify-between space-y-4">
            
            <!-- Dark-Luxury 3D Browser Mockup Window (Zero Image Cropping) -->
            <div class="rounded-2xl bg-[#05070e] border border-white/10 overflow-hidden shadow-2xl group relative">
              <!-- Browser Top Bar -->
              <div class="h-8 px-3.5 bg-[#090c15] border-b border-white/[0.07] flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                  <span class="w-2.5 h-2.5 rounded-full bg-red-500/80"></span>
                  <span class="w-2.5 h-2.5 rounded-full bg-amber-400/80"></span>
                  <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                </div>
                <div class="px-2.5 py-0.5 rounded-md bg-[#05070e] border border-white/[0.06] text-[10px] font-mono text-slate-400 truncate max-w-[180px] flex items-center gap-1.5">
                  <span class="text-emerald-400">🔒</span>
                  <span>{{ formattedDomain }}</span>
                </div>
                <span class="px-1.5 py-0.5 rounded bg-[#00f2fe]/10 border border-[#00f2fe]/30 text-[9px] font-mono font-bold text-[#00f2fe]">
                  60FPS
                </span>
              </div>

              <!-- Viewport: Ambient Blur Layer + Uncropped Crisp Asset + Always-On X-Ray Laser -->
              <div class="h-60 sm:h-68 relative flex items-center justify-center p-5 overflow-hidden bg-[#04060c]">
                <!-- Layer 1: Ambient Blurred Background Derived from Project Image -->
                <img 
                  :src="projectImage" 
                  :alt="currentProject.title"
                  aria-hidden="true"
                  class="pointer-events-none absolute inset-0 w-full h-full object-cover blur-2xl opacity-30 scale-125"
                />

                <!-- Subtle Radial Dark Vignette for Contrast -->
                <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_25%,rgba(5,7,14,0.75)_100%)] z-[5]"></div>

                <!-- Layer 2: Crisp Full Uncropped Asset (object-contain) -->
                <img 
                  :key="projectImage"
                  :src="projectImage" 
                  :alt="currentProject.title"
                  @error="(e) => e.target.src = '/projects/wasla-preview.svg'"
                  class="relative z-10 max-h-full max-w-full object-contain rounded-xl drop-shadow-[0_15px_35px_rgba(0,0,0,0.85)] transition-transform duration-500 group-hover:scale-105" 
                />

                <!-- Layer 3: Always-On X-Ray Blueprint Wireframe Grid & Laser Beam -->
                <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,rgba(0,242,254,0.06)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,242,254,0.06)_1px,transparent_1px)] bg-[size:1.75rem_1.75rem] z-20 opacity-60"></div>

                <!-- Corner HUD Brackets -->
                <div class="pointer-events-none absolute top-2.5 left-2.5 w-3.5 h-3.5 border-t-2 border-l-2 border-[#00f2fe]/60 z-20"></div>
                <div class="pointer-events-none absolute top-2.5 right-2.5 w-3.5 h-3.5 border-t-2 border-r-2 border-amber-400/60 z-20"></div>
                <div class="pointer-events-none absolute bottom-2.5 left-2.5 w-3.5 h-3.5 border-b-2 border-l-2 border-amber-400/60 z-20"></div>

                <!-- Asset Preview HUD Tag -->
                <div class="pointer-events-none absolute bottom-2.5 right-2.5 px-2.5 py-1 rounded-lg bg-[#05070e]/85 backdrop-blur-md border border-[#00f2fe]/30 text-[9px] font-mono text-[#00f2fe] z-20 shadow-lg">
                  Asset Preview // 3D Architecture
                </div>

                <!-- Sweeping Vertical Cyan Laser Beam -->
                <div class="xray-laser-beam pointer-events-none absolute top-0 bottom-0 w-[2px] bg-gradient-to-b from-transparent via-[#00f2fe] to-transparent shadow-[0_0_18px_#00f2fe,0_0_6px_#ffffff] z-30"></div>
              </div>
            </div>

            <!-- Mini Telemetry Bento Row (3 Mini Cards Below Image) -->
            <div class="grid grid-cols-3 gap-2.5">
              <div class="p-3 rounded-xl bg-white/[0.025] border border-white/[0.07] flex flex-col justify-between">
                <span class="text-[9px] font-mono uppercase tracking-wider text-slate-500">RENDER</span>
                <span class="text-[11px] font-mono font-bold text-[#00f2fe] mt-1 truncate">GPU 3D / 60FPS</span>
              </div>
              <div class="p-3 rounded-xl bg-white/[0.025] border border-white/[0.07] flex flex-col justify-between">
                <span class="text-[9px] font-mono uppercase tracking-wider text-slate-500">STATUS</span>
                <span class="text-[11px] font-mono font-bold text-emerald-400 mt-1 truncate">
                  {{ currentProject.status || 'Production Ready' }}
                </span>
              </div>
              <div class="p-3 rounded-xl bg-white/[0.025] border border-white/[0.07] flex flex-col justify-between">
                <span class="text-[9px] font-mono uppercase tracking-wider text-slate-500">ARCHITECTURE</span>
                <span class="text-[11px] font-mono font-bold text-amber-300 mt-1 truncate">Full-Stack / API</span>
              </div>
            </div>

            <!-- Primary Action Buttons (Directly Under Left Column) -->
            <div class="flex flex-col sm:flex-row items-stretch gap-3 pt-1">
              <!-- Live Preview CTA -->
              <a
                v-if="livePreviewUrl"
                :href="livePreviewUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-gradient-to-r from-[#ff5500] via-amber-500 to-[#f59e0b] hover:from-amber-400 hover:to-[#ff5500] text-slate-950 font-extrabold text-xs tracking-wider uppercase font-mono shadow-[0_0_28px_rgba(255,85,0,0.4)] hover:shadow-[0_0_40px_rgba(245,158,11,0.65)] hover:-translate-y-0.5 transition-all cursor-pointer"
              >
                <span>LIVE PREVIEW</span>
                <ExternalLink class="w-3.5 h-3.5 stroke-[2.5]" />
              </a>
              <div
                v-else
                class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-300 font-bold text-xs tracking-wider uppercase font-mono select-none"
              >
                <span>INTERNAL / PRIVATE DEPLOYMENT</span>
              </div>

              <!-- Repository / Source CTA -->
              <a
                v-if="repoSourceUrl"
                :href="repoSourceUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/[0.12] hover:border-[#00f2fe]/50 text-slate-200 hover:text-white font-mono text-xs font-semibold transition-all hover:-translate-y-0.5 cursor-pointer"
              >
                <Github class="w-4 h-4 text-[#00f2fe]" />
                <span>Repository</span>
              </a>
            </div>

          </div>

          <!-- ====================================================== -->
          <!-- RIGHT COLUMN (lg:col-span-7): Case Study & Cyber-Pills -->
          <!-- ====================================================== -->
          <div class="lg:col-span-7 flex flex-col justify-between space-y-5">
            
            <!-- Header Block: Monumental Title & Gradient Subtitle -->
            <div class="space-y-2">
              <h2 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                {{ currentProject.title }}
              </h2>
              <p class="text-xs sm:text-sm font-mono font-semibold bg-clip-text text-transparent bg-gradient-to-r from-amber-300 via-amber-400 to-[#00f2fe]">
                {{ currentProject.subtitle || currentProject.tagline || 'AI-Powered Smart Architecture & High-Performance Web Platform' }}
              </p>
            </div>

            <!-- Overview & Architecture Bento Card -->
            <div class="rounded-2xl bg-white/[0.025] border border-white/[0.08] p-5 relative overflow-hidden">
              <!-- Glowing Left Accent Bar -->
              <div class="absolute top-0 bottom-0 left-0 w-1 bg-gradient-to-b from-amber-400 via-[#ff5500] to-[#00f2fe]"></div>

              <div class="pl-2 space-y-2.5">
                <div class="flex items-center justify-between gap-2">
                  <span class="text-xs font-mono uppercase tracking-wider text-amber-400 font-bold flex items-center gap-2">
                    <span>▣ OVERVIEW &amp; ARCHITECTURE</span>
                  </span>
                  <span class="text-[10px] font-mono text-slate-500">// CASE_STUDY_SPEC</span>
                </div>
                <p class="text-sm sm:text-[15px] leading-relaxed text-slate-300 font-sans">
                  {{ currentProject.description || currentProject.tagline || 'Engineered with a modular reactive architecture, combining GPU-accelerated visual polish with clean backend API integration.' }}
                </p>
              </div>
            </div>

            <!-- System Highlights / Key Pillars Row (2-Column Mini Bento) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div 
                v-for="(pillar, pIdx) in displayHighlights" 
                :key="pIdx"
                class="p-3.5 rounded-2xl bg-white/[0.02] border border-white/[0.07] hover:border-amber-500/30 transition-colors flex items-start gap-3"
              >
                <span 
                  class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 font-mono text-xs font-bold border"
                  :class="pIdx % 2 === 0 
                    ? 'bg-amber-500/15 border-amber-500/35 text-amber-300' 
                    : 'bg-[#00f2fe]/12 border-[#00f2fe]/35 text-[#00f2fe]'"
                >
                  {{ pIdx % 2 === 0 ? '⚡' : '🔒' }}
                </span>
                <div class="min-w-0">
                  <div class="text-[10px] font-mono uppercase tracking-wider text-slate-500">
                    {{ pIdx % 2 === 0 ? 'REACTIVE ENGINE' : 'SYSTEM PIPELINE' }}
                  </div>
                  <div class="text-xs font-semibold text-slate-200 mt-0.5 leading-snug">
                    {{ pillar }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Optional Metrics Row if Project has Custom Metrics -->
            <div v-if="projectMetrics.length" class="grid grid-cols-3 gap-2.5">
              <div 
                v-for="m in projectMetrics.slice(0, 3)" 
                :key="m.label" 
                class="p-3 rounded-xl bg-white/[0.02] border border-white/[0.07] text-center"
              >
                <div class="text-base sm:text-lg font-black font-mono text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-[#00f2fe]">
                  {{ m.value }}
                </div>
                <div class="text-[10px] text-slate-400 font-mono truncate">
                  {{ m.label }}
                </div>
              </div>
            </div>

            <!-- Technologies & Architecture Stack (Numbered Cyber-Capsules) -->
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <h4 class="text-xs font-mono uppercase tracking-wider text-slate-300 font-bold flex items-center gap-2">
                  <Layers class="w-3.5 h-3.5 text-amber-400" />
                  <span>TECHNOLOGIES &amp; ARCHITECTURE STACK</span>
                </h4>
                <span class="text-[10px] font-mono text-[#00f2fe]">
                  {{ parsedTechStack.length }} MODULES
                </span>
              </div>

              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                <div
                  v-for="(tech, tIdx) in parsedTechStack"
                  :key="tech"
                  class="group/tech flex items-center justify-between gap-2 px-3 py-2 rounded-xl bg-[#0c101d]/90 border border-white/[0.08] hover:border-amber-500/50 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_8px_20px_-6px_rgba(245,158,11,0.25)] select-none"
                >
                  <div class="flex items-center gap-2 min-w-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 group-hover/tech:bg-[#00f2fe] shadow-[0_0_8px_#f59e0b] shrink-0 transition-colors"></span>
                    <span class="text-xs font-mono font-bold text-slate-200 group-hover/tech:text-white truncate">
                      {{ tech }}
                    </span>
                  </div>
                  <span class="text-[10px] font-mono text-white/25 group-hover/tech:text-amber-400/80 shrink-0">
                    {{ String(tIdx + 1).padStart(2, '0') }}
                  </span>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- ======================================================== -->
        <!-- 3. BOTTOM STATUS FOOTER BAR                              -->
        <!-- ======================================================== -->
        <div class="shrink-0 px-6 py-3 bg-[#05070e]/90 border-t border-white/[0.06] flex items-center justify-between text-xs font-mono text-slate-400">
          <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-[#00f2fe]"></span>
            <span class="text-[11px] tracking-wider text-slate-400">SYS_ID // PROJECT_INSPECTOR_V22</span>
          </div>

          <button
            type="button"
            @click="closeModal"
            class="px-3 py-1 rounded-lg bg-white/[0.03] hover:bg-white/[0.08] border border-white/[0.07] text-[11px] font-mono text-slate-300 hover:text-amber-300 transition-colors cursor-pointer"
          >
            Press [ESC] to close
          </button>
        </div>

      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'
import { X, ExternalLink, Github, Layers } from 'lucide-vue-next'
import { resolveAssetUrl } from '../services/portfolioService'
import { projects as defaultProjects } from '../data/portfolioData'

const props = defineProps({
  project: {
    type: Object,
    default: null
  },
  projects: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['close', 'select-project'])

// Internal override so Prev/Next works even if parent doesn't bind @select-project
const internalProject = ref(null)

watch(
  () => props.project,
  (newVal) => {
    internalProject.value = newVal
    if (typeof document !== 'undefined') {
      document.body.style.overflow = newVal ? 'hidden' : ''
    }
  },
  { immediate: true }
)

const currentProject = computed(() => internalProject.value || props.project)

const projectList = computed(() => {
  return Array.isArray(props.projects) && props.projects.length > 0
    ? props.projects
    : defaultProjects
})

const currentIndex = computed(() => {
  if (!currentProject.value || !projectList.value.length) return 0
  const idx = projectList.value.findIndex(
    (p) =>
      (p.id && currentProject.value.id && p.id === currentProject.value.id) ||
      (p.title && currentProject.value.title && p.title === currentProject.value.title)
  )
  return idx >= 0 ? idx : 0
})

const cycleProject = (step) => {
  const list = projectList.value
  if (!list.length) return
  const nextIdx = (currentIndex.value + step + list.length) % list.length
  const nextProj = list[nextIdx]
  internalProject.value = nextProj
  emit('select-project', nextProj)
}

const closeModal = () => {
  internalProject.value = null
  if (typeof document !== 'undefined') {
    document.body.style.overflow = ''
  }
  emit('close')
}

const projectImage = computed(() => {
  if (!currentProject.value) return '/projects/wasla-preview.svg'
  const raw =
    currentProject.value.image ||
    currentProject.value.thumbnail_url ||
    currentProject.value.thumbnail ||
    currentProject.value.previewImage ||
    currentProject.value.preview_image ||
    ''
  return resolveAssetUrl(raw) || '/projects/wasla-preview.svg'
})

const formattedDomain = computed(() => {
  if (!currentProject.value) return 'app.alhwaiti.dev'
  const url = currentProject.value.liveUrl || currentProject.value.live_url
  if (url) {
    return url.replace(/^https?:\/\//, '').replace(/\/+$/, '')
  }
  const slug = (currentProject.value.title || 'project')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
  return `${slug}.alhwaiti.dev`
})

const livePreviewUrl = computed(() => {
  if (!currentProject.value) return ''
  return currentProject.value.liveUrl || currentProject.value.live_url || ''
})

const repoSourceUrl = computed(() => {
  if (!currentProject.value) return ''
  return (
    currentProject.value.githubUrl ||
    currentProject.value.github_url ||
    currentProject.value.repoUrl ||
    currentProject.value.repo_url ||
    ''
  )
})

const parsedTechStack = computed(() => {
  if (!currentProject.value) return []
  const tech =
    currentProject.value.tech ||
    currentProject.value.technologies ||
    currentProject.value.tech_stack ||
    []
  if (Array.isArray(tech) && tech.length) return tech
  if (typeof tech === 'string') {
    try {
      const parsed = JSON.parse(tech)
      if (Array.isArray(parsed) && parsed.length) return parsed
    } catch {
      const split = tech.split(',').map((s) => s.trim()).filter(Boolean)
      if (split.length) return split
    }
  }
  return ['Vue 3', 'Tailwind CSS', 'REST API', 'Responsive UI']
})

const projectMetrics = computed(() => {
  if (!currentProject.value) return []
  let metrics = currentProject.value.metrics || []
  if (typeof metrics === 'string') {
    try {
      metrics = JSON.parse(metrics)
    } catch {
      metrics = []
    }
  }
  return Array.isArray(metrics) ? metrics : []
})

const projectFeatures = computed(() => {
  if (!currentProject.value) return []
  let features = currentProject.value.features || []
  if (typeof features === 'string') {
    try {
      features = JSON.parse(features)
    } catch {
      features = []
    }
  }
  return Array.isArray(features) ? features : []
})

const displayHighlights = computed(() => {
  if (projectFeatures.value.length >= 2) {
    return projectFeatures.value.slice(0, 2)
  }
  return [
    'High-Performance Reactive UI & 60FPS Spatial Motion',
    'Scalable Backend API & Real-Time Data Pipeline'
  ]
})

const handleKeyDown = (e) => {
  if (!currentProject.value) return
  if (e.key === 'Escape') {
    closeModal()
  } else if (e.key === 'ArrowRight') {
    cycleProject(1)
  } else if (e.key === 'ArrowLeft') {
    cycleProject(-1)
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown)
})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', handleKeyDown)
  if (typeof document !== 'undefined') {
    document.body.style.overflow = ''
  }
})
</script>

<style scoped>
/* 3D Unfold Modal Transition */
.modal-3d-enter-active {
  transition: opacity 0.38s cubic-bezier(0.16, 1, 0.3, 1);
}
.modal-3d-leave-active {
  transition: opacity 0.25s ease-in;
}
.modal-3d-enter-from,
.modal-3d-leave-to {
  opacity: 0;
}

.modal-3d-enter-active .modal-3d-deck {
  transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.38s ease-out;
}
.modal-3d-leave-active .modal-3d-deck {
  transition: transform 0.25s ease-in, opacity 0.22s ease-in;
}
.modal-3d-enter-from .modal-3d-deck,
.modal-3d-leave-to .modal-3d-deck {
  opacity: 0;
  transform: perspective(1400px) rotateX(14deg) translateY(35px) scale(0.92);
}

/* Sweeping Vertical Cyan Laser Beam */
@keyframes modalXrayLaser {
  0%, 100% {
    left: 6%;
  }
  50% {
    left: 92%;
  }
}

.xray-laser-beam {
  animation: modalXrayLaser 4.2s ease-in-out infinite;
}
</style>
