<template>
  <div 
    :dir="lang === 'ar' ? 'rtl' : 'ltr'"
    class="min-h-screen bg-[#04060c] text-slate-100 flex relative selection:bg-amber-500 selection:text-black font-sans antialiased overflow-x-hidden"
  >
    
    <!-- ========================================================================= -->
    <!-- 3D NEON-ECLIPSE & X-RAY BLUEPRINT AMBIENT BACKGROUND                      -->
    <!-- ========================================================================= -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
      <!-- Neon Amber-Orange Radial Eclipse Orb -->
      <div class="absolute -top-44 -left-40 w-[620px] h-[620px] rounded-full bg-[#f59e0b]/[0.11] blur-[160px]"></div>
      <!-- Cyber Cyan X-Ray Radial Orb -->
      <div class="absolute -bottom-48 right-1/4 w-[650px] h-[650px] rounded-full bg-[#00f2fe]/[0.08] blur-[180px]"></div>
      <!-- Secondary Deep Orange Core Glow -->
      <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-[500px] h-[300px] rounded-full bg-[#ff5500]/[0.05] blur-[150px]"></div>

      <!-- Subtle X-Ray Cyan Blueprint Grid Overlay -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(0,242,254,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,242,254,0.03)_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] [mask-image:radial-gradient(ellipse_85%_65%_at_50%_35%,#000_70%,transparent_100%)]"></div>

      <!-- 3D Perspective Cyber Floor Grid -->
      <div class="admin-perspective-floor absolute bottom-0 inset-x-0 h-[38vh] opacity-35"></div>
    </div>

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div 
      v-if="isMobileMenuOpen" 
      @click="isMobileMenuOpen = false"
      class="fixed inset-0 bg-black/75 backdrop-blur-sm z-40 md:hidden transition-opacity"
    ></div>

    <!-- ========================================================================= -->
    <!-- COLLAPSIBLE 3D GLASS SIDEBAR (w-72 Expanded / w-20 Collapsed)             -->
    <!-- ========================================================================= -->
    <aside 
      class="fixed top-0 bottom-0 bg-[#060913]/85 backdrop-blur-2xl border-white/[0.08] z-50 flex flex-col justify-between transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] shadow-[0_0_50px_rgba(0,0,0,0.85)]"
      :class="[
        isSidebarCollapsed ? 'w-72 md:w-20' : 'w-72',
        lang === 'ar' ? 'right-0 border-l' : 'left-0 border-r',
        isMobileMenuOpen 
          ? 'translate-x-0' 
          : (lang === 'ar' ? 'translate-x-full md:translate-x-0' : '-translate-x-full md:translate-x-0')
      ]"
    >
      <!-- Sleek Floating Neon Collapse/Expand Toggle Button on Sidebar Border (Desktop) -->
      <button
        type="button"
        @click="toggleSidebar"
        :title="isSidebarCollapsed ? t.expandSidebar : t.collapseSidebar"
        class="hidden md:flex items-center justify-center w-7 h-7 rounded-full bg-[#090d1a] border border-amber-500/50 text-amber-400 hover:text-white hover:bg-amber-500/20 hover:border-amber-400 shadow-[0_0_18px_rgba(245,158,11,0.45)] transition-all duration-300 cursor-pointer absolute top-7 z-50 group"
        :class="lang === 'ar' ? '-left-3.5' : '-right-3.5'"
      >
        <ChevronLeft 
          class="w-4 h-4 transition-transform duration-500"
          :class="[
            lang === 'ar' 
              ? (isSidebarCollapsed ? 'rotate-0' : 'rotate-180') 
              : (isSidebarCollapsed ? 'rotate-180' : 'rotate-0')
          ]"
        />
      </button>

      <!-- Brand & Admin Persona Header with Mini Neon Eclipse Ring -->
      <div 
        class="border-b border-white/[0.07] transition-all duration-500"
        :class="isSidebarCollapsed ? 'p-4 md:px-2 md:py-5' : 'p-5'"
      >
        <div 
          class="flex items-center"
          :class="isSidebarCollapsed ? 'justify-between md:justify-center' : 'justify-between'"
        >
          <div class="flex items-center gap-3.5">
            <!-- Avatar with Mini Neon Eclipse Ring -->
            <div class="relative shrink-0 group cursor-pointer" @click="toggleSidebar">
              <div class="absolute -inset-1 rounded-2xl bg-gradient-to-tr from-[#ff5500] via-amber-400 to-[#00f2fe] opacity-75 blur-[4px] group-hover:opacity-100 transition-opacity"></div>
              <div class="relative w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#ff5500] via-amber-400 to-[#00f2fe] p-[1.5px] shadow-[0_0_20px_rgba(245,158,11,0.4)]">
                <img 
                  :src="resolveProfilePortrait(profileState)" 
                  alt="Mohammed Alhwaiti" 
                  class="w-full h-full object-cover rounded-[14px] bg-[#060913]"
                  @error="(e) => e.target.style.display = 'none'"
                />
              </div>
              <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-[#060913] shadow-[0_0_10px_rgba(52,211,153,0.9)]"></span>
            </div>

            <!-- Brand Title (Hidden on desktop when collapsed) -->
            <div 
              class="overflow-hidden transition-all duration-300"
              :class="isSidebarCollapsed ? 'md:hidden' : 'block'"
            >
              <div class="flex items-center gap-2">
                <span class="text-sm font-black tracking-tight text-white whitespace-nowrap">
                  {{ t.brandTitle }}
                </span>
                <span class="px-1.5 py-0.5 rounded-md bg-amber-500/15 text-amber-300 border border-amber-500/40 text-[9px] font-mono font-bold shadow-[0_0_10px_rgba(245,158,11,0.25)]">
                  V22
                </span>
              </div>
              <p class="text-[11px] font-mono text-[#00f2fe]/80 truncate mt-0.5">
                {{ t.sanctumActive }}
              </p>
            </div>
          </div>

          <!-- Close button on mobile -->
          <button 
            @click="isMobileMenuOpen = false" 
            class="md:hidden text-slate-400 hover:text-white p-1.5 rounded-xl bg-white/[0.04] border border-white/[0.08]"
          >
            <X class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- Navigation Links -->
      <nav 
        class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar transition-all duration-500"
        :class="isSidebarCollapsed ? 'p-3 md:px-2.5 md:py-4 space-y-2' : 'p-4 space-y-1.5'"
      >
        <!-- Section Group Header -->
        <div 
          class="px-3 pb-2 text-[10px] font-mono uppercase tracking-[0.18em] text-slate-500 transition-opacity duration-300"
          :class="isSidebarCollapsed ? 'md:hidden' : 'block'"
        >
          {{ t.coreAdminHeader }}
        </div>

        <router-link
          v-for="item in navItems"
          :key="item.path"
          :to="item.path"
          @click="isMobileMenuOpen = false"
          class="group relative flex items-center rounded-2xl text-xs font-medium transition-all duration-300"
          :class="[
            isSidebarCollapsed ? 'px-3.5 py-3 md:p-0 md:w-11 md:h-11 md:mx-auto md:justify-center' : ' justify-between px-3.5 py-3',
            isCurrentRoute(item.path) 
              ? 'bg-gradient-to-r from-amber-500/20 to-orange-500/5 border border-amber-500/50 text-amber-300 shadow-[0_0_25px_rgba(245,158,11,0.2)]' 
              : 'text-slate-400 hover:text-slate-100 hover:bg-white/[0.04] border border-transparent hover:border-white/[0.08]'
          ]"
        >
          <!-- Glowing Vertical Laser Bar on Active Tab -->
          <span
            v-if="isCurrentRoute(item.path)"
            class="absolute top-2 bottom-2 w-1 rounded-full bg-gradient-to-b from-amber-300 via-[#ff5500] to-amber-500 shadow-[0_0_12px_#f59e0b]"
            :class="lang === 'ar' ? '-right-0.5' : '-left-0.5'"
          ></span>

          <div class="flex items-center gap-3" :class="isSidebarCollapsed ? 'md:gap-0' : ''">
            <component 
              :is="item.icon" 
              class="w-4 h-4 shrink-0 transition-transform duration-300 group-hover:scale-110"
              :class="isCurrentRoute(item.path) ? 'text-amber-400 drop-shadow-[0_0_8px_rgba(245,158,11,0.85)]' : 'text-slate-400 group-hover:text-amber-300'"
            />
            <span 
              class="tracking-wide whitespace-nowrap transition-all duration-300"
              :class="isSidebarCollapsed ? 'md:hidden' : 'block'"
            >
              {{ item.label }}
            </span>
          </div>

          <!-- Expanded Badge Count -->
          <span 
            v-if="item.badge" 
            class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold transition-colors"
            :class="[
              item.badgeClass || 'bg-slate-800 text-slate-300',
              isSidebarCollapsed ? 'md:hidden' : 'inline-flex'
            ]"
          >
            {{ item.badge }}
          </span>

          <!-- Collapsed Compact Corner Badge -->
          <span
            v-if="item.compactBadge && isSidebarCollapsed"
            class="hidden md:flex absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full text-[9px] font-mono font-extrabold items-center justify-center border border-[#060913] shadow-[0_0_10px_rgba(245,158,11,0.5)]"
            :class="item.compactBadgeClass || 'bg-amber-500 text-slate-950'"
          >
            {{ item.compactBadge }}
          </span>

          <!-- Floating Glass Tooltip on Hover (When Collapsed on Desktop) -->
          <div
            v-if="isSidebarCollapsed"
            class="hidden md:flex items-center gap-2 pointer-events-none opacity-0 group-hover:opacity-100 transition-all duration-200 absolute z-50 px-3 py-1.5 rounded-xl bg-[#090d1a]/95 backdrop-blur-xl border border-amber-500/40 text-xs font-mono text-white whitespace-nowrap shadow-[0_10px_30px_rgba(0,0,0,0.8),0_0_15px_rgba(245,158,11,0.2)]"
            :class="lang === 'ar' ? 'right-full mr-3' : 'left-full ml-3'"
          >
            <span>{{ item.label }}</span>
            <span v-if="item.badge" class="px-1.5 py-0.2 rounded bg-amber-500/20 text-amber-300 text-[10px]">
              {{ item.badge }}
            </span>
          </div>
        </router-link>
      </nav>

      <!-- ===================================================================== -->
      <!-- SINGLE CLEAN FOOTER ACTION BLOCK (NO DUPLICATION)                     -->
      <!-- ===================================================================== -->
      <div 
        class="border-t border-white/[0.07] bg-[#04060c]/60 space-y-2 transition-all duration-500"
        :class="isSidebarCollapsed ? 'p-3 md:px-2.5 md:py-3' : 'p-4'"
      >
        <!-- View Live Site -->
        <a 
          href="/" 
          target="_blank"
          rel="noopener noreferrer"
          class="group relative flex items-center rounded-2xl bg-white/[0.03] hover:bg-amber-500/10 border border-white/[0.08] hover:border-amber-500/40 text-xs font-mono text-slate-300 hover:text-amber-300 transition-all duration-300"
          :class="isSidebarCollapsed ? 'justify-between px-3.5 py-2.5 md:p-0 md:w-11 md:h-11 md:mx-auto md:justify-center' : 'justify-between px-3.5 py-2.5'"
        >
          <span class="flex items-center gap-2.5" :class="isSidebarCollapsed ? 'md:gap-0' : ''">
            <ExternalLink class="w-4 h-4 text-amber-400 shrink-0 group-hover:scale-110 transition-transform" />
            <span :class="isSidebarCollapsed ? 'md:hidden' : 'inline'">{{ t.viewLiveSite }}</span>
          </span>
          <span 
            class="text-[10px] text-[#00f2fe] bg-[#00f2fe]/10 border border-[#00f2fe]/30 px-1.5 py-0.5 rounded-md"
            :class="isSidebarCollapsed ? 'md:hidden' : 'inline'"
          >
            {{ t.publicBadge }}
          </span>

          <!-- Collapsed Tooltip -->
          <div
            v-if="isSidebarCollapsed"
            class="hidden md:flex items-center gap-2 pointer-events-none opacity-0 group-hover:opacity-100 transition-all duration-200 absolute z-50 px-3 py-1.5 rounded-xl bg-[#090d1a]/95 backdrop-blur-xl border border-amber-500/40 text-xs font-mono text-amber-300 whitespace-nowrap shadow-xl"
            :class="lang === 'ar' ? 'right-full mr-3' : 'left-full ml-3'"
          >
            {{ t.viewLiveSite }}
          </div>
        </a>

        <!-- Disconnect Session -->
        <button
          type="button"
          @click="handleLogout"
          class="group relative flex items-center w-full rounded-2xl bg-red-950/25 hover:bg-red-950/55 border border-red-500/25 hover:border-red-500/50 text-xs font-mono text-red-400 hover:text-red-200 transition-all duration-300 cursor-pointer"
          :class="isSidebarCollapsed ? 'justify-between px-3.5 py-2.5 md:p-0 md:w-11 md:h-11 md:mx-auto md:justify-center' : 'justify-between px-3.5 py-2.5'"
        >
          <span class="flex items-center gap-2.5" :class="isSidebarCollapsed ? 'md:gap-0' : ''">
            <LogOut class="w-4 h-4 shrink-0 group-hover:scale-110 transition-transform" />
            <span :class="isSidebarCollapsed ? 'md:hidden' : 'inline'">{{ t.disconnectSession }}</span>
          </span>
          <span 
            class="text-[10px] text-red-400/80 font-mono"
            :class="isSidebarCollapsed ? 'md:hidden' : 'inline'"
          >
            {{ t.exitBadge }}
          </span>

          <!-- Collapsed Tooltip -->
          <div
            v-if="isSidebarCollapsed"
            class="hidden md:flex items-center gap-2 pointer-events-none opacity-0 group-hover:opacity-100 transition-all duration-200 absolute z-50 px-3 py-1.5 rounded-xl bg-red-950/95 backdrop-blur-xl border border-red-500/40 text-xs font-mono text-red-200 whitespace-nowrap shadow-xl"
            :class="lang === 'ar' ? 'right-full mr-3' : 'left-full ml-3'"
          >
            {{ t.disconnectSession }}
          </div>
        </button>
      </div>
    </aside>

    <!-- ========================================================================= -->
    <!-- MAIN WORKSPACE CONTAINER (Smoothly adapts to Sidebar & RTL/LTR)           -->
    <!-- ========================================================================= -->
    <div 
      class="flex-1 flex flex-col min-h-screen relative z-10 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
      :class="[
        lang === 'ar'
          ? (isSidebarCollapsed ? 'md:pr-20' : 'md:pr-72')
          : (isSidebarCollapsed ? 'md:pl-20' : 'md:pl-72')
      ]"
    >
      
      <!-- Top Telemetry Glassmorphic Header Bar -->
      <header class="sticky top-0 z-30 h-16 backdrop-blur-2xl bg-[#060913]/80 border-b border-white/[0.08] px-4 sm:px-8 flex items-center justify-between shadow-[0_10px_30px_rgba(0,0,0,0.4)]">
        
        <!-- Left/Start: Mobile Menu Toggle, Desktop Sidebar Collapse Toggle & Breadcrumbs -->
        <div class="flex items-center gap-3">
          <!-- Mobile Drawer Trigger -->
          <button 
            type="button"
            @click="isMobileMenuOpen = true" 
            class="md:hidden p-2 rounded-xl bg-white/[0.04] text-slate-300 hover:text-amber-400 border border-white/[0.08]"
            aria-label="Open Navigation Menu"
          >
            <Menu class="w-5 h-5" />
          </button>

          <!-- Desktop Sidebar Collapse/Expand Trigger in Header -->
          <button
            type="button"
            @click="toggleSidebar"
            :title="isSidebarCollapsed ? t.expandSidebar : t.collapseSidebar"
            class="hidden md:inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white/[0.03] hover:bg-amber-500/15 border border-white/[0.08] hover:border-amber-500/40 text-slate-300 hover:text-amber-300 transition-all cursor-pointer"
          >
            <PanelLeftClose v-if="!isSidebarCollapsed" class="w-4 h-4" />
            <PanelLeftOpen v-else class="w-4 h-4 text-amber-400" />
          </button>

          <!-- Dynamic Breadcrumb -->
          <div class="hidden sm:flex items-center gap-2 text-xs font-mono text-slate-400">
            <span class="text-slate-500">{{ t.adminBreadcrumb }}</span>
            <span class="text-slate-600">/</span>
            <span class="text-amber-400 font-semibold tracking-wide">{{ currentRouteName }}</span>
          </div>
        </div>

        <!-- Right/End: Sanctum Pulse Pill, Language Switcher (AR/EN), Live Clock & Inquiries -->
        <div class="flex items-center gap-2.5 sm:gap-3.5">
          
          <!-- Live Sanctum API Pulse Pill -->
          <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/[0.03] border border-emerald-500/30 text-[11px] font-mono shadow-[0_0_15px_rgba(16,185,129,0.1)]">
            <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.9)] animate-pulse"></span>
            <span class="text-emerald-300">{{ t.sanctumStatus }}</span>
          </div>

          <!-- Prominent Bilingual Language Switcher Pill (🌐 عربي / EN) -->
          <button
            type="button"
            @click="toggleLang"
            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-gradient-to-r from-amber-500/15 via-orange-500/10 to-[#00f2fe]/15 hover:from-amber-500/25 hover:to-[#00f2fe]/25 border border-amber-500/40 hover:border-amber-400 text-xs font-mono font-bold text-amber-300 hover:text-white shadow-[0_0_20px_rgba(245,158,11,0.2)] transition-all duration-300 cursor-pointer"
            :title="lang === 'en' ? 'التبديل إلى العربية (RTL)' : 'Switch to English (LTR)'"
          >
            <Globe class="w-3.5 h-3.5 text-[#00f2fe]" />
            <span>{{ lang === 'en' ? 'عربي' : 'EN' }}</span>
          </button>

          <!-- Real-Time Digital Clock -->
          <div 
            dir="ltr"
            class="text-xs font-mono text-slate-300 bg-white/[0.03] px-3 py-1.5 rounded-xl border border-white/[0.08]"
          >
            {{ currentTime }}
          </div>

          <!-- Quick Messages Indicator -->
          <router-link
            to="/admin/messages"
            class="relative p-2 rounded-xl bg-white/[0.03] hover:bg-amber-500/15 border border-white/[0.08] hover:border-amber-500/40 text-slate-300 hover:text-amber-300 transition-all"
            :title="t.navMessages"
          >
            <Mail class="w-4 h-4" />
            <span 
              v-if="unreadCount > 0" 
              class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-gradient-to-r from-amber-400 to-[#ff5500] text-slate-950 font-mono font-extrabold text-[9px] flex items-center justify-center shadow-[0_0_10px_rgba(245,158,11,0.9)]"
            >
              {{ unreadCount }}
            </span>
          </router-link>

        </div>
      </header>

      <!-- Content Area with Smooth Route Transitions -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8">
        <router-view v-slot="{ Component }">
          <transition 
            name="fade-slide" 
            mode="out-in"
          >
            <component :is="Component" @stats-updated="refreshCounts" />
          </transition>
        </router-view>
      </main>

    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { 
  LayoutDashboard, 
  FolderGit2, 
  Cpu, 
  Award,
  BookOpen,
  Briefcase, 
  Share2, 
  Mail, 
  UserCheck, 
  ExternalLink, 
  LogOut, 
  Menu, 
  X,
  ChevronLeft,
  PanelLeftClose,
  PanelLeftOpen,
  Globe
} from 'lucide-vue-next'
import { adminApi } from '../../services/adminApi'
import { resolveAssetUrl } from '../../services/portfolioService'
import { profileState, resolveProfilePortrait } from '../../services/profileState'
import { lang, isSidebarCollapsed, toggleLang, toggleSidebar, t } from '../../services/adminI18n'

const route = useRoute()
const router = useRouter()

const isMobileMenuOpen = ref(false)
const currentTime = ref('')
const unreadCount = ref(0)
const projectsCount = ref(3)
const skillsCount = ref(11)
const certsCount = ref(3)
const articlesCount = ref(0)
const socialLinksCount = ref(9)
const adminAvatar = ref('/mohammed-portrait.png')
let clockInterval = null

const updateClock = () => {
  const now = new Date()
  currentTime.value = now.toLocaleTimeString('en-US', { hour12: false })
}

const refreshCounts = async () => {
  try {
    const [stats, profileRes] = await Promise.all([
      adminApi.getOverviewStats(),
      adminApi.getProfile().catch(() => null)
    ])
    unreadCount.value = stats.unreadInquiries ?? 0
    projectsCount.value = stats.totalProjects ?? 3
    skillsCount.value = stats.totalSkills ?? 11
    certsCount.value = stats.totalCertificates ?? 3
    articlesCount.value = stats.totalArticles ?? 0
    socialLinksCount.value = stats.totalSocialLinks ?? 9
    if (profileRes) {
      const p = profileRes.data || profileRes
      if (p?.portrait || p?.avatar) {
        adminAvatar.value = p.portrait || p.avatar
      }
    }
  } catch (e) {
    // Keep current counts on error
  }
}

onMounted(() => {
  updateClock()
  clockInterval = setInterval(updateClock, 1000)
  refreshCounts()
})

onBeforeUnmount(() => {
  if (clockInterval) clearInterval(clockInterval)
})

const isCurrentRoute = (path) => {
  return route.path === path || route.path.startsWith(path + '/')
}

const currentRouteName = computed(() => {
  if (route.path.includes('/projects')) return t.value.navProjects
  if (route.path.includes('/skills')) return t.value.navSkills
  if (route.path.includes('/certificates')) return t.value.navCertificates
  if (route.path.includes('/articles')) return t.value.navArticles
  if (route.path.includes('/about')) return t.value.navAbout
  if (route.path.includes('/social-links')) return t.value.navSocial
  if (route.path.includes('/messages')) return t.value.navMessages
  if (route.path.includes('/profile')) return t.value.navProfile
  return t.value.navOverview
})

const navItems = computed(() => [
  { 
    path: '/admin/overview', 
    label: t.value.navOverview, 
    icon: LayoutDashboard 
  },
  { 
    path: '/admin/projects', 
    label: t.value.navProjects, 
    icon: FolderGit2,
    badge: projectsCount.value.toString(),
    compactBadge: projectsCount.value.toString(),
    badgeClass: 'bg-amber-500/20 text-amber-300 border border-amber-500/40',
    compactBadgeClass: 'bg-amber-500 text-slate-950'
  },
  { 
    path: '/admin/skills', 
    label: t.value.navSkills, 
    icon: Cpu,
    badge: skillsCount.value.toString(),
    compactBadge: skillsCount.value.toString(),
    badgeClass: 'bg-orange-500/20 text-orange-300 border border-orange-500/40',
    compactBadgeClass: 'bg-orange-500 text-slate-950'
  },
  { 
    path: '/admin/certificates', 
    label: t.value.navCertificates, 
    icon: Award,
    badge: certsCount.value.toString(),
    compactBadge: certsCount.value.toString(),
    badgeClass: 'bg-[#00f2fe]/15 text-[#00f2fe] border border-[#00f2fe]/35',
    compactBadgeClass: 'bg-[#00f2fe] text-slate-950'
  },
  { 
    path: '/admin/articles', 
    label: t.value.navArticles, 
    icon: BookOpen,
    badge: articlesCount.value.toString(),
    compactBadge: articlesCount.value.toString(),
    badgeClass: 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/35',
    compactBadgeClass: 'bg-cyan-400 text-slate-950'
  },
  { 
    path: '/admin/about', 
    label: t.value.navAbout, 
    icon: Briefcase 
  },
  { 
    path: '/admin/social-links', 
    label: t.value.navSocial, 
    icon: Share2,
    badge: socialLinksCount.value.toString(),
    compactBadge: socialLinksCount.value.toString(),
    badgeClass: 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/35',
    compactBadgeClass: 'bg-emerald-400 text-slate-950'
  },
  { 
    path: '/admin/messages', 
    label: t.value.navMessages, 
    icon: Mail,
    badge: unreadCount.value > 0 ? `${unreadCount.value} ${t.value.newBadge}` : null,
    compactBadge: unreadCount.value > 0 ? unreadCount.value.toString() : null,
    badgeClass: 'bg-amber-500/25 text-amber-200 border border-amber-400/50 animate-pulse',
    compactBadgeClass: 'bg-[#ff5500] text-white animate-pulse'
  },
  { 
    path: '/admin/profile', 
    label: t.value.navProfile, 
    icon: UserCheck 
  }
])

const handleLogout = async () => {
  try {
    await adminApi.logout()
  } catch (e) {
    // Continue even if network error
  }
  localStorage.removeItem('auth_token')
  localStorage.removeItem('admin_user')
  router.push('/admin/login')
}
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: opacity 0.25s ease, transform 0.25s ease;
}

.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(8px);
}

.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

.admin-perspective-floor {
  background-image: 
    linear-gradient(to right, rgba(245, 158, 11, 0.08) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(0, 242, 254, 0.08) 1px, transparent 1px);
  background-size: 3.5rem 3.5rem;
  transform: perspective(600px) rotateX(62deg) translateY(30%);
  transform-origin: bottom center;
  mask-image: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent 85%);
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(245, 158, 11, 0.2);
  border-radius: 4px;
}
</style>
