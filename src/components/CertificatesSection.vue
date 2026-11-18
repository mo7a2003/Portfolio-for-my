<template>
  <section id="certificates" class="certificates-section relative py-24 sm:py-32 overflow-hidden">
    <!-- Ambient Background Glows -->
    <div class="pointer-events-none absolute top-1/3 left-1/4 -translate-x-1/2 w-[500px] h-[350px] rounded-full bg-amber-500/10 blur-[150px] z-0"></div>
    <div class="pointer-events-none absolute bottom-1/4 right-1/4 translate-x-1/3 w-[480px] h-[340px] rounded-full bg-[#00f2fe]/10 blur-[150px] z-0"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
      
      <!-- Section Header -->
      <div class="section-header flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 sm:mb-16">
        <div>
          <div class="section-badge inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] text-amber-400 font-mono text-xs tracking-wider uppercase mb-3 shadow-[0_0_20px_rgba(245,158,11,0.15)]">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            <span>{{ t.certSubtitle }}</span>
          </div>
          <h2 class="section-title text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
            {{ t.certTitle }} <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#ff5500] via-amber-400 to-[#00f2fe]">{{ t.certTitleHighlight }}</span>
          </h2>
        </div>

        <div class="flex items-center gap-3 font-mono text-xs">
          <div class="px-3.5 py-2 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center gap-2 shadow-[0_0_20px_rgba(16,185,129,0.15)]">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span class="font-bold">{{ t.certVerified }}</span>
          </div>
        </div>
      </div>

      <!-- 3D Holographic Bento Grid (2 Columns) -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
        <div
          v-for="(cert, idx) in displayCertificates"
          :key="cert.id || idx"
          class="cert-holo-card group relative rounded-3xl bg-[#0b0e17]/95 border border-white/[0.08] hover:border-amber-500/50 p-6 sm:p-7 overflow-hidden transition-all duration-500 hover:-translate-y-1.5 hover:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.85),0_0_35px_rgba(245,158,11,0.18)] cursor-pointer flex flex-col justify-between"
          @click="selectedCertificate = cert"
        >
          <!-- Sweeping Holographic Foil Shimmer Overlay on Hover -->
          <div class="holo-foil-shimmer pointer-events-none absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"></div>

          <!-- Top Accent Line -->
          <div 
            class="absolute top-0 left-0 right-0 h-[2px] opacity-80 group-hover:opacity-100 transition-opacity"
            :style="{ background: `linear-gradient(90deg, transparent, ${cert.accent || '#f59e0b'}, #00f2fe, transparent)` }"
          ></div>

          <!-- Top Telemetry Row: Badge + Verification Hash -->
          <div class="relative z-10">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-5">
              <!-- Honor / Credential Badge -->
              <span 
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono font-bold tracking-wider uppercase border shadow-sm"
                :class="idx === 0 
                  ? 'bg-amber-500/15 border-amber-400/50 text-amber-300 shadow-[0_0_15px_rgba(245,158,11,0.25)]' 
                  : 'bg-[#00f2fe]/12 border-[#00f2fe]/35 text-[#00f2fe]'"
              >
                <span>{{ idx === 0 ? '🏆' : '✦' }}</span>
                <span>{{ getLocalized(cert, 'badge', cert.badge || 'VERIFIED CREDENTIAL') }}</span>
              </span>

              <!-- X-Ray Verification Hash -->
              <span class="px-2.5 py-1 rounded-lg bg-white/[0.03] border border-white/[0.07] font-mono text-[10px] text-slate-400 group-hover:text-emerald-400 group-hover:border-emerald-500/30 transition-colors">
                {{ cert.hash || `ID // VERIFIED-${cert.issue_date || cert.issueDate || cert.year || '2026'}-0${idx + 1}` }}
              </span>
            </div>

            <!-- Icon Emblem + Title & Issuer -->
            <div class="flex items-start gap-4 mb-4">
              <!-- 3D Medal / Shield Emblem -->
              <div 
                class="w-13 h-13 rounded-2xl shrink-0 flex items-center justify-center border border-white/15 bg-gradient-to-br from-white/[0.07] to-white/[0.01] group-hover:scale-110 group-hover:border-amber-400/50 transition-all duration-300 shadow-lg"
                :style="{ boxShadow: `0 8px 24px -6px ${cert.accent || '#f59e0b'}40` }"
              >
                <!-- Award Trophy SVG for 1st Place / Academic Cap / Shield -->
                <svg v-if="idx === 0" class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <svg v-else-if="idx === 1" class="w-6 h-6 text-[#00f2fe]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path d="M12 14l9-5-9-5-9 5 9 5z" />
                  <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
                <svg v-else-if="idx === 2" class="w-6 h-6 text-[#ff5500]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                <svg v-else class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
              </div>

              <div class="min-w-0 flex-1">
                <h3 class="text-lg sm:text-xl font-black text-white group-hover:text-amber-300 transition-colors leading-snug">
                  {{ getLocalized(cert, 'title', cert.title) }}
                </h3>
                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs font-mono text-slate-400">
                  <span class="text-amber-400/90 font-semibold">{{ getLocalized(cert, 'issuer', cert.issuer) }}</span>
                  <span>•</span>
                  <span class="text-[#00f2fe]">// {{ cert.issue_date || cert.issueDate || cert.year || '2026' }}</span>
                </div>
              </div>
            </div>

            <!-- Associated Project Highlight Pill (If present) -->
            <div 
              v-if="cert.project" 
              class="mb-3 inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-white/[0.03] border border-white/[0.07] text-xs font-mono text-slate-300"
            >
              <span class="w-1.5 h-1.5 rounded-full bg-[#ff5500]"></span>
              <span class="text-slate-400">Featured Project:</span>
              <strong class="text-white">{{ cert.project }}</strong>
            </div>

            <p class="text-xs sm:text-sm text-slate-300/85 leading-relaxed mb-5">
              {{ getLocalized(cert, 'description', cert.description) }}
            </p>
          </div>

          <!-- Bottom Row: Skill Tags & Inspect Credential Button -->
          <div class="relative z-10 pt-4 border-t border-white/[0.07] flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-1.5">
              <span
                v-for="tag in (cert.tags || []).slice(0, 3)"
                :key="tag"
                class="px-2.5 py-1 rounded-lg bg-white/[0.03] border border-white/[0.07] font-mono text-[10px] text-slate-300"
              >
                {{ tag }}
              </span>
            </div>

            <button
              type="button"
              @click.stop="selectedCertificate = cert"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gradient-to-r from-amber-500/20 to-[#ff5500]/20 hover:from-[#ff5500] hover:to-amber-500 border border-amber-500/40 text-amber-300 hover:text-slate-950 font-mono font-bold text-xs transition-all duration-300 shadow-[0_0_15px_rgba(245,158,11,0.2)] cursor-pointer shrink-0"
            >
              <span>Inspect Credential</span>
              <span>↗</span>
            </button>
          </div>
        </div>
      </div>

    </div>

    <!-- ======================================================== -->
    <!-- 3D HOLOGRAPHIC CERTIFICATE PREVIEW MODAL                 -->
    <!-- ======================================================== -->
    <Transition name="cert-modal">
      <div
        v-if="selectedCertificate"
        class="fixed inset-0 z-50 bg-[#03050a]/85 backdrop-blur-2xl flex items-center justify-center p-4"
        @click="selectedCertificate = null"
      >
        <div
          class="relative w-full max-w-2xl rounded-3xl bg-[#090d18]/95 border border-amber-500/40 shadow-[0_30px_90px_rgba(0,0,0,0.9),0_0_50px_rgba(245,158,11,0.18)] overflow-hidden p-6 sm:p-8"
          @click.stop
        >
          <!-- Top Glowing Line -->
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#ff5500] via-amber-400 to-[#00f2fe]"></div>

          <!-- Header -->
          <div class="flex items-center justify-between gap-4 pb-4 border-b border-white/[0.08]">
            <div class="flex items-center gap-2">
              <span class="px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/35 text-emerald-400 font-mono text-[10px] font-bold uppercase tracking-wider">
                ● VERIFIED CREDENTIAL
              </span>
              <span class="font-mono text-xs text-slate-400">
                {{ selectedCertificate.hash }}
              </span>
            </div>
            <button
              type="button"
              @click="selectedCertificate = null"
              class="w-8 h-8 rounded-full bg-white/[0.05] hover:bg-red-500/20 border border-white/[0.1] text-slate-300 hover:text-white flex items-center justify-center transition-colors cursor-pointer"
            >
              ✕
            </button>
          </div>

          <!-- Certificate Visual Parchment / Hologram Frame -->
          <div class="my-6 relative rounded-2xl bg-[radial-gradient(circle_at_top,#131b2e_0%,#05070e_100%)] border border-amber-500/30 p-6 sm:p-8 text-center overflow-hidden shadow-inner">
            <!-- Corner HUD Ornaments -->
            <div class="absolute top-3 left-3 w-5 h-5 border-t-2 border-l-2 border-amber-400/60"></div>
            <div class="absolute top-3 right-3 w-5 h-5 border-t-2 border-r-2 border-amber-400/60"></div>
            <div class="absolute bottom-3 left-3 w-5 h-5 border-b-2 border-l-2 border-[#00f2fe]/60"></div>
            <div class="absolute bottom-3 right-3 w-5 h-5 border-b-2 border-r-2 border-[#00f2fe]/60"></div>

            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/15 border border-amber-400/40 text-amber-300 font-mono text-[10px] font-bold uppercase tracking-widest mb-4">
              {{ selectedCertificate.badge }}
            </div>

            <h3 class="text-xl sm:text-2xl font-black text-white tracking-tight mb-2">
              {{ getLocalized(selectedCertificate, 'title', selectedCertificate.title) }}
            </h3>

            <p class="text-xs font-mono text-amber-400 mb-4">
              Issued to <strong class="text-white">{{ isRtl ? 'محمد الحويطي' : 'MOHAMMED ALHWAITI' }}</strong> by {{ getLocalized(selectedCertificate, 'issuer', selectedCertificate.issuer) }} ({{ selectedCertificate.issue_date || selectedCertificate.issueDate || selectedCertificate.year }})
            </p>

            <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed mb-6">
              {{ getLocalized(selectedCertificate, 'description', selectedCertificate.description) }}
            </p>

            <!-- Digital Seal & Signature Row -->
            <div class="pt-5 border-t border-white/[0.08] flex flex-wrap items-center justify-between gap-4 text-left font-mono text-[10px]">
              <div>
                <span class="text-slate-500 block">ISSUING AUTHORITY</span>
                <span class="text-slate-200 font-bold">{{ getLocalized(selectedCertificate, 'issuer', selectedCertificate.issuer) }}</span>
              </div>
              <div v-if="selectedCertificate.project">
                <span class="text-slate-500 block">ASSOCIATED WORK</span>
                <span class="text-amber-300 font-bold">{{ selectedCertificate.project }}</span>
              </div>
              <div class="px-3 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold">
                ✓ AUTHENTICATED SEAL
              </div>
            </div>
          </div>

          <!-- Footer Actions -->
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap gap-1.5">
              <span
                v-for="tag in selectedCertificate.tags"
                :key="tag"
                class="px-2.5 py-1 rounded-lg bg-white/[0.04] border border-white/[0.08] font-mono text-[10px] text-slate-300"
              >
                {{ tag }}
              </span>
            </div>

            <div class="flex items-center gap-2 ml-auto">
              <a
                v-if="selectedCertificate.url"
                :href="selectedCertificate.url"
                target="_blank"
                rel="noopener noreferrer"
                class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#ff5500] to-amber-500 text-slate-950 font-mono font-bold text-xs cursor-pointer"
              >
                Verify External ↗
              </a>
              <button
                type="button"
                @click="selectedCertificate = null"
                class="px-4 py-2 rounded-xl bg-white/[0.06] hover:bg-white/[0.12] border border-white/[0.1] text-white font-mono text-xs cursor-pointer"
              >
                Close Preview
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import { lang, isRtl, t, getLocalized } from '@/services/siteLanguage'

const props = defineProps({
  certificates: {
    type: Array,
    default: () => []
  }
})

const selectedCertificate = ref(null)

const defaultCertificates = [
  {
    id: 'cert-anan-award',
    title: 'Anan Award (جائزة عنان) — Best Graduation Project',
    issuer: 'University College of Applied Sciences (UCAS)',
    year: '2026',
    badge: '1ST PLACE HONOR',
    project: 'WafarCash Smart Platform',
    hash: 'ID // VERIFIED-UCAS-2026-01',
    accent: '#f59e0b',
    description:
      'Awarded 1st Place Honor in the prestigious Anan Innovation & Graduation Award at UCAS for architecting and engineering the WafarCash smart financial & cashback ecosystem.',
    tags: ['FinTech Architecture', 'Full-Stack Engineering', '1st Place Honor', 'WafarCash']
  },
  {
    id: 'cert-ucas-degree',
    title: 'Technical Degree in Website Design & Development',
    issuer: 'University College of Applied Sciences (UCAS)',
    year: '2026',
    badge: 'ACADEMIC DEGREE',
    project: 'Web Engineering & Interactive Systems',
    hash: 'ID // VERIFIED-UCAS-2026-02',
    accent: '#00f2fe',
    description:
      'Academic degree specializing in modern front-end architectures, responsive UI/UX design systems, relational database engineering, and full-stack web application development.',
    tags: ['Web Engineering', 'UI/UX Architecture', 'Full-Stack Development', 'UCAS']
  },
  {
    id: 'cert-taqat-fellowship',
    title: 'AI & PropTech Full-Stack Engineering Fellowship',
    issuer: 'Taqat Academy (طاقات أكاديمي)',
    year: '2026',
    badge: 'FELLOWSHIP CREDENTIAL',
    project: 'VibeLocate AI',
    hash: 'ID // VERIFIED-TAQAT-2026-03',
    accent: '#ff5500',
    description:
      'Intensive engineering fellowship focused on AI-driven spatial platforms and modern full-stack architectures, culminating in the VibeLocate AI smart real estate & PropTech platform.',
    tags: ['AI Integration', 'PropTech GIS', 'Laravel & Vue 3', 'VibeLocate AI']
  },
  {
    id: 'cert-vue-3d-architect',
    title: 'Advanced Vue.js 3, UI/UX Architecture & 3D Web Systems',
    issuer: 'Professional Technical Certification',
    year: '2026',
    badge: 'VERIFIED ARCHITECT',
    project: '60FPS Spatial WebGL & GSAP Systems',
    hash: 'ID // VERIFIED-ARCH-2026-04',
    accent: '#10b981',
    description:
      'Advanced technical specialization in GPU-accelerated 3D web interfaces, Three.js/WebGL shaders, GSAP ScrollTrigger choreography, and dark-luxury glassmorphic design systems.',
    tags: ['Vue 3 Composition API', 'Three.js / WebGL', 'GSAP Motion', 'Tailwind CSS']
  }
]

const displayCertificates = computed(() => {
  const list =
    Array.isArray(props.certificates) && props.certificates.length > 0
      ? props.certificates
      : defaultCertificates
  return list.filter((c) => c.is_active !== false)
})
</script>

<style scoped>
.holo-foil-shimmer {
  background: linear-gradient(
    125deg,
    transparent 20%,
    rgba(245, 158, 11, 0.08) 40%,
    rgba(0, 242, 254, 0.1) 50%,
    rgba(124, 58, 237, 0.08) 60%,
    transparent 80%
  );
  background-size: 200% 200%;
  animation: holoSweep 3.5s linear infinite;
}

@keyframes holoSweep {
  0% {
    background-position: 200% 0%;
  }
  100% {
    background-position: -200% 0%;
  }
}

.cert-modal-enter-active,
.cert-modal-leave-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.cert-modal-enter-from,
.cert-modal-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(15px);
}
</style>
