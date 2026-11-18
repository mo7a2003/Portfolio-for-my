<template>
  <header 
    class="navbar-floating fixed top-0 left-0 right-0 z-50 transition-all duration-300 px-3 sm:px-6 lg:px-8"
    :class="[isScrolled ? 'pt-2.5 pb-2' : 'pt-4 pb-3']"
  >
    <div 
      class="max-w-7xl mx-auto rounded-2xl sm:rounded-full transition-all duration-300 px-3.5 sm:px-5 py-2 flex items-center justify-between gap-3 sm:gap-4"
      :class="[
        isScrolled 
          ? 'bg-slate-950/80 backdrop-blur-2xl border border-slate-800/90 shadow-[0_15px_40px_rgba(0,0,0,0.7)]' 
          : 'bg-slate-950/50 backdrop-blur-xl border border-white/[0.08] shadow-[0_10px_30px_rgba(0,0,0,0.35)]'
      ]"
    >
      <!-- Left: Logo & Profile Brand -->
      <a 
        href="#hero" 
        @click.prevent="scrollTo({ name: 'Home', href: '#hero' })"
        class="flex items-center gap-2.5 sm:gap-3 group focus:outline-none cursor-pointer shrink-0"
      >
        <div class="relative w-9 h-9 sm:w-10 sm:h-10 rounded-xl p-[1.5px] bg-gradient-to-tr from-[#ff5500] via-amber-400 to-[#00f2fe] shadow-md shadow-cyan-500/10 group-hover:scale-105 transition-transform duration-300 shrink-0">
          <img 
            :src="resolvedAvatarUrl" 
            :alt="isRtl && (profileState.nameAr || profileState.name_ar) ? (profileState.nameAr || profileState.name_ar) : (profileState.name || 'Mohammed Alhwaiti')" 
            class="w-full h-full object-cover rounded-[10px] bg-slate-950"
            @error="(e) => e.target.src = '/developer-portrait.jpg'"
          />
          <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-slate-950 shadow-[0_0_8px_#34d399]"></div>
        </div>
        <div class="flex flex-col text-left" :class="isRtl ? 'text-right' : 'text-left'">
          <span class="text-xs sm:text-sm font-bold tracking-tight text-white group-hover:text-amber-300 transition-colors whitespace-nowrap">
            {{ isRtl && (profileState.nameAr || profileState.name_ar) ? (profileState.nameAr || profileState.name_ar) : (profileState.name || 'Mohammed Alhwaiti') }}
          </span>
          <span class="text-[10px] sm:text-[11px] font-mono text-cyan-400 font-medium tracking-wide hidden sm:block whitespace-nowrap">
            {{ isRtl && (profileState.title_ar || profileState.titleAr) ? (profileState.title_ar || profileState.titleAr) : (profileState.title || 'Front-End & 3D Architect') }}
          </span>
        </div>
      </a>

      <!-- Center: Navigation Links (Clean, Centered & Non-Crowded) -->
      <nav 
        ref="navContainerRef"
        class="hidden lg:flex items-center gap-1 bg-slate-900/60 border border-slate-800/90 px-3 py-1.5 rounded-full shadow-inner relative"
      >
        <!-- Sliding Active Pill Indicator -->
        <div 
          class="absolute top-1 bottom-1 rounded-full bg-gradient-to-r from-amber-500/25 via-amber-400/20 to-cyan-500/20 border border-amber-400/40 shadow-[0_0_15px_rgba(245,158,11,0.25)] transition-all duration-300 ease-out pointer-events-none"
          :style="slidingPillStyle"
        ></div>

        <a 
          v-for="item in navLinks" 
          :key="item.key"
          :ref="(el) => setLinkRef(el, item.key)"
          :href="item.href"
          @click.prevent="scrollTo(item)"
          class="relative z-10 px-3 py-1 text-xs font-medium tracking-normal transition-all duration-200 rounded-full cursor-pointer select-none whitespace-nowrap"
          :class="[
            activeNav === item.key 
              ? 'text-amber-300 font-semibold shadow-sm' 
              : 'text-slate-300 hover:text-white hover:bg-slate-800/50'
          ]"
        >
          <span>{{ item.name }}</span>
        </a>
      </nav>

      <!-- Right: Action Controls (Theme, Language, Sound, CTA) -->
      <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
        <!-- Theme Toggle Button -->
        <button
          type="button"
          @click="onToggleTheme"
          :title="isCyanTheme ? 'Switch to Eclipse Mode' : 'Switch to Cyan Mode'"
          class="px-2.5 sm:px-3 py-1.5 text-xs font-semibold rounded-xl transition-all flex items-center gap-1.5 shadow-sm border cursor-pointer select-none active:scale-95"
          :class="isCyanTheme 
            ? 'text-cyan-300 bg-cyan-950/40 border-cyan-500/40 hover:bg-cyan-900/50 shadow-[0_0_12px_rgba(0,242,254,0.25)]' 
            : 'text-amber-300 bg-amber-950/40 border-amber-500/40 hover:bg-amber-900/50 shadow-[0_0_12px_rgba(245,158,11,0.2)]'"
        >
          <span>{{ isCyanTheme ? '⚡ Cyan' : '👑 Eclipse' }}</span>
        </button>

        <!-- Language Toggle Button -->
        <button
          type="button"
          @click="toggleLang"
          :title="lang === 'ar' ? 'Switch to English' : 'التبديل إلى العربية'"
          class="px-2.5 sm:px-3 py-1.5 text-xs font-semibold text-cyan-300 bg-cyan-950/30 border border-cyan-500/30 rounded-xl hover:bg-cyan-900/40 hover:border-cyan-400 transition-all flex items-center gap-1.5 shadow-sm cursor-pointer select-none active:scale-95"
        >
          <span>{{ lang === 'ar' ? 'English 🇺🇸' : 'العربية 🇸🇦' }}</span>
        </button>

        <!-- Sound Effect Toggle (Compact) -->
        <button
          type="button"
          @click="$emit('toggle-sound')"
          :title="isSoundEnabled ? 'Mute sound effects' : 'Enable sound effects'"
          class="p-1.5 sm:p-2 rounded-xl border border-slate-800 bg-slate-900/60 hover:border-amber-500/40 text-slate-300 hover:text-amber-300 transition-colors text-xs cursor-pointer select-none hidden sm:inline-flex"
        >
          <span v-if="isSoundEnabled">🔊</span>
          <span v-else>🔇</span>
        </button>

        <!-- Let's Connect CTA Button -->
        <a 
          ref="ctaBtnRef"
          href="#contact" 
          @click.prevent="scrollTo({ key: 'Contact', name: t.navContact, href: '#contact' })"
          @mousemove="handleMagneticMove"
          @mouseleave="resetMagneticMove"
          :style="magneticStyle"
          class="hidden xl:inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 rounded-xl transition-all shadow-md shadow-amber-500/20 cursor-pointer select-none shrink-0"
        >
          <span>{{ t.navConnect }}</span>
          <svg class="w-3.5 h-3.5 text-slate-950 transform rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
        </a>

        <!-- Mobile Menu Hamburger Button -->
        <button 
          type="button"
          @click="isMobileMenuOpen = !isMobileMenuOpen"
          class="lg:hidden p-2 rounded-xl bg-slate-900/70 border border-slate-800 text-slate-300 hover:text-white cursor-pointer"
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
      class="lg:hidden mt-2 mx-auto max-w-7xl px-4 pt-3 pb-5 rounded-2xl bg-slate-950/95 border border-slate-800 backdrop-blur-2xl shadow-2xl animate-fadeIn"
    >
      <div class="flex flex-col gap-1.5">
        <a 
          v-for="item in navLinks" 
          :key="item.key"
          :href="item.href"
          @click.prevent="scrollTo(item)"
          class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all flex items-center justify-between"
          :class="[
            activeNav === item.key 
              ? 'text-amber-300 bg-amber-500/10 border-l-4 border-amber-400' 
              : 'text-slate-300 hover:bg-slate-900 hover:text-white'
          ]"
        >
          <span>{{ item.name }}</span>
          <span v-if="activeNav === item.key" class="w-2 h-2 rounded-full bg-amber-400 shadow-[0_0_8px_#f59e0b]"></span>
        </a>

        <!-- Mobile Controls & Let's Connect -->
        <div class="pt-3 border-t border-slate-800 mt-2 flex flex-col gap-2">
          <div class="flex items-center justify-between gap-2">
            <button
              type="button"
              @click="onToggleTheme"
              class="flex-1 py-2 px-3 text-xs font-semibold rounded-xl border text-center transition-all cursor-pointer"
              :class="isCyanTheme 
                ? 'text-cyan-300 bg-cyan-950/40 border-cyan-500/40' 
                : 'text-amber-300 bg-amber-950/40 border-amber-500/40'"
            >
              {{ isCyanTheme ? '⚡ Cyan Mode' : '👑 Eclipse Mode' }}
            </button>

            <button
              type="button"
              @click="$emit('toggle-sound')"
              class="py-2 px-3 text-xs font-semibold rounded-xl border border-slate-800 bg-slate-900 text-slate-300 cursor-pointer"
            >
              {{ isSoundEnabled ? '🔊 Sound On' : '🔇 Sound Off' }}
            </button>
          </div>

          <a 
            href="#contact" 
            @click.prevent="scrollTo({ key: 'Contact', name: t.navContact, href: '#contact' })"
            class="text-center py-2.5 rounded-xl text-xs font-bold text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 shadow-lg shadow-amber-500/20 cursor-pointer"
          >
            {{ t.navConnect }} 🚀
          </a>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, reactive, computed, watch, nextTick, onMounted, onBeforeUnmount } from 'vue'
import { profileState, resolveProfileAvatar } from '@/services/profileState'
import { lang, isRtl, toggleLang, t } from '@/services/siteLanguage'
import { isCyanTheme, toggleTheme as switchGlobalTheme } from '@/services/themeState'

const props = defineProps({
  avatarUrl: {
    type: String,
    default: ''
  },
  isSoundEnabled: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['toggle-sound', 'toggle-theme'])

const onToggleTheme = () => {
  switchGlobalTheme()
  emit('toggle-theme')
}

const resolvedAvatarUrl = computed(() => {
  return resolveProfileAvatar({
    ...profileState,
    ...(props.avatarUrl ? { avatar: props.avatarUrl } : {})
  })
})

const isScrolled = ref(false)
const isMobileMenuOpen = ref(false)
const activeNav = ref('Home')

const navLinks = computed(() => [
  { key: 'Home', name: t.value.navHome, href: '#hero' },
  { key: 'About', name: t.value.navAbout, href: '#about' },
  { key: 'Skills', name: t.value.navSkills, href: '#skills' },
  { key: 'Projects', name: t.value.navProjects, href: '#projects' },
  { key: 'Certificates', name: t.value.navCertificates, href: '#certificates' },
  { key: 'Blog', name: t.value.navBlog, href: '#blog' },
  { key: 'Contact', name: t.value.navContact, href: '#contact' },
])

// Sliding Pill Indicator
const navContainerRef = ref(null)
const linkRefs = reactive({})
const slidingPillStyle = ref({ left: '6px', width: '60px', opacity: 1 })

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

watch([activeNav, lang], () => {
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
  activeNav.value = item.key || item.name
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
