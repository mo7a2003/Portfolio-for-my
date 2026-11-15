<template>
  <div class="min-h-screen relative bg-[#020617] text-slate-100 selection:bg-cyan-500 selection:text-black p-4 sm:p-8">
    
    <!-- Background Ambient Glow Mesh -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
      <div class="absolute top-10 right-10 w-96 h-96 rounded-full bg-cyan-500/10 blur-[130px]"></div>
      <div class="absolute bottom-10 left-10 w-96 h-96 rounded-full bg-blue-600/10 blur-[140px]"></div>
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#082f4915_1px,transparent_1px),linear-gradient(to_bottom,#082f4915_1px,transparent_1px)] bg-[size:3rem_3rem] [mask-image:radial-gradient(ellipse_70%_60%_at_50%_50%,#000_70%,transparent_100%)]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto space-y-8">
      
      <!-- Top Navigation Bar -->
      <header class="backdrop-blur-xl bg-slate-900/60 border border-slate-800 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl">
        
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/40 flex items-center justify-center text-cyan-400">
            <LayoutDashboard class="w-5 h-5" />
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h1 class="text-base sm:text-lg font-bold text-white tracking-wide">
                Alhwaiti Core Dashboard
              </h1>
              <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-[10px] font-mono text-emerald-300 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Authorized
              </span>
            </div>
            <p class="text-xs text-slate-400 font-mono">
              Role: {{ adminUser.role || 'Super Admin' }} • {{ adminUser.email || 'admin@alhwaiti.dev' }}
            </p>
          </div>
        </div>

        <!-- Header Actions -->
        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
          <router-link
            to="/"
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 hover:border-cyan-500/40 text-xs font-mono text-slate-300 hover:text-cyan-300 transition-all cursor-pointer"
          >
            <ExternalLink class="w-3.5 h-3.5" />
            <span>Public Portfolio</span>
          </router-link>

          <button
            @click="handleLogout"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-950/40 hover:bg-red-900/60 border border-red-500/40 hover:border-red-400 text-xs font-mono text-red-300 hover:text-red-100 transition-all cursor-pointer shadow-[0_0_15px_rgba(239,68,68,0.15)]"
          >
            <LogOut class="w-3.5 h-3.5" />
            <span>Logout</span>
          </button>
        </div>

      </header>

      <!-- Security & Auth Token Status Banner -->
      <section class="backdrop-blur-xl bg-slate-900/50 border border-cyan-500/30 rounded-2xl p-6 shadow-[0_0_40px_rgba(6,182,212,0.1)]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 text-xs font-mono text-cyan-400 mb-1">
              <ShieldCheck class="w-4 h-4" />
              <span>NAVIGATION GUARD VERIFIED // 200 OK</span>
            </div>
            <h2 class="text-lg font-bold text-white">
              Authenticated Session Active
            </h2>
            <p class="text-xs text-slate-400 font-mono mt-1 break-all">
              Stored Token: <code class="text-cyan-300 bg-slate-950 px-2 py-0.5 rounded border border-cyan-500/20">{{ currentToken || 'None' }}</code>
            </p>
          </div>

          <div class="flex items-center gap-2">
            <button
              @click="testRouteProtection"
              class="px-4 py-2 rounded-xl bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/40 text-xs font-mono text-cyan-300 transition-all cursor-pointer"
            >
              Test Guard (Wipe Token & Reload)
            </button>
          </div>
        </div>
      </section>

      <!-- Key Dashboard Metrics Bento Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="backdrop-blur-xl bg-slate-900/40 border border-slate-800 rounded-2xl p-5 hover:border-cyan-500/40 transition-all group">
          <div class="flex items-center justify-between text-slate-400 mb-3">
            <span class="text-xs font-mono">Portfolio Visitors</span>
            <Users class="w-4 h-4 text-cyan-400 group-hover:scale-110 transition-transform" />
          </div>
          <div class="text-2xl font-bold text-white font-mono">14,280</div>
          <div class="text-[11px] text-emerald-400 font-mono mt-1">+18.4% this month</div>
        </div>

        <div class="backdrop-blur-xl bg-slate-900/40 border border-slate-800 rounded-2xl p-5 hover:border-cyan-500/40 transition-all group">
          <div class="flex items-center justify-between text-slate-400 mb-3">
            <span class="text-xs font-mono">Managed Projects</span>
            <FolderGit2 class="w-4 h-4 text-cyan-400 group-hover:scale-110 transition-transform" />
          </div>
          <div class="text-2xl font-bold text-white font-mono">6 Active</div>
          <div class="text-[11px] text-cyan-400 font-mono mt-1">100% CI/CD Deployed</div>
        </div>

        <div class="backdrop-blur-xl bg-slate-900/40 border border-slate-800 rounded-2xl p-5 hover:border-cyan-500/40 transition-all group">
          <div class="flex items-center justify-between text-slate-400 mb-3">
            <span class="text-xs font-mono">New Inquiries</span>
            <Inbox class="w-4 h-4 text-cyan-400 group-hover:scale-110 transition-transform" />
          </div>
          <div class="text-2xl font-bold text-white font-mono">19 Unread</div>
          <div class="text-[11px] text-gold-400 font-mono mt-1">High conversion rate</div>
        </div>

        <div class="backdrop-blur-xl bg-slate-900/40 border border-slate-800 rounded-2xl p-5 hover:border-cyan-500/40 transition-all group">
          <div class="flex items-center justify-between text-slate-400 mb-3">
            <span class="text-xs font-mono">System Health</span>
            <Cpu class="w-4 h-4 text-emerald-400 group-hover:scale-110 transition-transform" />
          </div>
          <div class="text-2xl font-bold text-emerald-400 font-mono">99.98%</div>
          <div class="text-[11px] text-slate-400 font-mono mt-1">Vite + Vue 3 Optimus</div>
        </div>

      </div>

      <!-- Quick Actions & Management Table -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Recent System Notifications -->
        <div class="lg:col-span-2 backdrop-blur-xl bg-slate-900/40 border border-slate-800 rounded-2xl p-6">
          <h3 class="text-sm font-bold font-mono text-white mb-4 flex items-center gap-2">
            <Activity class="w-4 h-4 text-cyan-400" />
            Audit Log & Security Feed
          </h3>
          <div class="space-y-3">
            <div 
              v-for="(item, idx) in auditLogs" 
              :key="idx"
              class="p-3.5 rounded-xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between text-xs font-mono"
            >
              <div class="flex items-center gap-3">
                <span class="w-2 h-2 rounded-full" :class="item.dot"></span>
                <span class="text-slate-300">{{ item.action }}</span>
              </div>
              <span class="text-slate-500">{{ item.time }}</span>
            </div>
          </div>
        </div>

        <!-- Right: Admin Quick Operations -->
        <div class="backdrop-blur-xl bg-slate-900/40 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
          <div>
            <h3 class="text-sm font-bold font-mono text-white mb-4 flex items-center gap-2">
              <KeyRound class="w-4 h-4 text-cyan-400" />
              Quick Actions
            </h3>
            <div class="space-y-2.5">
              <button class="w-full text-left px-4 py-2.5 rounded-xl bg-slate-950/70 hover:bg-slate-800 border border-slate-800 hover:border-cyan-500/40 text-xs font-mono text-slate-300 transition-all flex items-center justify-between">
                <span>Manage Project Portfolio</span>
                <ChevronRight class="w-3.5 h-3.5 text-cyan-400" />
              </button>
              <button class="w-full text-left px-4 py-2.5 rounded-xl bg-slate-950/70 hover:bg-slate-800 border border-slate-800 hover:border-cyan-500/40 text-xs font-mono text-slate-300 transition-all flex items-center justify-between">
                <span>Review Contact Messages</span>
                <ChevronRight class="w-3.5 h-3.5 text-cyan-400" />
              </button>
              <button class="w-full text-left px-4 py-2.5 rounded-xl bg-slate-950/70 hover:bg-slate-800 border border-slate-800 hover:border-cyan-500/40 text-xs font-mono text-slate-300 transition-all flex items-center justify-between">
                <span>Update Tech Matrix</span>
                <ChevronRight class="w-3.5 h-3.5 text-cyan-400" />
              </button>
            </div>
          </div>

          <div class="mt-6 pt-4 border-t border-slate-800 text-[11px] font-mono text-slate-500 flex items-center justify-between">
            <span>Client IP: Protected</span>
            <span>Gateway: SSL Valid</span>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { 
  LayoutDashboard, 
  ShieldCheck, 
  LogOut, 
  ExternalLink, 
  Users, 
  FolderGit2, 
  Inbox, 
  Cpu, 
  Activity, 
  KeyRound, 
  ChevronRight 
} from 'lucide-vue-next'

const router = useRouter()
const currentToken = ref('')
const adminUser = ref({})

const auditLogs = ref([
  { action: 'Admin logged into portal from secure session', time: 'Just now', dot: 'bg-emerald-400' },
  { action: 'Navigation Guard verified auth_token in localStorage', time: '1m ago', dot: 'bg-cyan-400' },
  { action: 'Database sync: 6 Selected projects loaded', time: '12m ago', dot: 'bg-blue-400' },
  { action: 'Web Audio API sfx initialized successfully', time: '25m ago', dot: 'bg-slate-500' }
])

onMounted(() => {
  currentToken.value = localStorage.getItem('auth_token') || ''
  try {
    adminUser.value = JSON.parse(localStorage.getItem('admin_user') || '{}')
  } catch (e) {
    adminUser.value = {}
  }
})

const handleLogout = () => {
  localStorage.removeItem('auth_token')
  localStorage.removeItem('admin_user')
  router.push('/admin/login')
}

const testRouteProtection = () => {
  localStorage.removeItem('auth_token')
  localStorage.removeItem('admin_user')
  // Try to re-navigate to /admin/dashboard -> guard should intercept & bounce to /admin/login
  router.push('/admin/dashboard')
}
</script>

