<template>
  <div 
    ref="containerRef" 
    class="relative w-full h-[500px] sm:h-[580px] lg:h-[640px] flex items-center justify-center select-none"
    style="perspective: 1200px;"
    @mousemove="handleContainerMouseMove"
    @mouseleave="handleContainerMouseLeave"
  >
    <!-- 1. Neon Eclipse Atmospheric Halo (#ff5500 to #f59e0b & #00f2fe) -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
      <!-- Outer Fiery Eclipse Corona -->
      <div 
        class="hero-eclipse-ring absolute w-72 sm:w-96 md:w-[450px] aspect-square rounded-full bg-gradient-to-tr from-[#ff5500]/30 via-amber-500/20 to-[#7c3aed]/25 blur-[95px] portal-pulse-gold"
      ></div>
      <!-- Inner Concentrated Solar Rim Glow -->
      <div 
        class="absolute w-60 sm:w-80 md:w-[370px] aspect-square rounded-full bg-[#ff5500]/25 blur-[65px] portal-pulse-cyan"
      ></div>
      <!-- Deep Ground Shadow -->
      <div class="absolute bottom-6 w-72 h-28 rounded-full bg-[#05060b]/90 blur-2xl"></div>
    </div>

    <!-- 2. Rotating Dashed SVG Telemetry Ring & Orbital Tracks -->
    <div class="hero-eclipse-ring absolute inset-0 flex items-center justify-center pointer-events-none z-1">
      <svg 
        class="w-[360px] sm:w-[460px] md:w-[520px] aspect-square orbit-spin-slow opacity-85" 
        viewBox="0 0 520 520" 
        fill="none"
      >
        <defs>
          <linearGradient id="eclipseGrad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#ff5500" stop-opacity="0.9" />
            <stop offset="50%" stop-color="#f59e0b" stop-opacity="0.6" />
            <stop offset="100%" stop-color="#00f2fe" stop-opacity="0.8" />
          </linearGradient>
        </defs>
        <!-- Outer Telemetry Dashed Ring -->
        <circle cx="260" cy="260" r="248" stroke="url(#eclipseGrad)" stroke-width="1.5" stroke-dasharray="8 14" />
        <!-- Middle Precision Arc -->
        <circle cx="260" cy="260" r="226" stroke="#ff5500" stroke-opacity="0.28" stroke-width="1" stroke-dasharray="40 20 10 20" />
        <!-- Cardinal Telemetry Nodes -->
        <circle cx="260" cy="12" r="4" fill="#ff5500" />
        <circle cx="260" cy="508" r="3.5" fill="#f59e0b" />
        <circle cx="508" cy="260" r="4" fill="#00f2fe" />
        <circle cx="12" cy="260" r="3.5" fill="#7c3aed" />
      </svg>

      <!-- Counter-Rotating Inner Cyber Ring -->
      <div class="absolute w-[300px] sm:w-[380px] md:w-[430px] aspect-square rounded-full border border-dotted border-[#00f2fe]/35 orbit-spin-reverse flex items-center justify-center">
        <span class="absolute right-0 top-1/2 translate-x-1/2 -translate-y-1/2 w-2.5 h-2.5 rounded-full bg-[#00f2fe] shadow-[0_0_12px_#00f2fe]"></span>
        <span class="absolute left-0 top-1/2 -translate-x-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-[#ff5500] shadow-[0_0_12px_#ff5500]"></span>
      </div>
    </div>

    <!-- 3. Local Constellation Particle Canvas -->
    <canvas 
      ref="localCanvasRef" 
      class="absolute inset-0 w-full h-full pointer-events-none z-2 opacity-60"
    ></canvas>

    <!-- 4. Central 3D Neon Eclipse Portrait & Orbiting Glass HUD Cards -->
    <div 
      class="relative z-10 flex items-center justify-center group"
      :style="tiltStyle"
    >
      <!-- Neon Eclipse Ring Frame -->
      <div 
        class="hero-portrait-core relative w-64 h-64 sm:w-80 sm:h-80 md:w-92 md:h-92 lg:w-[390px] lg:h-[390px] rounded-full p-[3px] bg-gradient-to-tr from-[#ff5500] via-amber-400 to-[#00f2fe] shadow-[0_0_65px_rgba(255,85,0,0.45),_0_0_120px_rgba(245,158,11,0.22),_inset_0_0_30px_rgba(255,85,0,0.4)] backdrop-blur-2xl transition-all duration-500 float-levitate flex items-center justify-center cursor-pointer"
        style="transform-style: preserve-3d; will-change: transform;"
        @click="triggerPulse"
      >
        <!-- Eclipse Inner Rim Specular Ring -->
        <div class="absolute inset-1.5 rounded-full border border-amber-300/35 pointer-events-none z-20"></div>

        <!-- Inner Circular Viewport -->
        <div class="relative w-full h-full rounded-full bg-gradient-to-b from-[#090b14] via-[#05060b] to-[#05060b] overflow-hidden flex items-end justify-center border border-white/10 shadow-inner">
          <!-- Top Eclipse Corona Flare -->
          <div class="absolute top-0 inset-x-0 h-32 bg-gradient-to-b from-[#ff5500]/25 via-amber-500/10 to-transparent pointer-events-none z-10"></div>

          <!-- Laser Scan Hologram Sweep -->
          <div class="laser-scanner pointer-events-none z-20"></div>

          <!-- High-Res Portrait -->
          <img 
            :src="currentPortrait" 
            :alt="currentName" 
            class="portrait-mask-fade w-full h-full object-contain object-bottom filter drop-shadow-[0_20px_35px_rgba(0,0,0,0.95)] transition-transform duration-500 ease-out group-hover:scale-105"
            :style="portraitParallaxStyle"
            @error="(e) => e.target.src = '/developer-portrait.jpg'"
          />

          <!-- Soft Bottom Eclipse Horizon Fade -->
          <div class="absolute bottom-0 inset-x-0 h-24 sm:h-28 bg-gradient-to-t from-[#05060b] via-[#05060b]/85 to-transparent pointer-events-none z-15"></div>

          <!-- Click Energy Wave Pulse -->
          <div 
            v-if="isPulsing"
            class="absolute inset-0 rounded-full border-2 border-[#ff5500] animate-ping pointer-events-none z-30 opacity-75"
          ></div>
        </div>

        <!-- Bottom Telemetry Identity Badge -->
        <div class="absolute -bottom-4 inset-x-0 flex justify-center z-25 pointer-events-none">
          <span class="px-4 py-1.5 rounded-full bg-[#05060b]/95 border border-[#ff5500]/50 text-amber-300 font-mono text-[10px] sm:text-[11px] tracking-widest uppercase backdrop-blur-xl shadow-[0_0_25px_rgba(255,85,0,0.35)] flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>{{ currentName }} ✦ 3D ARCHITECT</span>
          </span>
        </div>
      </div>

      <!-- ======================================================== -->
      <!-- 5. 3 Floating Glass HUD Cards at Different Z-Depths       -->
      <!-- ======================================================== -->

      <!-- HUD CARD 1: Top-Left (</> Front-End & Vue 3) -->
      <div 
        class="hero-hud-card absolute -top-4 sm:top-2 -left-4 sm:-left-12 lg:-left-16 z-30 cursor-pointer pointer-events-auto group/hud"
        :style="orbWaslaStyle"
        @click.stop="signatureProjects.wasla ? handleOrbClick('wasla', 880) : triggerPulse()"
        @mouseenter="handleOrbHover(880)"
      >
        <div class="hud-float-1 px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-2xl backdrop-blur-xl bg-[#090b14]/85 border border-white/[0.12] group-hover/hud:border-[#ff5500]/60 shadow-[0_15px_35px_rgba(0,0,0,0.6),_0_0_20px_rgba(255,85,0,0.2)] transition-all duration-300 flex items-center gap-3">
          <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-[#ff5500]/25 to-amber-500/10 border border-[#ff5500]/40 flex items-center justify-center text-amber-300 font-mono font-bold text-xs shadow-[0_0_15px_rgba(255,85,0,0.3)]">
            &lt;/&gt;
          </div>
          <div class="text-left">
            <div class="flex items-center gap-1.5">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
              <span class="text-[9px] font-mono uppercase tracking-wider text-slate-400">CORE ENGINE</span>
            </div>
            <p class="text-xs sm:text-sm font-bold text-white group-hover/hud:text-amber-300 transition-colors">
              Front-End &amp; Vue 3
            </p>
          </div>
        </div>
      </div>

      <!-- HUD CARD 2: Top-Right (AI & 3D Web) -->
      <div 
        class="hero-hud-card absolute top-6 sm:top-10 -right-4 sm:-right-12 lg:-right-16 z-30 cursor-pointer pointer-events-auto group/hud"
        :style="orbWafarcashStyle"
        @click.stop="signatureProjects.wafarcash ? handleOrbClick('wafarcash', 987) : triggerPulse()"
        @mouseenter="handleOrbHover(987)"
      >
        <div class="hud-float-2 px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-2xl backdrop-blur-xl bg-[#090b14]/85 border border-white/[0.12] group-hover/hud:border-[#00f2fe]/60 shadow-[0_15px_35px_rgba(0,0,0,0.6),_0_0_20px_rgba(0,242,254,0.2)] transition-all duration-300 flex items-center gap-3">
          <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-[#00f2fe]/25 to-blue-500/10 border border-[#00f2fe]/40 flex items-center justify-center text-[#00f2fe] font-mono font-bold text-xs shadow-[0_0_15px_rgba(0,242,254,0.3)]">
            3D
          </div>
          <div class="text-left">
            <div class="flex items-center gap-1.5">
              <span class="w-1.5 h-1.5 rounded-full bg-[#00f2fe] animate-pulse"></span>
              <span class="text-[9px] font-mono uppercase tracking-wider text-slate-400">WEBGL SPATIAL</span>
            </div>
            <p class="text-xs sm:text-sm font-bold text-white group-hover/hud:text-[#00f2fe] transition-colors">
              AI &amp; 3D Web
            </p>
          </div>
        </div>
      </div>

      <!-- HUD CARD 3: Bottom-Left ({ } UI/UX Architecture) -->
      <div 
        class="hero-hud-card absolute bottom-6 sm:bottom-10 -left-4 sm:-left-10 lg:-left-14 z-30 cursor-pointer pointer-events-auto group/hud"
        :style="orbVibelocateStyle"
        @click.stop="signatureProjects.vibelocate ? handleOrbClick('vibelocate', 1046) : triggerPulse()"
        @mouseenter="handleOrbHover(1046)"
      >
        <div class="hud-float-3 px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-2xl backdrop-blur-xl bg-[#090b14]/85 border border-white/[0.12] group-hover/hud:border-[#7c3aed]/60 shadow-[0_15px_35px_rgba(0,0,0,0.6),_0_0_20px_rgba(124,58,237,0.25)] transition-all duration-300 flex items-center gap-3">
          <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-[#7c3aed]/25 to-purple-500/10 border border-[#7c3aed]/40 flex items-center justify-center text-purple-300 font-mono font-bold text-xs shadow-[0_0_15px_rgba(124,58,237,0.3)]">
            { }
          </div>
          <div class="text-left">
            <div class="flex items-center gap-1.5">
              <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
              <span class="text-[9px] font-mono uppercase tracking-wider text-slate-400">DESIGN SYSTEMS</span>
            </div>
            <p class="text-xs sm:text-sm font-bold text-white group-hover/hud:text-purple-300 transition-colors">
              UI/UX Architecture
            </p>
          </div>
        </div>
      </div>

      <!-- Dynamic Active Project Quick-Orb (Bottom-Right) -->
      <div 
        v-if="signatureProjects.wasla"
        class="hero-hud-card absolute bottom-8 sm:bottom-12 -right-2 sm:-right-8 lg:-right-12 z-30 cursor-pointer pointer-events-auto group/orb"
        :style="orbLumiereStyle"
        @click.stop="handleOrbClick('wasla', 1174)"
        @mouseenter="handleOrbHover(1174)"
        :title="`Preview ${signatureProjects.wasla.title}`"
      >
        <div class="hud-float-2 px-3 py-2 rounded-2xl backdrop-blur-xl bg-[#090b14]/90 border border-amber-500/40 shadow-[0_0_25px_rgba(245,158,11,0.25)] flex items-center gap-2.5 group-hover/orb:scale-105 transition-transform">
          <div class="w-8 h-8 rounded-xl overflow-hidden border border-amber-400/50 bg-[#05060b] shrink-0">
            <img 
              :src="signatureProjects.wasla.image || signatureProjects.wasla.thumbnail || '/projects/wasla-preview.svg'" 
              :alt="signatureProjects.wasla.title"
              class="w-full h-full object-cover"
              @error="(e) => e.target.src = '/projects/wasla-preview.svg'"
            />
          </div>
          <div class="pr-1">
            <span class="text-[9px] font-mono text-amber-400 block uppercase">FEATURED CASE</span>
            <span class="text-xs font-bold text-white truncate max-w-[95px] block">{{ signatureProjects.wasla.title }}</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onBeforeUnmount } from 'vue'
import { personalInfo } from '@/data/portfolioData'
import { profileState, resolveProfilePortrait } from '@/services/profileState'
import gsap from 'gsap'

const props = defineProps({
  projects: {
    type: Array,
    default: () => []
  },
  profile: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['open-project'])

// Dynamic Signature Project References from Database
const signatureProjects = computed(() => {
  const list = Array.isArray(props.projects) ? props.projects : []
  return {
    wasla: list[0] || null,
    wafarcash: list[1] || null,
    vibelocate: list[2] || null,
    lumiere: list[3] || null
  }
})

const currentPortrait = computed(() => {
  return resolveProfilePortrait({
    ...profileState,
    ...(props.profile || {})
  })
})
const currentName = computed(() => props.profile?.name || profileState.name || personalInfo.name || 'MOHAMMED ALHWAITI')

const containerRef = ref(null)
const localCanvasRef = ref(null)
const isPulsing = ref(false)

// Web Audio API Synthesizer for Micro-SFX
let audioCtx = null
const playOrbSound = (frequency = 880) => {
  try {
    if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)()
    if (audioCtx.state === 'suspended') audioCtx.resume()

    const osc = audioCtx.createOscillator()
    const gain = audioCtx.createGain()

    osc.type = 'sine'
    osc.frequency.setValueAtTime(frequency, audioCtx.currentTime)
    osc.frequency.exponentialRampToValueAtTime(frequency * 1.5, audioCtx.currentTime + 0.12)

    gain.gain.setValueAtTime(0.045, audioCtx.currentTime)
    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.12)

    osc.connect(gain)
    gain.connect(audioCtx.destination)

    osc.start()
    osc.stop(audioCtx.currentTime + 0.12)
  } catch (e) {
    // Ignore audio errors
  }
}

const handleOrbHover = (frequency) => {
  playOrbSound(frequency * 0.9)
}

const handleOrbClick = (projectKey, frequency) => {
  playOrbSound(frequency)
  const project = signatureProjects.value ? signatureProjects.value[projectKey] : null
  if (project) {
    emit('open-project', project)
  }
}

// 3D Tilt & Layered Depth Reactive State
const tiltState = reactive({
  rotateX: 0,
  rotateY: 0
})

const tiltStyle = ref({
  transform: 'perspective(1200px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)',
  transition: 'transform 500ms cubic-bezier(0.16, 1, 0.3, 1)'
})

const portraitParallaxStyle = ref({
  transform: 'translate3d(0px, 0px, 35px)',
  transition: 'transform 500ms cubic-bezier(0.16, 1, 0.3, 1)'
})

// Decoupled 3D Parallax Styles for Each HUD Card at Different Z-Depths
const orbWaslaStyle = ref({
  transform: 'translate3d(0px, 0px, 80px)',
  transition: 'transform 500ms cubic-bezier(0.16, 1, 0.3, 1)'
})

const orbWafarcashStyle = ref({
  transform: 'translate3d(0px, 0px, 65px)',
  transition: 'transform 500ms cubic-bezier(0.16, 1, 0.3, 1)'
})

const orbVibelocateStyle = ref({
  transform: 'translate3d(0px, 0px, 55px)',
  transition: 'transform 500ms cubic-bezier(0.16, 1, 0.3, 1)'
})

const orbLumiereStyle = ref({
  transform: 'translate3d(0px, 0px, 70px)',
  transition: 'transform 500ms cubic-bezier(0.16, 1, 0.3, 1)'
})

const handleContainerMouseMove = (e) => {
  if (!containerRef.value) return
  const rect = containerRef.value.getBoundingClientRect()
  const x = e.clientX - rect.left
  const y = e.clientY - rect.top

  const centerX = rect.width / 2
  const centerY = rect.height / 2

  const maxTilt = 12
  const targetRotX = ((y - centerY) / centerY) * -maxTilt
  const targetRotY = ((x - centerX) / centerX) * maxTilt

  gsap.to(tiltState, {
    rotateX: targetRotX,
    rotateY: targetRotY,
    duration: 0.35,
    ease: 'power2.out',
    overwrite: 'auto',
    onUpdate: () => {
      tiltStyle.value = {
        transform: `perspective(1200px) rotateX(${tiltState.rotateX.toFixed(2)}deg) rotateY(${tiltState.rotateY.toFixed(2)}deg) scale3d(1.02, 1.02, 1.02)`
      }
      portraitParallaxStyle.value = {
        transform: `translate3d(${(tiltState.rotateY * 1.2).toFixed(2)}px, ${(-tiltState.rotateX * 0.9).toFixed(2)}px, 45px)`
      }
      orbWaslaStyle.value = {
        transform: `translate3d(${(-tiltState.rotateY * 1.8).toFixed(2)}px, ${(tiltState.rotateX * 1.4).toFixed(2)}px, 85px)`
      }
      orbWafarcashStyle.value = {
        transform: `translate3d(${(tiltState.rotateY * 1.6).toFixed(2)}px, ${(tiltState.rotateX * 1.2).toFixed(2)}px, 70px)`
      }
      orbVibelocateStyle.value = {
        transform: `translate3d(${(-tiltState.rotateY * 1.4).toFixed(2)}px, ${(-tiltState.rotateX * 1.4).toFixed(2)}px, 60px)`
      }
      orbLumiereStyle.value = {
        transform: `translate3d(${(tiltState.rotateY * 1.7).toFixed(2)}px, ${(-tiltState.rotateX * 1.3).toFixed(2)}px, 75px)`
      }
    }
  })

  localMouse.x = x
  localMouse.y = y
  localMouse.active = true
}

const handleContainerMouseLeave = () => {
  gsap.to(tiltState, {
    rotateX: 0,
    rotateY: 0,
    duration: 0.8,
    ease: 'power3.out',
    overwrite: 'auto',
    onUpdate: () => {
      tiltStyle.value = {
        transform: `perspective(1200px) rotateX(${tiltState.rotateX.toFixed(2)}deg) rotateY(${tiltState.rotateY.toFixed(2)}deg) scale3d(1, 1, 1)`
      }
      portraitParallaxStyle.value = {
        transform: 'translate3d(0px, 0px, 35px)'
      }
      orbWaslaStyle.value = {
        transform: 'translate3d(0px, 0px, 80px)'
      }
      orbWafarcashStyle.value = {
        transform: 'translate3d(0px, 0px, 65px)'
      }
      orbVibelocateStyle.value = {
        transform: 'translate3d(0px, 0px, 55px)'
      }
      orbLumiereStyle.value = {
        transform: 'translate3d(0px, 0px, 70px)'
      }
    }
  })

  localMouse.active = false
}

const triggerPulse = () => {
  isPulsing.value = true
  setTimeout(() => {
    isPulsing.value = false
  }, 700)
}

// Local Constellation Canvas Engine
let animId = null
const localMouse = { x: -100, y: -100, active: false }

const initConstellation = () => {
  const canvas = localCanvasRef.value
  if (!canvas || !containerRef.value) return

  const ctx = canvas.getContext('2d')
  let width = (canvas.width = containerRef.value.clientWidth)
  let height = (canvas.height = containerRef.value.clientHeight)

  const numParticles = 30
  const particles = []

  for (let i = 0; i < numParticles; i++) {
    particles.push({
      x: Math.random() * width,
      y: Math.random() * height,
      vx: (Math.random() - 0.5) * 0.45,
      vy: (Math.random() - 0.5) * 0.45,
      r: Math.random() * 1.8 + 0.7,
      color: i % 3 === 0 ? '#ff5500' : i % 2 === 0 ? '#f59e0b' : '#00f2fe'
    })
  }

  const render = () => {
    ctx.clearRect(0, 0, width, height)

    for (let i = 0; i < particles.length; i++) {
      const p = particles[i]
      p.x += p.vx
      p.y += p.vy

      if (p.x < 0 || p.x > width) p.vx *= -1
      if (p.y < 0 || p.y > height) p.vy *= -1

      ctx.beginPath()
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2)
      ctx.fillStyle = p.color
      ctx.globalAlpha = 0.65
      ctx.fill()

      for (let j = i + 1; j < particles.length; j++) {
        const p2 = particles[j]
        const dx = p.x - p2.x
        const dy = p.y - p2.y
        const dist = Math.sqrt(dx * dx + dy * dy)
        if (dist < 105) {
          ctx.beginPath()
          ctx.moveTo(p.x, p.y)
          ctx.lineTo(p2.x, p2.y)
          ctx.strokeStyle = '#f59e0b'
          ctx.globalAlpha = (1 - dist / 105) * 0.16
          ctx.lineWidth = 0.6
          ctx.stroke()
        }
      }
    }

    animId = requestAnimationFrame(render)
  }

  render()
}

const handleResize = () => {
  if (!localCanvasRef.value || !containerRef.value) return
  localCanvasRef.value.width = containerRef.value.clientWidth
  localCanvasRef.value.height = containerRef.value.clientHeight
}

onMounted(() => {
  initConstellation()
  window.addEventListener('resize', handleResize, { passive: true })
})

onBeforeUnmount(() => {
  if (animId) cancelAnimationFrame(animId)
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
@keyframes spinSlow {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@keyframes spinReverse {
  from { transform: rotate(360deg); }
  to { transform: rotate(0deg); }
}

.orbit-spin-slow {
  animation: spinSlow 36s linear infinite;
}

.orbit-spin-reverse {
  animation: spinReverse 28s linear infinite;
}

@keyframes portalPulse {
  0%, 100% { transform: scale(1); opacity: 0.7; }
  50% { transform: scale(1.08); opacity: 1; }
}

.portal-pulse-gold {
  animation: portalPulse 6s ease-in-out infinite;
}

.portal-pulse-cyan {
  animation: portalPulse 8s ease-in-out infinite reverse;
}

@keyframes floatLevitate {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.float-levitate {
  animation: floatLevitate 6s ease-in-out infinite;
}

@keyframes hudFloat1 {
  0%, 100% { transform: translate3d(0px, 0px, 0px); }
  50% { transform: translate3d(6px, -12px, 0px); }
}

@keyframes hudFloat2 {
  0%, 100% { transform: translate3d(0px, 0px, 0px); }
  50% { transform: translate3d(-8px, -14px, 0px); }
}

@keyframes hudFloat3 {
  0%, 100% { transform: translate3d(0px, 0px, 0px); }
  50% { transform: translate3d(7px, 10px, 0px); }
}

.hud-float-1 {
  animation: hudFloat1 5.5s ease-in-out infinite;
}

.hud-float-2 {
  animation: hudFloat2 6.4s ease-in-out infinite -1.5s;
}

.hud-float-3 {
  animation: hudFloat3 6.0s ease-in-out infinite -3s;
}

@keyframes laserSweep {
  0% { top: 5%; opacity: 0; }
  15% { opacity: 0.85; }
  60% { top: 92%; opacity: 0.75; }
  62%, 100% { top: 92%; opacity: 0; }
}

.laser-scanner {
  position: absolute;
  left: 8%;
  right: 8%;
  height: 2px;
  background: linear-gradient(90deg, transparent 0%, rgba(0, 242, 254, 0.5) 20%, rgba(255, 85, 0, 0.95) 50%, rgba(0, 242, 254, 0.5) 80%, transparent 100%);
  box-shadow: 0 0 14px rgba(255, 85, 0, 0.85), 0 0 4px rgba(0, 242, 254, 0.9);
  animation: laserSweep 5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

.portrait-mask-fade {
  -webkit-mask-image: radial-gradient(ellipse 84% 78% at 50% 24%, black 58%, rgba(0, 0, 0, 0.75) 75%, transparent 98%);
  mask-image: radial-gradient(ellipse 84% 78% at 50% 24%, black 58%, rgba(0, 0, 0, 0.75) 75%, transparent 98%);
}
</style>
