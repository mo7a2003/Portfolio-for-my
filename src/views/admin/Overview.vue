<template>
  <div class="space-y-8">
    
    <!-- ========================================================================= -->
    <!-- WELCOME HEADER & QUICK COMMAND ACTIONS                                    -->
    <!-- ========================================================================= -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/35 text-xs font-mono text-amber-300 mb-2.5 shadow-[0_0_20px_rgba(245,158,11,0.18)]">
          <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse shadow-[0_0_8px_#f59e0b]"></span>
          <span>{{ t.commandCenterBadge }}</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
          {{ t.overviewTitle }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 font-mono mt-1">
          {{ t.overviewSubtitle }}
        </p>
      </div>

      <!-- Quick Action Buttons -->
      <div class="flex items-center gap-3">
        <router-link
          to="/admin/projects"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-400 via-[#ff5500] to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-extrabold text-xs font-mono transition-all shadow-[0_0_25px_rgba(245,158,11,0.45)] hover:shadow-[0_0_35px_rgba(255,85,0,0.65)] hover:-translate-y-0.5 cursor-pointer"
        >
          <span>{{ t.newProjectBtn }}</span>
        </router-link>

        <button
          type="button"
          @click="refreshData"
          :disabled="isRefreshing"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/[0.03] hover:bg-white/[0.07] border border-white/[0.1] hover:border-[#00f2fe]/50 text-xs font-mono text-slate-300 hover:text-[#00f2fe] transition-all cursor-pointer disabled:opacity-50 shadow-[0_0_20px_rgba(0,0,0,0.4)]"
          :title="t.syncDataBtn"
        >
          <RefreshCw class="w-3.5 h-3.5 text-[#00f2fe]" :class="{ 'animate-spin': isRefreshing }" />
          <span>{{ isRefreshing ? t.syncingBtn : t.syncDataBtn }}</span>
        </button>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- FULL STACK MICROSERVICES MATRIX BANNER (WITH CYAN X-RAY LASER BEAM)       -->
    <!-- ========================================================================= -->
    <div class="relative overflow-hidden rounded-3xl backdrop-blur-2xl bg-[#070b16]/80 border border-amber-500/35 p-5 sm:p-6 shadow-[0_20px_50px_rgba(0,0,0,0.7),0_0_35px_rgba(245,158,11,0.15)] group">
      
      <!-- Subtle Cyan X-Ray Blueprint Grid Inside Banner -->
      <div class="absolute inset-0 pointer-events-none bg-[linear-gradient(to_right,rgba(0,242,254,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,242,254,0.04)_1px,transparent_1px)] bg-[size:2rem_2rem]"></div>

      <!-- Sweeping Vertical Cyan X-Ray Laser Beam -->
      <div class="xray-laser-line pointer-events-none"></div>

      <!-- Top-Right X-Ray Telemetry HUD Tag -->
      <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        
        <div class="flex items-start sm:items-center gap-4">
          <div class="relative w-13 h-13 rounded-2xl bg-gradient-to-br from-amber-500/20 via-[#ff5500]/15 to-[#00f2fe]/15 border border-amber-500/40 flex items-center justify-center text-amber-400 shrink-0 shadow-[0_0_25px_rgba(245,158,11,0.3)] p-3">
            <Activity class="w-6 h-6 animate-pulse text-amber-300" />
            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-[#00f2fe] shadow-[0_0_10px_#00f2fe]"></span>
          </div>
          <div>
            <div class="flex flex-wrap items-center gap-2.5">
              <span class="text-sm sm:text-base font-extrabold text-white tracking-tight">
                {{ t.matrixTitle }}
              </span>
              <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/15 border border-emerald-500/40 text-[10px] font-mono font-bold text-emerald-300 flex items-center gap-1.5 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                <span>{{ t.operationalBadge }}</span>
              </span>
              <span class="hidden sm:inline-flex px-2 py-0.5 rounded-md bg-[#00f2fe]/10 border border-[#00f2fe]/30 text-[9px] font-mono text-[#00f2fe]">
                {{ t.xrayHudLabel }}
              </span>
            </div>
            <p class="text-xs text-slate-400 font-mono mt-1">
              {{ t.matrixSubtitle }}
            </p>
          </div>
        </div>

        <!-- 4 Glassmorphic Telemetry Capsules -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs font-mono">
          <div class="px-3.5 py-2.5 rounded-2xl bg-[#04060c]/80 border border-white/[0.08] hover:border-amber-500/40 transition-colors shadow-inner">
            <span class="text-slate-500 text-[10px] block uppercase tracking-wider">{{ t.apiLatency }}</span>
            <span class="text-amber-400 font-extrabold text-sm" dir="ltr">{{ stats.avgLatency || '18ms' }}</span>
          </div>
          <div class="px-3.5 py-2.5 rounded-2xl bg-[#04060c]/80 border border-white/[0.08] hover:border-emerald-500/40 transition-colors shadow-inner">
            <span class="text-slate-500 text-[10px] block uppercase tracking-wider">{{ t.uptime }}</span>
            <span class="text-emerald-400 font-extrabold text-sm" dir="ltr">{{ stats.uptime || '99.99%' }}</span>
          </div>
          <div class="px-3.5 py-2.5 rounded-2xl bg-[#04060c]/80 border border-white/[0.08] hover:border-[#00f2fe]/40 transition-colors shadow-inner">
            <span class="text-slate-500 text-[10px] block uppercase tracking-wider">{{ t.sanctumAuth }}</span>
            <span class="text-[#00f2fe] font-extrabold text-sm">{{ t.encrypted }}</span>
          </div>
          <div class="px-3.5 py-2.5 rounded-2xl bg-[#04060c]/80 border border-white/[0.08] hover:border-amber-500/40 transition-colors shadow-inner">
            <span class="text-slate-500 text-[10px] block uppercase tracking-wider">{{ t.dbCluster }}</span>
            <span class="text-amber-300 font-extrabold text-sm">{{ t.connected }}</span>
          </div>
        </div>

      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 4 KPI BENTO CARDS (3D HOVER LIFT & 2PX BOTTOM NEON PROGRESS BARS)         -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      
      <!-- Card 1: Showcase Projects -->
      <router-link
        to="/admin/projects"
        class="kpi-bento-card group relative overflow-hidden backdrop-blur-2xl bg-[#070b16]/80 border border-white/[0.08] hover:border-amber-500/50 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1.5 shadow-[0_15px_35px_rgba(0,0,0,0.6)] hover:shadow-[0_20px_40px_rgba(245,158,11,0.2)]"
      >
        <div class="flex items-center justify-between text-slate-400 mb-3">
          <span class="text-xs font-mono font-semibold">{{ t.kpiProjectsTitle }}</span>
          <div class="w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/35 flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(245,158,11,0.25)]">
            <FolderGit2 class="w-4 h-4" />
          </div>
        </div>
        <div class="text-3xl font-black text-white font-mono tracking-tight">
          {{ stats.totalProjects ?? 3 }}
        </div>
        <div class="flex items-center justify-between mt-2.5 text-[11px] font-mono">
          <span class="text-emerald-400 flex items-center gap-1">
            <CheckCircle2 class="w-3.5 h-3.5" />
            <span>{{ stats.activeProjects ?? 3 }} {{ t.kpiProjectsSub }}</span>
          </span>
          <span class="text-slate-500 group-hover:text-amber-400 transition-colors">{{ t.kpiManage }}</span>
        </div>
        <!-- 2px Bottom Neon Progress Line -->
        <div class="absolute bottom-0 inset-x-0 h-[2px] bg-white/[0.04]">
          <div class="h-full w-[88%] bg-gradient-to-r from-amber-400 via-[#ff5500] to-amber-300 shadow-[0_0_12px_#f59e0b]"></div>
        </div>
      </router-link>

      <!-- Card 2: Contact Inquiries -->
      <router-link
        to="/admin/messages"
        class="kpi-bento-card group relative overflow-hidden backdrop-blur-2xl bg-[#070b16]/80 border border-white/[0.08] hover:border-[#ff5500]/50 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1.5 shadow-[0_15px_35px_rgba(0,0,0,0.6)] hover:shadow-[0_20px_40px_rgba(255,85,0,0.2)]"
      >
        <div class="flex items-center justify-between text-slate-400 mb-3">
          <span class="text-xs font-mono font-semibold">{{ t.kpiInquiriesTitle }}</span>
          <div class="w-9 h-9 rounded-xl bg-[#ff5500]/15 border border-[#ff5500]/35 flex items-center justify-center text-[#ff5500] group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(255,85,0,0.25)]">
            <Mail class="w-4 h-4" />
          </div>
        </div>
        <div class="text-3xl font-black text-white font-mono tracking-tight">
          {{ stats.totalInquiries ?? 0 }}
        </div>
        <div class="flex items-center justify-between mt-2.5 text-[11px] font-mono">
          <span class="text-amber-400 font-bold flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
            <span>{{ stats.unreadInquiries ?? 0 }} {{ t.kpiInquiriesSub }}</span>
          </span>
          <span class="text-slate-500 group-hover:text-amber-400 transition-colors">{{ t.kpiInbox }}</span>
        </div>
        <!-- 2px Bottom Neon Progress Line -->
        <div class="absolute bottom-0 inset-x-0 h-[2px] bg-white/[0.04]">
          <div class="h-full w-[72%] bg-gradient-to-r from-[#ff5500] via-amber-500 to-orange-400 shadow-[0_0_12px_#ff5500]"></div>
        </div>
      </router-link>

      <!-- Card 3: Total Page Visits -->
      <div class="kpi-bento-card group relative overflow-hidden backdrop-blur-2xl bg-[#070b16]/80 border border-white/[0.08] hover:border-[#00f2fe]/50 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1.5 shadow-[0_15px_35px_rgba(0,0,0,0.6)] hover:shadow-[0_20px_40px_rgba(0,242,254,0.18)]">
        <div class="flex items-center justify-between text-slate-400 mb-3">
          <span class="text-xs font-mono font-semibold">{{ t.kpiVisitsTitle }}</span>
          <div class="w-9 h-9 rounded-xl bg-[#00f2fe]/15 border border-[#00f2fe]/35 flex items-center justify-center text-[#00f2fe] group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(0,242,254,0.25)]">
            <Users class="w-4 h-4" />
          </div>
        </div>
        <div class="text-3xl font-black text-white font-mono tracking-tight" dir="ltr">
          {{ stats.portfolioViews != null ? Number(stats.portfolioViews).toLocaleString() : '0' }}
        </div>
        <div class="flex items-center justify-between mt-2.5 text-[11px] font-mono">
          <span class="text-emerald-400 font-medium">{{ stats.viewsGrowth || '0%' }} {{ t.kpiVisitsSub }}</span>
          <span class="text-slate-500">{{ t.kpiAnalytics }}</span>
        </div>
        <!-- 2px Bottom Neon Progress Line -->
        <div class="absolute bottom-0 inset-x-0 h-[2px] bg-white/[0.04]">
          <div class="h-full w-[92%] bg-gradient-to-r from-[#00f2fe] via-cyan-400 to-emerald-400 shadow-[0_0_12px_#00f2fe]"></div>
        </div>
      </div>

      <!-- Card 4: WebGL & 3D Framerate -->
      <div class="kpi-bento-card group relative overflow-hidden backdrop-blur-2xl bg-[#070b16]/80 border border-white/[0.08] hover:border-amber-500/50 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1.5 shadow-[0_15px_35px_rgba(0,0,0,0.6)] hover:shadow-[0_20px_40px_rgba(245,158,11,0.2)]">
        <div class="flex items-center justify-between text-slate-400 mb-3">
          <span class="text-xs font-mono font-semibold">{{ t.kpiFpsTitle }}</span>
          <div class="w-9 h-9 rounded-xl bg-amber-500/15 border border-amber-500/35 flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(245,158,11,0.25)]">
            <Cpu class="w-4 h-4" />
          </div>
        </div>
        <div class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-amber-300 via-amber-400 to-[#ff5500] font-mono tracking-tight" dir="ltr">
          60.0 FPS
        </div>
        <div class="flex items-center justify-between mt-2.5 text-[11px] font-mono">
          <span class="text-slate-400">{{ t.kpiFpsSub }}</span>
          <span class="text-emerald-400 font-semibold">{{ t.kpiSmooth }}</span>
        </div>
        <!-- 2px Bottom Neon Progress Line -->
        <div class="absolute bottom-0 inset-x-0 h-[2px] bg-white/[0.04]">
          <div class="h-full w-full bg-gradient-to-r from-amber-400 via-[#ff5500] to-[#00f2fe] shadow-[0_0_12px_#f59e0b]"></div>
        </div>
      </div>

    </div>

    <!-- ========================================================================= -->
    <!-- ACTIVE SHOWCASE PROJECTS TABLE (CYBER-BENTO FLOATING GLASS ROWS)          -->
    <!-- ========================================================================= -->
    <div class="backdrop-blur-2xl bg-[#070b16]/80 border border-white/[0.08] rounded-3xl p-5 sm:p-7 shadow-[0_25px_60px_rgba(0,0,0,0.75)]">
      
      <!-- Section Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-white/[0.07]">
        <div>
          <h2 class="text-base sm:text-lg font-extrabold text-white flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-amber-500/15 border border-amber-500/35 flex items-center justify-center text-amber-400">
              <FolderGit2 class="w-4 h-4" />
            </div>
            <span>{{ t.activeProjectsHeader }}</span>
          </h2>
          <p class="text-xs text-slate-400 font-mono mt-1">
            {{ t.activeProjectsSub }}
          </p>
        </div>

        <router-link
          to="/admin/projects"
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-xs font-mono text-amber-300 transition-all"
        >
          <span>{{ t.openProjectsStudio }}</span>
          <ChevronRight class="w-3.5 h-3.5" :class="lang === 'ar' ? 'rotate-180' : ''" />
        </router-link>
      </div>

      <!-- Column Header Strip (Desktop) -->
      <div class="hidden lg:grid lg:grid-cols-12 gap-4 px-4 pb-3 text-[11px] font-mono uppercase tracking-wider text-slate-500">
        <div class="col-span-4">{{ t.colProject }}</div>
        <div class="col-span-2">{{ t.colCategory }}</div>
        <div class="col-span-3">{{ t.colTechStack }}</div>
        <div class="col-span-1 text-center">{{ t.colStatus }}</div>
        <div class="col-span-2" :class="lang === 'ar' ? 'text-left' : 'text-right'">{{ t.colQuickActions }}</div>
      </div>

      <!-- Empty State -->
      <div v-if="projectsList.length === 0" class="py-12 text-center text-xs font-mono text-slate-400">
        {{ t.noProjectsFound }}
      </div>

      <!-- Floating Glassmorphic Cyber-Bento Rows -->
      <div v-else class="space-y-3">
        <div
          v-for="project in projectsList"
          :key="project.id"
          class="group relative rounded-2xl bg-[#04060c]/85 hover:bg-[#090d1a]/90 border border-white/[0.07] hover:border-amber-500/45 p-4 transition-all duration-300 hover:shadow-[0_10px_30px_rgba(0,0,0,0.6),0_0_25px_rgba(245,158,11,0.12)] grid grid-cols-1 lg:grid-cols-12 items-center gap-4"
        >
          <!-- 1. Project Mini Browser Frame + Title + Award Badge -->
          <div class="lg:col-span-4 flex items-center gap-3.5 min-w-0">
            <!-- Mini 3D Glass Browser Thumbnail Frame -->
            <div class="relative w-14 h-12 rounded-xl overflow-hidden bg-[#0b1020] border border-white/[0.12] group-hover:border-amber-500/50 shrink-0 flex flex-col shadow-inner">
              <!-- Mini Browser Top Traffic Dots Bar -->
              <div class="h-2.5 bg-black/60 border-b border-white/[0.06] px-1.5 flex items-center gap-1">
                <span class="w-1 h-1 rounded-full bg-red-400/80"></span>
                <span class="w-1 h-1 rounded-full bg-amber-400/80"></span>
                <span class="w-1 h-1 rounded-full bg-emerald-400/80"></span>
              </div>
              <!-- Project Image or Code Initials -->
              <div class="flex-1 relative flex items-center justify-center overflow-hidden">
                <img
                  v-if="project.image || project.preview_image || project.previewImage"
                  :src="resolveAssetUrl(project.image || project.preview_image || project.previewImage)"
                  :alt="project.title"
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                  @error="(e) => e.target.style.display = 'none'"
                />
                <span
                  v-else
                  class="font-mono font-extrabold text-[11px] uppercase"
                  :style="{ color: project.accentColor || '#f59e0b' }"
                >
                  {{ (project.title || 'PR').slice(0, 2) }}
                </span>
              </div>
            </div>

            <div class="min-w-0 flex-1">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="font-extrabold text-sm text-white group-hover:text-amber-300 transition-colors truncate">
                  {{ project.title }}
                </span>
                <!-- Golden / Cyan Award Badge -->
                <span 
                  v-if="project.badge || getDefaultBadge(project)" 
                  class="px-2 py-0.5 rounded-full text-[9px] font-mono font-semibold border shrink-0"
                  :class="getBadgeStyle(project)"
                >
                  {{ project.badge || getDefaultBadge(project) }}
                </span>
              </div>
              <div class="text-[11px] text-slate-400 font-mono truncate mt-0.5">
                {{ project.subtitle || project.tagline || project.description }}
              </div>
            </div>
          </div>

          <!-- 2. Category -->
          <div class="lg:col-span-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/[0.03] border border-white/[0.06] text-[11px] font-mono text-slate-300">
              <span class="w-1.5 h-1.5 rounded-full bg-[#00f2fe]"></span>
              <span class="truncate">{{ project.category || '3D WebGL' }}</span>
            </span>
          </div>

          <!-- 3. Numbered Tech Stack Cyber-Capsules -->
          <div class="lg:col-span-3 flex flex-wrap items-center gap-1.5">
            <span 
              v-for="(tech, i) in (project.tech || []).slice(0, 3)" 
              :key="i"
              class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-[#090d1a] border border-white/[0.08] group-hover:border-amber-500/30 text-slate-200 text-[10px] font-mono"
            >
              <span class="text-[9px] text-amber-400/80 font-bold">0{{ i + 1 }}</span>
              <span>{{ tech }}</span>
            </span>
            <span 
              v-if="(project.tech || []).length > 3" 
              class="px-1.5 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/30 text-[10px] font-mono text-amber-300"
            >
              +{{ project.tech.length - 3 }}
            </span>
          </div>

          <!-- 4. Interactive Status Toggle Pill -->
          <div class="lg:col-span-1 flex lg:justify-center">
            <button
              type="button"
              @click="toggleStatus(project)"
              class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono font-bold border transition-all cursor-pointer"
              :class="project.isActive !== false 
                ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-300 hover:bg-emerald-500/25 shadow-[0_0_15px_rgba(16,185,129,0.2)]' 
                : 'bg-slate-900 border-slate-700 text-slate-400 hover:bg-slate-800'"
            >
              <span>{{ project.isActive !== false ? t.statusActive : t.statusDraft }}</span>
            </button>
          </div>

          <!-- 5. Glowing Quick Action Buttons (Preview ↗ & Edit) -->
          <div 
            class="lg:col-span-2 flex items-center gap-2"
            :class="lang === 'ar' ? 'lg:justify-end' : 'lg:justify-end'"
          >
            <a
              v-if="project.liveUrl || project.live_url"
              :href="project.liveUrl || project.live_url"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-white/[0.04] hover:bg-[#00f2fe]/15 text-slate-300 hover:text-[#00f2fe] border border-white/[0.08] hover:border-[#00f2fe]/40 text-[11px] font-mono transition-all"
            >
              <span>{{ t.previewBtn }}</span>
            </a>
            <router-link
              to="/admin/projects"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/40 text-amber-300 text-[11px] font-mono font-bold shadow-[0_0_15px_rgba(245,158,11,0.15)] transition-all"
            >
              <span>{{ t.editBtn }}</span>
            </router-link>
          </div>

        </div>
      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { 
  Activity, 
  FolderGit2, 
  Mail, 
  Users, 
  Cpu, 
  CheckCircle2, 
  ChevronRight, 
  RefreshCw 
} from 'lucide-vue-next'
import { adminApi } from '../../services/adminApi'
import { resolveAssetUrl } from '../../services/portfolioService'
import { lang, t } from '../../services/adminI18n'

const emit = defineEmits(['stats-updated'])

const stats = ref({})
const projectsList = ref([])
const isRefreshing = ref(false)

const getDefaultBadge = (project) => {
  const title = (project.title || '').toLowerCase()
  if (title.includes('wafar')) return '🏆 Anan Award Winner'
  if (title.includes('vibe')) return '⚡ AI Powered'
  if (title.includes('wasla')) return '🌐 Multi-Service'
  return ''
}

const getBadgeStyle = (project) => {
  const title = (project.title || '').toLowerCase()
  if (title.includes('vibe')) {
    return 'border-[#00f2fe]/40 text-[#00f2fe] bg-[#00f2fe]/10'
  }
  return project.badgeColor || 'border-amber-500/40 text-amber-300 bg-amber-500/10'
}

const loadData = async () => {
  isRefreshing.value = true
  try {
    const projectsData = await adminApi.getProjects()
    projectsList.value = projectsData
    const statsData = await adminApi.getOverviewStats(projectsData)
    stats.value = statsData
    emit('stats-updated')
  } catch (err) {
    console.error('[Overview] Failed to load overview data:', err)
  } finally {
    isRefreshing.value = false
  }
}

const refreshData = async () => {
  isRefreshing.value = true
  await loadData()
  setTimeout(() => {
    isRefreshing.value = false
  }, 400)
}

const toggleStatus = async (project) => {
  const currentStatus = project.is_active !== undefined ? project.is_active : project.isActive !== false
  const newStatus = !currentStatus
  try {
    await adminApi.updateProject(project.id, { 
      is_active: newStatus, 
      isActive: newStatus 
    })
    project.is_active = newStatus
    project.isActive = newStatus
    localStorage.setItem('portfolio_sync_trigger', Date.now().toString())
    window.dispatchEvent(new CustomEvent('portfolio-updated'))
    emit('stats-updated')
  } catch (err) {
    console.error('Failed to toggle project status:', err)
  }
}

onMounted(() => {
  loadData()
})
</script>

<style scoped>
@keyframes xraySweep {
  0% {
    left: -10%;
    opacity: 0;
  }
  15% {
    opacity: 0.9;
  }
  85% {
    opacity: 0.9;
  }
  100% {
    left: 110%;
    opacity: 0;
  }
}

.xray-laser-line {
  position: absolute;
  top: 0;
  bottom: 0;
  width: 2px;
  background: linear-gradient(
    to bottom,
    transparent,
    #00f2fe,
    #f59e0b,
    #00f2fe,
    transparent
  );
  box-shadow: 0 0 18px 2px rgba(0, 242, 254, 0.75), 0 0 35px 6px rgba(245, 158, 11, 0.35);
  animation: xraySweep 6.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
  z-index: 5;
}
</style>
