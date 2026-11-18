<template>
  <div 
    :dir="isRtl ? 'rtl' : 'ltr'"
    class="portfolio-luxury-root relative min-h-screen bg-[#05060b] text-slate-100 overflow-x-hidden selection:bg-[#ff5500] selection:text-white"
    :class="isRtl ? 'font-sans text-right' : 'font-sans text-left'"
  >
    
    <!-- ======================================================== -->
    <!-- GLOBAL ATMOSPHERE: 3D Perspective Grid, Morphing Orbs    -->
    <!-- ======================================================== -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
      <!-- Deep Obsidian Void Base Gradient -->
      <div class="absolute inset-0 bg-gradient-to-b from-[#05060b] via-[#090b14] to-[#05060b]"></div>

      <!-- Ambient Morphing Gradient Orbs (Parallax Driven on Scroll) -->
      <div 
        class="ambient-orb-1 absolute -top-32 -left-32 w-[520px] h-[520px] rounded-full bg-gradient-to-br from-[#ff5500]/16 via-[#f59e0b]/10 to-transparent blur-[150px]"
        style="will-change: transform;"
      ></div>
      <div 
        class="ambient-orb-2 absolute top-1/3 -right-40 w-[560px] h-[560px] rounded-full bg-gradient-to-bl from-[#00f2fe]/14 via-blue-600/8 to-transparent blur-[160px]"
        style="will-change: transform;"
      ></div>
      <div 
        class="ambient-orb-3 absolute -bottom-40 left-1/4 w-[600px] h-[600px] rounded-full bg-gradient-to-tr from-[#7c3aed]/16 via-[#ff5500]/10 to-transparent blur-[170px]"
        style="will-change: transform;"
      ></div>

      <!-- Fixed 3D Perspective Grid Floor -->
      <div class="perspective-grid-floor absolute inset-0 opacity-35"></div>

      <!-- Subtle Film Noise Texture Overlay -->
      <div class="noise-overlay absolute inset-0 opacity-[0.025]"></div>
    </div>

    <!-- Floating Glass Navigation Header -->
    <Navbar 
      :avatar-url="resolveProfileAvatar(portfolioData.profile)"
      :is-sound-enabled="isSoundEnabled"
      :is-cyan-theme="isCyanTheme"
      @toggle-sound="toggleSound"
      @toggle-theme="toggleTheme"
    />

    <!-- Top API Sync Progress Bar when loading -->
    <div 
      v-if="isLoading" 
      class="fixed top-0 left-0 right-0 z-[60] h-1 bg-[#05060b] overflow-hidden pointer-events-none"
    >
      <div class="h-full w-1/2 bg-gradient-to-r from-[#ff5500] via-amber-400 to-[#00f2fe] animate-pulse"></div>
    </div>

    <!-- Main Content Layout -->
    <main class="relative z-10">
      <!-- Hero Section (Neon Eclipse & Floating HUD Architecture) -->
      <HeroSection 
        :profile="portfolioData.profile"
        :projects="portfolioData.projects"
        :social-links="portfolioData.socialLinks"
        :stats="portfolioData.stats"
        @open-project="selectedProject = $event" 
      />

      <!-- About Section (Asymmetric Bento Architectural Grid) -->
      <AboutSection :profile="portfolioData.profile" />

      <!-- Skills & Technologies (Ranked Top-Tier Glassmorphic Capsules) -->
      <SkillsSection 
        :skills="portfolioData.skills" 
        :is-loading="isLoading"
      />

      <!-- Selected Projects (Interactive 3D Coverflow + Laser X-Ray Scanner) -->
      <ProjectsSection 
        :projects="portfolioData.projects"
        :is-loading="isLoading"
        @open-project="selectedProject = $event" 
      />

      <!-- 3D Holographic Certifications & Honors Vault -->
      <CertificatesSection :certificates="portfolioData.certificates" />

      <!-- Latest Articles & Engineering Insights -->
      <BlogSection />

      <!-- Asymmetric Leaf-Curved Contact & Omni-Channel Social Hub -->
      <ContactSection 
        :profile="portfolioData.profile" 
        :social-links="portfolioData.socialLinks" 
      />
    </main>

    <!-- Footer -->
    <Footer 
      :profile="portfolioData.profile" 
      :social-links="portfolioData.socialLinks" 
    />

    <!-- Project Details Modal -->
    <ProjectModal 
      :project="selectedProject" 
      :projects="portfolioData.projects"
      @select-project="selectedProject = $event"
      @close="selectedProject = null" 
    />
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'
import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'
import Lenis from 'lenis'
import Navbar from '../components/Navbar.vue'
import HeroSection from '../components/HeroSection.vue'
import AboutSection from '../components/AboutSection.vue'
import SkillsSection from '../components/SkillsSection.vue'
import ProjectsSection from '../components/ProjectsSection.vue'
import CertificatesSection from '../components/CertificatesSection.vue'
import BlogSection from '../components/BlogSection.vue'
import ProjectModal from '../components/ProjectModal.vue'
import ContactSection from '../components/ContactSection.vue'
import Footer from '../components/Footer.vue'
import { personalInfo, projects as defaultProjects, skills as defaultSkills, defaultCertificates, defaultSocialLinks } from '../data/portfolioData'
import { portfolioService, normalizeProject, normalizeSkill } from '../services/portfolioService'
import { profileState, setProfileState, resolveProfileAvatar } from '../services/profileState'
import { lang, isRtl } from '../services/siteLanguage'
import { currentTheme, isCyanTheme, toggleTheme as switchGlobalTheme } from '../services/themeState'

// Register GSAP ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger)

// Read cached data synchronously or fall back to default rich portfolio data
const getInitialPortfolioData = () => {
  let cachedProjects = []
  let cachedSkills = []
  let cachedCertificates = []
  let cachedSocialLinks = []
  let cachedProfile = personalInfo

  try {
    const cp = localStorage.getItem('cached_portfolio_projects')
    if (cp) cachedProjects = JSON.parse(cp)
  } catch {}

  try {
    const cs = localStorage.getItem('cached_portfolio_skills')
    if (cs) cachedSkills = JSON.parse(cs)
  } catch {}

  try {
    const cc = localStorage.getItem('cached_portfolio_certificates')
    if (cc) cachedCertificates = JSON.parse(cc)
  } catch {}

  try {
    const csoc = localStorage.getItem('cached_portfolio_social_links')
    if (csoc) cachedSocialLinks = JSON.parse(csoc)
  } catch {}

  try {
    const cprof = localStorage.getItem('cached_portfolio_profile')
    if (cprof) cachedProfile = { ...personalInfo, ...JSON.parse(cprof) }
  } catch {}

  return {
    profile: cachedProfile,
    projects: cachedProjects.length ? cachedProjects : defaultProjects.map(normalizeProject).filter(Boolean),
    skills: cachedSkills.length ? cachedSkills : defaultSkills.map(normalizeSkill).filter(Boolean),
    certificates: cachedCertificates.length ? cachedCertificates : defaultCertificates,
    socialLinks: cachedSocialLinks.length ? cachedSocialLinks : defaultSocialLinks,
    stats: cachedProfile.stats || personalInfo.stats || []
  }
}

const portfolioData = ref(getInitialPortfolioData())
const isLoading = ref(false)

const selectedProject = ref(null)
const isSoundEnabled = ref(false)
let lenis = null
let gsapTickerCallback = null

// Synthesized Web Audio API Sound Effects
let audioCtx = null
const getAudioContext = () => {
  if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)()
  if (audioCtx.state === 'suspended') audioCtx.resume()
  return audioCtx
}

const playFuturisticClick = () => {
  if (!isSoundEnabled.value) return
  try {
    const ctx = getAudioContext()
    const osc = ctx.createOscillator()
    const gain = ctx.createGain()
    osc.type = 'sine'
    osc.frequency.setValueAtTime(800, ctx.currentTime)
    osc.frequency.exponentialRampToValueAtTime(1200, ctx.currentTime + 0.08)

    gain.gain.setValueAtTime(0.08, ctx.currentTime)
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.08)

    osc.connect(gain)
    gain.connect(ctx.destination)
    osc.start()
    osc.stop(ctx.currentTime + 0.08)
  } catch (e) {
    console.warn('Audio not supported', e)
  }
}

const playFuturisticHover = () => {
  if (!isSoundEnabled.value) return
  try {
    const ctx = getAudioContext()
    const osc = ctx.createOscillator()
    const gain = ctx.createGain()
    osc.type = 'triangle'
    osc.frequency.setValueAtTime(1400, ctx.currentTime)
    osc.frequency.exponentialRampToValueAtTime(2200, ctx.currentTime + 0.04)

    gain.gain.setValueAtTime(0.025, ctx.currentTime)
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.04)

    osc.connect(gain)
    gain.connect(ctx.destination)
    osc.start()
    osc.stop(ctx.currentTime + 0.04)
  } catch (e) {
    // Ignore audio errors
  }
}

const playFuturisticSweep = () => {
  if (!isSoundEnabled.value) return
  try {
    const ctx = getAudioContext()
    const osc = ctx.createOscillator()
    const gain = ctx.createGain()
    osc.type = 'sine'
    osc.frequency.setValueAtTime(450, ctx.currentTime)
    osc.frequency.exponentialRampToValueAtTime(1350, ctx.currentTime + 0.15)

    gain.gain.setValueAtTime(0.07, ctx.currentTime)
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15)

    osc.connect(gain)
    gain.connect(ctx.destination)
    osc.start()
    osc.stop(ctx.currentTime + 0.15)
  } catch (e) {
    // Ignore audio errors
  }
}

const toggleSound = () => {
  isSoundEnabled.value = !isSoundEnabled.value
  if (isSoundEnabled.value) {
    playFuturisticClick()
  }
}

const toggleTheme = () => {
  switchGlobalTheme()
  playFuturisticSweep()
}

// Play sound on modal open/close
watch(selectedProject, (val) => {
  if (val) {
    playFuturisticSweep()
  }
})

let clickHandler = null
let hoverHandler = null
let syncHandler = null

const refreshPortfolioData = async () => {
  // Only display skeleton placeholders if we don't have any items rendered yet
  if (!portfolioData.value.projects?.length && !portfolioData.value.skills?.length) {
    isLoading.value = true
  }
  try {
    const data = await portfolioService.getPortfolio()
    if (data) {
      portfolioData.value = {
        profile: data.profile || personalInfo,
        projects: Array.isArray(data.projects) && data.projects.length ? data.projects : portfolioData.value.projects,
        skills: Array.isArray(data.skills) && data.skills.length ? data.skills : portfolioData.value.skills,
        certificates: Array.isArray(data.certificates) && data.certificates.length ? data.certificates : portfolioData.value.certificates,
        socialLinks: Array.isArray(data.socialLinks) && data.socialLinks.length ? data.socialLinks : portfolioData.value.socialLinks,
        stats: Array.isArray(data.stats) && data.stats.length ? data.stats : (personalInfo.stats || [])
      }
    }
  } catch (err) {
    console.warn('[PortfolioView] Backend API offline or pending, keeping local portfolio data:', err.message)
  } finally {
    isLoading.value = false
    nextTick(() => {
      ScrollTrigger.refresh()
      gsap.set('.skill-card', { clearProps: 'opacity,transform,scale' })
    })
  }
}

watch(
  [() => portfolioData.value.skills, () => portfolioData.value.projects],
  () => {
    nextTick(() => {
      ScrollTrigger.refresh()
      gsap.set('.skill-card', { clearProps: 'opacity,transform,scale' })
    })
  },
  { deep: true }
)

onMounted(() => {
  // 1. Fetch dynamic portfolio data from Laravel backend API
  refreshPortfolioData()

  // 2. Real-time synchronization when Admin updates projects, skills, or profile
  syncHandler = (e) => {
    if (e.key === 'portfolio_sync_trigger' || e.key === 'cached_portfolio_profile') {
      try {
        const raw = localStorage.getItem('cached_portfolio_profile')
        if (raw) {
          const parsed = JSON.parse(raw)
          setProfileState(parsed, { broadcast: false })
          portfolioData.value.profile = { ...profileState }
        }
      } catch {}
      refreshPortfolioData()
    }
  }
  window.addEventListener('storage', syncHandler)
  window.addEventListener('portfolio-updated', (ev) => {
    if (ev?.detail && typeof ev.detail === 'object') {
      portfolioData.value.profile = { ...portfolioData.value.profile, ...ev.detail }
    } else {
      portfolioData.value.profile = { ...portfolioData.value.profile, ...profileState }
    }
    refreshPortfolioData()
  })

  // 3. Attach subtle audio click & hover to interactive elements
  clickHandler = (e) => {
    if (e.target.closest('button') || e.target.closest('a')) {
      playFuturisticClick()
    }
  }
  hoverHandler = (e) => {
    if (e.target.closest('.skill-card') || e.target.closest('.project-card') || e.target.closest('button')) {
      playFuturisticHover()
    }
  }

  document.addEventListener('click', clickHandler)
  document.addEventListener('mouseover', hoverHandler)

  // 4. Initialize Lenis Butter-Smooth Inertial Scroll Engine & Sync with GSAP
  try {
    lenis = new Lenis({
      duration: 1.2,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      smoothWheel: true,
      touchMultiplier: 1.8,
    })
    window.__lenis = lenis

    lenis.on('scroll', ScrollTrigger.update)

    gsapTickerCallback = (time) => {
      if (lenis) lenis.raf(time * 1000)
    }
    gsap.ticker.add(gsapTickerCallback)
    gsap.ticker.lagSmoothing(0)
  } catch (err) {
    console.warn('Lenis smooth scroll initialization skipped:', err)
  }

  // 5. Master GSAP Cinematic Intro & ScrollTrigger Choreography
  nextTick(() => {
    const introTl = gsap.timeline({
      defaults: { ease: 'power3.out' },
      onComplete: () => {
        gsap.set(
          '.navbar-floating, .hero-badge, .hero-title-line, .hero-subtitle, .hero-desc, .hero-buttons, .hero-social, .hero-impact, .hero-eclipse-ring, .hero-portrait-core, .hero-hud-card',
          { clearProps: 'opacity,transform,filter' }
        )
      }
    })

    // A. Navbar Drop-In with Blur-to-Clear Transition
    introTl
      .from('.navbar-floating', {
        y: -40,
        opacity: 0,
        filter: 'blur(10px)',
        duration: 0.85
      })
      // B. Hero Status Badge & 3D Skew Title Lines
      .from('.hero-badge', { y: -24, opacity: 0, duration: 0.7 }, '-=0.5')
      .from('.hero-title-line', {
        yPercent: 110,
        rotateX: -20,
        opacity: 0,
        stagger: 0.14,
        duration: 0.9
      }, '-=0.45')
      .from('.hero-subtitle', { y: 24, opacity: 0, duration: 0.7 }, '-=0.55')
      .from('.hero-desc', { y: 20, opacity: 0, duration: 0.7 }, '-=0.5')
      .from('.hero-buttons', { y: 20, opacity: 0, duration: 0.7 }, '-=0.5')
      .from('.hero-social', { y: 18, opacity: 0, duration: 0.65 }, '-=0.5')
      // C. Neon Eclipse Ring Burst + Portrait + 3 Floating HUD Cards with Spring Physics
      .from('.hero-eclipse-ring', {
        scale: 0.6,
        opacity: 0,
        duration: 1.0,
        ease: 'power3.out'
      }, '-=0.95')
      .from('.hero-portrait-core', {
        scale: 0.82,
        opacity: 0,
        duration: 0.95,
        ease: 'back.out(1.5)'
      }, '-=0.85')
      .from('.hero-hud-card', {
        scale: 0.5,
        opacity: 0,
        y: 30,
        stagger: 0.12,
        duration: 0.85,
        ease: 'back.out(1.7)'
      }, '-=0.65')
      // D. Bottom Telemetry Bar Entrance
      .from('.hero-impact', {
        y: 35,
        opacity: 0,
        duration: 0.8
      }, '-=0.55')

    // Section Headers Choreography (Badge Wipe + Title Reveal)
    gsap.utils.toArray('.section-header').forEach((header) => {
      gsap.fromTo(
        header,
        { y: 36, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.8,
          ease: 'power3.out',
          scrollTrigger: {
            trigger: header,
            start: 'top 86%',
            once: true
          },
          clearProps: 'all'
        }
      )
    })

    // About Bento Cards 3D Staggered Entrance
    gsap.fromTo(
      '#about .bento-card',
      { opacity: 0, y: 50, rotateX: 12, scale: 0.94 },
      {
        opacity: 1,
        y: 0,
        rotateX: 0,
        scale: 1,
        duration: 0.8,
        stagger: 0.08,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: '#about',
          start: 'top 82%',
          once: true
        },
        clearProps: 'all'
      }
    )

    // Skills Capsules 3D Staggered Entrance
    gsap.fromTo(
      '.skill-card',
      { opacity: 0, y: 35, scale: 0.96 },
      {
        opacity: 1,
        y: 0,
        scale: 1,
        duration: 0.6,
        stagger: 0.04,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: '#skills',
          start: 'top 92%',
          once: true
        },
        clearProps: 'all'
      }
    )

    // Projects 3D Stage Entrance (Do not touch .project-card inline 3D transforms)
    gsap.fromTo(
      '.projects-3d-stage',
      { opacity: 0, y: 35 },
      {
        opacity: 1,
        y: 0,
        duration: 0.75,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: '#projects',
          start: 'top 92%',
          once: true
        },
        clearProps: 'opacity,y'
      }
    )

    // Blog Multi-Panel Cards Reveal
    gsap.fromTo(
      '.blog-card',
      { opacity: 0, y: 45, scale: 0.95 },
      {
        opacity: 1,
        y: 0,
        scale: 1,
        duration: 0.75,
        stagger: 0.12,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: '#blog',
          start: 'top 85%',
          once: true
        },
        clearProps: 'all'
      }
    )

    // Contact Leaf-Curved Glass Banner Reveal
    gsap.fromTo(
      '.contact-leaf-banner',
      { opacity: 0, y: 50, scale: 0.96 },
      {
        opacity: 1,
        y: 0,
        scale: 1,
        duration: 0.85,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: '#contact',
          start: 'top 85%',
          once: true
        },
        clearProps: 'all'
      }
    )

    // Parallax Depth on Ambient Orbs (scrub: 1.2)
    gsap.to('.ambient-orb-1', {
      y: 240,
      x: 80,
      ease: 'none',
      scrollTrigger: {
        trigger: '.portfolio-luxury-root',
        start: 'top top',
        end: 'bottom bottom',
        scrub: 1.2
      }
    })

    gsap.to('.ambient-orb-2', {
      y: -220,
      x: -90,
      ease: 'none',
      scrollTrigger: {
        trigger: '.portfolio-luxury-root',
        start: 'top top',
        end: 'bottom bottom',
        scrub: 1.2
      }
    })

    gsap.to('.ambient-orb-3', {
      y: -160,
      x: 110,
      ease: 'none',
      scrollTrigger: {
        trigger: '.portfolio-luxury-root',
        start: 'top top',
        end: 'bottom bottom',
        scrub: 1.2
      }
    })

    setTimeout(() => {
      ScrollTrigger.refresh()
    }, 350)
  })
})

onBeforeUnmount(() => {
  if (clickHandler) document.removeEventListener('click', clickHandler)
  if (hoverHandler) document.removeEventListener('mouseover', hoverHandler)
  if (syncHandler) window.removeEventListener('storage', syncHandler)
  window.removeEventListener('portfolio-updated', refreshPortfolioData)
  ScrollTrigger.getAll().forEach(t => t.kill())
  if (gsapTickerCallback) {
    gsap.ticker.remove(gsapTickerCallback)
    gsapTickerCallback = null
  }
  if (lenis) {
    lenis.destroy()
    window.__lenis = null
    lenis = null
  }
})
</script>

<style scoped>
/* Fixed 3D Perspective Grid Floor */
.perspective-grid-floor {
  background-image: 
    linear-gradient(to right, rgba(255, 85, 0, 0.07) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(0, 242, 254, 0.05) 1px, transparent 1px);
  background-size: 4rem 4rem;
  mask-image: radial-gradient(ellipse 85% 65% at 50% 35%, #000 40%, transparent 100%);
  -webkit-mask-image: radial-gradient(ellipse 85% 65% at 50% 35%, #000 40%, transparent 100%);
}

/* Subtle SVG Noise Overlay */
.noise-overlay {
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
}
</style>
