<template>
  <div class="min-h-screen relative flex items-center justify-center p-4 sm:p-6 overflow-hidden bg-[#020617] text-slate-100 selection:bg-gold-500 selection:text-black">
    
    <!-- Background Ambient Glow Mesh (Gold & Amber Neon) -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
      <div class="absolute -top-40 -left-40 w-96 sm:w-[500px] h-96 sm:h-[500px] rounded-full bg-gold-500/15 blur-[120px] animate-pulse"></div>
      <div class="absolute -bottom-40 -right-40 w-96 sm:w-[550px] h-96 sm:h-[550px] rounded-full bg-amber-600/15 blur-[140px] animate-pulse" style="animation-duration: 7s;"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] rounded-full bg-gold-400/5 blur-[160px]"></div>
      
      <!-- Subtle Cyber Grid Lines -->
      <div class="absolute inset-0 bg-[linear-gradient(to_right,#b4530915_1px,transparent_1px),linear-gradient(to_bottom,#b4530915_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)]"></div>
    </div>

    <!-- Top Left Quick Back Button -->
    <router-link
      to="/"
      class="absolute top-6 left-6 z-20 group inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900/60 hover:bg-slate-800/80 border border-slate-800 hover:border-gold-500/40 text-slate-400 hover:text-gold-300 transition-all duration-300 backdrop-blur-md text-xs font-mono"
    >
      <ArrowLeft class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1 text-gold-400" />
      <span>Return to Portfolio</span>
    </router-link>

    <!-- Glassmorphic Auth Card Container -->
    <div class="relative z-10 w-full max-w-md my-8">
      
      <!-- Outer Gold Glow Ring Accent -->
      <div class="absolute -inset-0.5 rounded-3xl bg-gradient-to-r from-gold-500/40 via-amber-500/30 to-yellow-400/30 opacity-70 blur-xl group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>

      <div class="relative backdrop-blur-2xl bg-[#030712]/80 border border-gold-500/30 rounded-3xl p-8 sm:p-10 shadow-[0_0_60px_-15px_rgba(212,175,55,0.25)] ring-1 ring-white/10">
        
        <!-- Header Emblem & Cyber Title -->
        <div class="text-center mb-8">
          
          <!-- Holographic Security Badge -->
          <div class="relative inline-flex items-center justify-center mb-4">
            <div class="absolute inset-0 rounded-2xl bg-gold-500/20 blur-md animate-ping" style="animation-duration: 3s;"></div>
            <div class="relative w-16 h-16 rounded-2xl bg-slate-900/90 border border-gold-400/50 flex items-center justify-center shadow-[0_0_25px_rgba(212,175,55,0.35)] group-hover:scale-105 transition-transform duration-300">
              <ShieldCheck class="w-8 h-8 text-gold-400 drop-shadow-[0_0_10px_rgba(212,175,55,0.8)]" />
            </div>
          </div>

          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 border border-gold-500/30 text-[11px] font-mono text-gold-300 tracking-wider uppercase mb-2">
            <span class="w-1.5 h-1.5 rounded-full bg-gold-400 animate-pulse"></span>
            Encrypted Admin Gateway
          </div>

          <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mt-1">
            Admin Console
          </h1>
          <p class="text-xs sm:text-sm text-slate-400 mt-1 font-mono">
            Mohammed Alhwaiti // Master Node
          </p>
        </div>

        <!-- Feedback Alert Messages -->
        <transition
          enter-active-class="transition duration-300 ease-out"
          enter-from-class="opacity-0 -translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition duration-200 ease-in"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-2"
        >
          <div 
            v-if="errorMessage" 
            class="mb-6 p-3.5 rounded-xl bg-red-950/50 border border-red-500/40 text-red-200 text-xs flex items-start gap-2.5 backdrop-blur-md"
          >
            <AlertCircle class="w-4 h-4 text-red-400 shrink-0 mt-0.5" />
            <div class="flex-1 font-mono">{{ errorMessage }}</div>
          </div>
        </transition>

        <transition
          enter-active-class="transition duration-300 ease-out"
          enter-from-class="opacity-0 -translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition duration-200 ease-in"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-2"
        >
          <div 
            v-if="successMessage" 
            class="mb-6 p-3.5 rounded-xl bg-emerald-950/50 border border-emerald-500/40 text-emerald-200 text-xs flex items-start gap-2.5 backdrop-blur-md"
          >
            <CheckCircle2 class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5" />
            <div class="flex-1 font-mono">{{ successMessage }}</div>
          </div>
        </transition>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="space-y-5">
          
          <!-- Email Input Field -->
          <div>
            <label class="block text-xs font-mono font-medium text-slate-300 mb-1.5 flex items-center justify-between">
              <span class="flex items-center gap-1.5">
                <Mail class="w-3.5 h-3.5 text-gold-400" />
                Administrator Email
              </span>
              <span class="text-[10px] text-slate-500">256-Bit TLS</span>
            </label>
            <div class="relative">
              <input
                v-model.trim="email"
                type="email"
                required
                autocomplete="email"
                placeholder="admin@alhwaiti.dev"
                class="w-full bg-slate-950/80 border border-slate-700/80 focus:border-gold-400 focus:ring-2 focus:ring-gold-500/20 text-white placeholder-slate-600 rounded-xl px-4 py-3 text-sm font-sans transition-all duration-300 outline-none hover:border-slate-600 disabled:opacity-50"
                :disabled="isLoading"
              />
            </div>
          </div>

          <!-- Password Input Field -->
          <div>
            <label class="block text-xs font-mono font-medium text-slate-300 mb-1.5 flex items-center justify-between">
              <span class="flex items-center gap-1.5">
                <Lock class="w-3.5 h-3.5 text-gold-400" />
                Secure Passkey
              </span>
              <span class="text-[10px] text-slate-500">Min 6 chars</span>
            </label>
            <div class="relative">
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                required
                autocomplete="current-password"
                placeholder="••••••••••••"
                class="w-full bg-slate-950/80 border border-slate-700/80 focus:border-gold-400 focus:ring-2 focus:ring-gold-500/20 text-white placeholder-slate-600 rounded-xl px-4 py-3 pr-11 text-sm font-sans transition-all duration-300 outline-none hover:border-slate-600 disabled:opacity-50"
                :disabled="isLoading"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-gold-300 transition-colors p-1"
                aria-label="Toggle password visibility"
              >
                <EyeOff v-if="showPassword" class="w-4 h-4" />
                <Eye v-else class="w-4 h-4" />
              </button>
            </div>
          </div>

          <!-- Session Options -->
          <div class="flex items-center justify-between text-xs font-mono pt-1">
            <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-300 select-none">
              <input
                v-model="rememberMe"
                type="checkbox"
                class="rounded bg-slate-900 border-slate-700 text-gold-500 focus:ring-gold-500/20 h-4 w-4 accent-gold-500 cursor-pointer"
              />
              <span>Remember Token</span>
            </label>
            <button
              type="button"
              @click="autofillDemo"
              class="text-gold-400 hover:text-gold-300 hover:underline transition-colors cursor-pointer text-[11px]"
            >
              Fill Demo Auth
            </button>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="isLoading"
            class="relative w-full group overflow-hidden rounded-xl p-[1px] font-medium transition-all duration-300 cursor-pointer disabled:cursor-not-allowed disabled:opacity-60"
          >
            <!-- Glowing gradient background -->
            <span class="absolute inset-0 bg-gradient-to-r from-gold-400 via-amber-500 to-yellow-500 group-hover:opacity-100 transition-opacity"></span>
            <span class="relative flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-[11px] bg-slate-950 transition-colors group-hover:bg-slate-900/90 text-sm font-semibold tracking-wide text-white">
              
              <!-- Spinner when loading -->
              <svg 
                v-if="isLoading" 
                class="animate-spin -ml-1 mr-2 h-4 w-4 text-gold-400" 
                fill="none" 
                viewBox="0 0 24 24"
              >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
              </svg>
              
              <KeyRound v-else class="w-4 h-4 text-gold-400 group-hover:rotate-45 transition-transform duration-300" />
              
              <span>{{ isLoading ? 'Authenticating System...' : 'Authorize & Enter Console' }}</span>
              <ArrowRight v-if="!isLoading" class="w-4 h-4 text-gold-400 group-hover:translate-x-1 transition-transform duration-300" />
            </span>
          </button>

        </form>

        <!-- Footer Security Note -->
        <div class="mt-8 pt-6 border-t border-slate-800/80 text-center">
          <div class="flex items-center justify-center gap-2 text-[11px] font-mono text-slate-500">
            <span class="w-2 h-2 rounded-full bg-emerald-500/80 shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
            <span>Security Status: Protocol Active (AES-256)</span>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { 
  ShieldCheck, 
  Mail, 
  Lock, 
  Eye, 
  EyeOff, 
  ArrowRight, 
  ArrowLeft, 
  KeyRound, 
  AlertCircle, 
  CheckCircle2 
} from 'lucide-vue-next'
import { adminApi } from '@/services/adminApi'

const router = useRouter()
const route = useRoute()

const email = ref('')
const password = ref('')
const rememberMe = ref(true)
const showPassword = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')
const successMessage = ref('')

// Quick helper to fill demo credentials
const autofillDemo = () => {
  email.value = 'admin@alhwaiti.dev'
  password.value = 'CyberObsidian2026!'
  errorMessage.value = ''
}

// Full API Sanctum Login Handler
const handleLogin = async () => {
  errorMessage.value = ''
  successMessage.value = ''

  // Client-side validation
  if (!email.value || !email.value.includes('@')) {
    errorMessage.value = 'Please provide a valid administrative email address.'
    return
  }

  if (!password.value || password.value.length < 6) {
    errorMessage.value = 'Passkey must be at least 6 characters in length.'
    return
  }

  isLoading.value = true

  try {
    await adminApi.login({
      email: email.value,
      password: password.value
    })

    successMessage.value = 'Authentication successful! Initializing Dashboard...'

    // Short transition before redirecting to intended route or dashboard
    setTimeout(() => {
      const redirectTarget = (route.query && route.query.redirect) 
        ? route.query.redirect 
        : '/admin/overview'
      router.push(redirectTarget)
    }, 400)

  } catch (error) {
    errorMessage.value = error.message || 'Authentication handshake failed. Please verify credentials.'
  } finally {
    isLoading.value = false
  }
}
</script>

