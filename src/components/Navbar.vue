<template>
  <header 
    class="navbar-floating fixed top-0 left-0 right-0 z-50 transition-all duration-500 px-3 sm:px-6 lg:px-8"
    :class="[isScrolled ? 'pt-3 pb-2' : 'pt-5 pb-3']"
  >
    <div 
      class="max-w-7xl mx-auto rounded-full transition-all duration-500 px-4 sm:px-6 py-2.5 flex items-center justify-between"
      :class="[
        isScrolled 
          ? 'bg-[#05060b]/85 backdrop-blur-2xl border border-white/[0.1] shadow-[0_20px_50px_rgba(0,0,0,0.75),_0_0_25px_rgba(245,158,11,0.12)]' 
          : 'bg-white/[0.03] backdrop-blur-xl border border-white/[0.08] shadow-[0_10px_30px_rgba(0,0,0,0.4)]'
      ]"
    >
      <!-- Left: Avatar Brand Pill -->
      <a 
        href="#hero" 
        @click.prevent="scrollTo({ name: 'Home', href: '#hero' })"
        class="flex items-center gap-3 group focus:outline-none cursor-pointer"
      >
        <div class="relative w-10 h-10 rounded-full p-[1.5px] bg-gradient-to-tr from-[#ff5500] via-amber-400 to-[#00f2fe] shadow-[0_0_20px_rgba(255,85,0,0.45)] group-hover:scale-105 transition-transform duration-300">
          <img 
            :src="resolvedAvatarUrl" 
            :alt="profileState.name || 'Mohammed Alhwaiti'" 
            class="w-full h-full object-cover rounded-full bg-[#05060b]"
            @error="(e) => e.target.src = '/developer-portrait.jpg'"
          />
          <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2 border-[#05060b] shadow-[0_0_8px_#34d399]"></div>
        </div>
        <div class="flex flex-col">
          <span class="text-sm font-extrabold tracking-wide text-white group-hover:text-amber-300 transition-colors">
            {{ profileState.name || 'Mohammed Alhwaiti' }}
          </span>
          <span class="text-[10px] font-mono text-[#00f2fe] tracking-widest uppercase hidden sm:block">
            {{ profileState.title || '3D & FRONT-END ARCHITECT' }}
          </span>
        </div>
      </a>

      <!-- Center: Desktop Floating Glass Navigation Links with Sliding Active Pill -->
      <nav 
        ref="navContainerRef"
        class="hidden md:flex items-center gap-1 p-1.5 rounded-full bg-[#05060b]/80 border border-white/[0.08] backdrop-blur-xl shadow-inner relative"
      >
        <!-- Sliding Active Pill Indicator -->
        <div 
          class="absolute top-1.5 bottom-1.5 rounded-full bg-gradient-to-r from-[#ff5500]/25 via-amber-500/20 to-[#f59e0b]/25 border border-amber-400/50 shadow-[0_0_20px_rgba(255,85,0,0.35)] transition-all duration-300 ease-out pointer-events-none"
          :style="slidingPillStyle"
        ></div>

        <a 
          v-for="item in navLinks" 
          :key="item.name"
          :ref="(el) => setLinkRef(el, item.name)"
          :href="item.href"
          @click.prevent="scrollTo(item)"
          class="relative z-10 px-4 py-1.5 text-xs font-semibold tracking-wide transition-colors duration-300 rounded-full cursor-pointer select-none"
          :class="[
            activeNav === item.name 
              ? 'text-amber-300' 
              : 'text-slate-300 hover:text-white'
          ]"
        >
          <span>{{ item.name }}</span>
          <span 
            v-if="activeNav === item.name" 
            class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-amber-400 shadow-[0_0_8px_#f59e0b]"
          ></span>
        </a>
      </nav>

      <!-- Right: Theme Switcher, Sound & Magnetic CTA Button -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Neon Accent Theme Switcher (Gold/Eclipse ↔ Cyan) -->
        <button
          @click="$emit('toggle-theme')"
          :title="isCyanTheme ? 'Switch to Neon Eclipse Accent' : 'Switch to Cyber Cyan Accent'"
          class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border transition-all text-xs font-mono font-medium cursor-pointer"
          :class="isCyanTheme 
            ? 'bg-cyan-500/15 border-cyan-400/50 text-cyan-300 shadow-[0_0_15px_rgba(0,242,254,0.35)]' 
            : 'bg-amber-500/15 border-amber-400/50 text-amber-300 shadow-[0_0_15px_rgba(255,85,0,0.35)]'"
        >
          <span v-if="isCyanTheme" class="flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
            <span class="hidden sm:inline">Cyan</span>
            <span>⚡</span>
          </span>
          <span v-else class="flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-[#ff5500] animate-pulse"></span>
            <span class="hidden sm:inline">Eclipse</span>
            <span>👑</span>
          </span>
        </button>

        <!-- Sound Effect Toggle -->
        <button
          @click="$emit('toggle-sound')"
          :title="isSoundEnabled ? 'Mute futuristic sound effects' : 'Enable futuristic sound effects'"
          class="p-2 rounded-full border border-white/[0.08] bg-white/[0.03] hover:border-amber-500/40 text-slate-300 hover:text-amber-300 transition-colors text-xs cursor-pointer"
        >
          <span v-if="isSoundEnabled">🔊</span>
          <span v-else>🔇</span>
        </button>

        <!-- Magnetic "Let's Connect" CTA Button -->
        <a 
          ref="ctaBtnRef"
          href="#contact" 
          @click.prevent="scrollTo({ name: 'Contact', href: '#contact' })"
          @mousemove="handleMagneticMove"
          @mouseleave="resetMagneticMove"
          :style="magneticStyle"
          class="hidden sm:inline-flex items-center gap-2 px-5 py-2 rounded-full text-xs font-bold tracking-wider text-white bg-gradient-to-r from-[#ff5500] via-amber-500 to-[#f59e0b] border border-amber-300/40 shadow-[0_0_24px_rgba(255,85,0,0.45)] hover:shadow-[0_0_32px_rgba(255,85,0,0.75)] transition-shadow cursor-pointer group"
        >
          <span>Let's Connect</span>
          <svg class="w-3.5 h-3.5 text-white transform rotate-45 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
        </a>

        <!-- Mobile Menu Toggle Button -->
        <button 
          @click="isMobileMenuOpen = !isMobileMenuOpen"
          class="md:hidden p-2 rounded-full bg-white/[0.05] border border-white/10 text-slate-300 hover:text-white"
          aria-label="Toggle Navigation"
        >
          <svg v-if="!isMobileMenuOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
          <svg v-else class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Dropdown Navigation -->
    <div 
      v-if="isMobileMenuOpen" 
      class="md:hidden mt-2 mx-auto max-w-7xl px-4 pt-3 pb-5 rounded-3xl bg-[#05060b]/95 border border-white/10 backdrop-blur-2xl shadow-2xl animate-fadeIn"
    >
      <div class="flex flex-col gap-1.5">
        <a 
          v-for="item in navLinks" 
          :key="item.name"
          :href="item.href"
          @click.prevent="scrollTo(item)"
          class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all flex items-center justify-between"
          :class="[
            activeNav === item.name 
              ? 'text-amber-300 bg-gradient-to-r from-[#ff5500]/20 to-amber-500/10 border-l-4 border-[#ff5500]' 
              : 'text-slate-300 hover:bg-white/5 hover:text-white'
          ]"
        >
          <span>{{ item.name }}</span>
          <span v-if="activeNav === item.name" class="w-2 h-2 rounded-full bg-[#ff5500] shadow-[0_0_8px_#ff5500]"></span>
        </a>
        <a 
          href="#contact" 
          @click.prevent="scrollTo({ name: 'Contact', href: '#contact' })"
          class="mt-2 text-center py-2.5 rounded-full text-xs font-bold text-white bg-gradient-to-r from-[#ff5500] to-amber-500 shadow-[0_0_20px_rgba(255,85,0,0.4)]"
        >
          Let's Connect 🚀
        </a>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, reactive, computed, watch, nextTick, onMounted, onBeforeUnmount } from 'vue'
import { profileState, resolveProfileAvatar } from '@/services/profileState'

const props = defineProps({
  avatarUrl: {
    type: String,
    default: ''
  },
  isSoundEnabled: {
    type: Boolean,
    default: false
  },
  isCyanTheme: {
    type: Boolean,
    default: false
  }
})

const resolvedAvatarUrl = computed(() => {
  return resolveProfileAvatar({
    ...profileState,
    ...(props.avatarUrl ? { avatar: props.avatarUrl } : {})
  })
})

defineEmits(['toggle-sound', 'toggle-theme'])

const isScrolled = ref(false)
const isMobileMenuOpen = ref(false)
const activeNav = ref('Home')

const navLinks = [
  { name: 'Home', href: '#hero' },
  { name: 'About', href: '#about' },
  { name: 'Skills', href: '#skills' },
  { name: 'Projects', href: '#projects' },
  { name: 'Certificates', href: '#certificates' },
  { name: 'Blog', href: '#blog' },
  { name: 'Contact', href: '#contact' },
]

// Sliding Pill Indicator
const navContainerRef = ref(null)
const linkRefs = reactive({})
const slidingPillStyle = ref({ left: '6px', width: '68px', opacity: 1 })

const setLinkRef = (el, name) => {
  if (el) linkRefs[name] = el
}

const updateSlidingPill = () => {
  const activeEl = linkRefs[activeNav.value]
  if (activeEl && navContainerRef.value) {
    slidingPillStyle.value = {
      left: `${activeEl.offsetLeft}px`,
      width: `${activeEl.offsetWidth}px`,
      opacity: 1
    }
  }
}

watch(activeNav, () => {
  nextTick(updateSlidingPill)
})

// Magnetic CTA Button Physics
const ctaBtnRef = ref(null)
const magneticStyle = ref({ transform: 'translate3d(0px, 0px, 0px)', transition: 'transform 200ms ease-out' })

const handleMagneticMove = (e) => {
  if (!ctaBtnRef.value) return
  const rect = ctaBtnRef.value.getBoundingClientRect()
  const x = (e.clientX - rect.left - rect.width / 2) * 0.28
  const y = (e.clientY - rect.top - rect.height / 2) * 0.28
  magneticStyle.value = {
    transform: `translate3d(${x.toFixed(1)}px, ${y.toFixed(1)}px, 0px)`,
    transition: 'transform 60ms linear'
  }
}

const resetMagneticMove = () => {
  magneticStyle.value = {
    transform: 'translate3d(0px, 0px, 0px)',
    transition: 'transform 400ms cubic-bezier(0.16, 1, 0.3, 1)'
  }
}

// Smooth Scroll Navigation with Offset
const scrollTo = (item) => {
  activeNav.value = item.name
  isMobileMenuOpen.value = false
  const targetId = item.href.replace('#', '')
  const el = document.getElementById(targetId)
  if (el) {
    if (window.__lenis) {
      window.__lenis.scrollTo(el, { offset: -80, duration: 1.2 })
    } else {
      const offset = 80
      const bodyRect = document.body.getBoundingClientRect().top
      const elementRect = el.getBoundingClientRect().top
      const elementPosition = elementRect - bodyRect
      const offsetPosition = elementPosition - offset

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      })
    }
  }
}

// IntersectionObserver Scroll Spy
let observer = null

const initScrollSpy = () => {
  const sectionIds = ['hero', 'about', 'skills', 'projects', 'certificates', 'blog', 'contact']
  const idToNavMap = {
    hero: 'Home',
    about: 'About',
    skills: 'Skills',
    projects: 'Projects',
    certificates: 'Certificates',
    blog: 'Blog',
    contact: 'Contact'
  }

  const options = {
    root: null,
    rootMargin: '-20% 0px -55% 0px',
    threshold: [0, 0.2]
  }

  observer = new IntersectionObserver((entries) => {
    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 60) {
      activeNav.value = 'Contact'
      return
    }

    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const id = entry.target.getAttribute('id')
        if (id && idToNavMap[id]) {
          activeNav.value = idToNavMap[id]
        }
      }
    })
  }, options)

  sectionIds.forEach((id) => {
    const el = document.getElementById(id)
    if (el) observer.observe(el)
  })
}

const handleScroll = () => {
  isScrolled.value = window.scrollY > 24
  if (window.scrollY < 100) {
    activeNav.value = 'Home'
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
  window.addEventListener('resize', updateSlidingPill, { passive: true })
  handleScroll()
  initScrollSpy()
  setTimeout(updateSlidingPill, 150)
})

onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll)
  window.removeEventListener('resize', updateSlidingPill)
  if (observer) {
    observer.disconnect()
    observer = null
  }
})
</script>
