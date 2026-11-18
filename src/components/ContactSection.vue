<template>
  <section id="contact" class="contact-section relative py-24 sm:py-32 overflow-hidden">
    
    <!-- Deep Purple / Cyan / Amber Ambient Backlighting -->
    <div class="pointer-events-none absolute inset-0 flex items-center justify-center z-0">
      <div class="w-[500px] h-[350px] rounded-full bg-[#7c3aed]/20 blur-[150px] -translate-x-28"></div>
      <div class="w-[480px] h-[350px] rounded-full bg-[#ff5500]/20 blur-[150px] translate-x-28"></div>
      <div class="w-[360px] h-[260px] rounded-full bg-[#00f2fe]/15 blur-[130px]"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
      
      <!-- Asymmetric Leaf-Curved Glass Banner (border-radius: 64px 20px 64px 20px) -->
      <div 
        class="contact-leaf-banner relative p-7 sm:p-11 lg:p-14 backdrop-blur-2xl bg-white/[0.03] border border-white/[0.1] shadow-[0_30px_80px_rgba(0,0,0,0.75),_inset_0_1px_2px_rgba(255,255,255,0.12)] overflow-hidden"
        style="border-radius: 64px 20px 64px 20px;"
      >
        <!-- Specular Corner Accents -->
        <div class="pointer-events-none absolute -top-24 -left-24 w-64 h-64 rounded-full bg-gradient-to-br from-[#ff5500]/25 to-transparent blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -right-24 w-64 h-64 rounded-full bg-gradient-to-tl from-[#00f2fe]/25 to-transparent blur-2xl"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
          
          <!-- ====================================================== -->
          <!-- LEFT COLUMN: Copy, 1-Click Email Capsule & Direct Chat -->
          <!-- ====================================================== -->
          <div class="lg:col-span-5 flex flex-col justify-between space-y-6">
            <div>
              <div class="section-badge inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#ff5500]/15 border border-[#ff5500]/30 text-amber-300 font-mono text-xs tracking-wider uppercase mb-4 shadow-[0_0_20px_rgba(255,85,0,0.2)]">
                <span class="w-2 h-2 rounded-full bg-[#ff5500] animate-pulse"></span>
                <span>// OMNI-CHANNEL TRANSMISSION HUB</span>
              </div>
              <h2 class="section-title text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight mb-3">
                Let's <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#ff5500] via-amber-400 to-[#00f2fe]">Connect</span>
              </h2>
              <p class="text-slate-300/90 text-sm sm:text-base leading-relaxed">
                Ready to elevate your digital presence or engineer an immersive 3D web application? Connect instantly via direct chat, social networks, or encrypted transmission.
              </p>
            </div>

            <!-- Interactive Direct Email Capsule (With 1-Click Copy + Mailto) -->
            <div class="p-4 rounded-2xl bg-[#070a12]/90 border border-white/[0.1] hover:border-amber-500/40 transition-all shadow-lg space-y-3">
              <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] font-mono uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
                  <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                  <span>DIRECT ELECTRONIC MAIL</span>
                </span>
                <span class="text-[10px] font-mono text-emerald-400">● 24/7 INBOX</span>
              </div>

              <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                <a 
                  :href="`mailto:${resolvedEmail}`" 
                  class="font-mono text-xs sm:text-sm font-bold text-white hover:text-amber-300 transition-colors truncate"
                >
                  {{ resolvedEmail }}
                </a>

                <div class="flex items-center gap-2 shrink-0">
                  <button
                    type="button"
                    @click="copyEmailToClipboard"
                    class="px-3 py-1.5 rounded-xl font-mono text-[11px] font-bold border transition-all cursor-pointer flex items-center gap-1.5"
                    :class="emailCopied 
                      ? 'bg-emerald-500/20 border-emerald-500/50 text-emerald-300 shadow-[0_0_15px_rgba(16,185,129,0.3)]' 
                      : 'bg-white/[0.05] hover:bg-amber-500/20 border-white/[0.1] hover:border-amber-500/40 text-slate-200 hover:text-amber-300'"
                  >
                    <span>{{ emailCopied ? 'COPIED ✓' : 'Copy Email' }}</span>
                  </button>
                  <a
                    :href="`mailto:${resolvedEmail}`"
                    class="px-2.5 py-1.5 rounded-xl bg-[#ff5500]/20 hover:bg-[#ff5500] border border-[#ff5500]/40 text-amber-300 hover:text-white font-mono text-[11px] font-bold transition-all"
                    title="Send Direct Email"
                  >
                    ✉
                  </a>
                </div>
              </div>
            </div>

            <!-- Instant Direct Chat Priority Row (WhatsApp, Telegram, Discord) -->
            <div class="space-y-2.5">
              <div class="text-[10px] font-mono uppercase tracking-wider text-slate-400 flex items-center justify-between">
                <span>// INSTANT DIRECT MESSAGING</span>
                <span class="text-emerald-400">⚡ AVG REPLY &lt; 1 HR</span>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                <a
                  v-for="chat in directChatChannels"
                  :key="chat.id"
                  :href="chat.url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="group/chat relative p-3 rounded-2xl bg-[#070a12]/85 border border-white/[0.08] transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between overflow-hidden"
                  :style="{ '--hover-color': chat.color }"
                >
                  <div class="flex items-center justify-between mb-2">
                    <span 
                      class="w-8 h-8 rounded-xl flex items-center justify-center border border-white/10 transition-transform duration-300 group-hover/chat:scale-110"
                      :style="{ backgroundColor: `${chat.color}18`, color: chat.color }"
                      v-html="chat.svg"
                    ></span>
                    <span class="text-xs font-mono text-slate-500 group-hover/chat:text-white group-hover/chat:translate-x-0.5 group-hover/chat:-translate-y-0.5 transition-all">
                      ↗
                    </span>
                  </div>
                  <div>
                    <div class="text-xs font-bold text-white group-hover/chat:text-amber-300 transition-colors">
                      {{ chat.name }}
                    </div>
                    <div class="text-[10px] font-mono text-slate-400 truncate">
                      {{ chat.handle }}
                    </div>
                  </div>
                </a>
              </div>
            </div>

            <!-- Location & SLA Telemetry Row -->
            <div class="grid grid-cols-2 gap-3 pt-2 font-mono text-xs">
              <div class="p-3 rounded-2xl bg-white/[0.02] border border-white/[0.07] flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-[#00f2fe]/15 border border-[#00f2fe]/35 flex items-center justify-center text-[#00f2fe] shrink-0">
                  📍
                </span>
                <div class="min-w-0">
                  <span class="text-[9px] text-slate-500 block uppercase">BASE OF OPS</span>
                  <span class="text-white font-bold text-[11px] truncate block">
                    {{ currentProfile.location || 'Saudi Arabia / Remote' }}
                  </span>
                </div>
              </div>

              <div class="p-3 rounded-2xl bg-white/[0.02] border border-white/[0.07] flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-emerald-500/15 border border-emerald-500/35 flex items-center justify-center text-emerald-400 shrink-0">
                  ⚡
                </span>
                <div class="min-w-0">
                  <span class="text-[9px] text-slate-500 block uppercase">SLA TELEMETRY</span>
                  <span class="text-emerald-400 font-bold text-[11px] truncate block">Within 24 Hours</span>
                </div>
              </div>
            </div>

          </div>

          <!-- ====================================================== -->
          <!-- RIGHT COLUMN: Glassmorphic Contact Form (100% Intact)  -->
          <!-- ====================================================== -->
          <div class="lg:col-span-7">
            <form @submit.prevent="handleSubmit" class="space-y-5">
              
              <!-- Two columns: Name & Email -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="relative group">
                  <input
                    id="contact-name"
                    v-model="form.name"
                    type="text"
                    required
                    placeholder=" "
                    class="peer w-full px-4 pt-6 pb-2.5 rounded-2xl bg-[#05060b]/85 border border-white/[0.1] focus:border-[#ff5500] focus:ring-2 focus:ring-[#ff5500]/30 focus:shadow-[0_0_25px_rgba(255,85,0,0.25)] text-white text-sm transition-all outline-none"
                  />
                  <label 
                    for="contact-name"
                    class="absolute left-4 top-2 text-[10px] font-mono uppercase tracking-wider text-amber-400 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-xs peer-placeholder-shown:text-slate-400 peer-focus:top-2 peer-focus:text-[10px] peer-focus:text-[#ff5500] pointer-events-none"
                  >
                    {{ t.nameLabel }}
                  </label>
                </div>

                <div class="relative group">
                  <input
                    id="contact-email"
                    v-model="form.email"
                    type="email"
                    required
                    placeholder=" "
                    class="peer w-full px-4 pt-6 pb-2.5 rounded-2xl bg-[#05060b]/85 border border-white/[0.1] focus:border-[#00f2fe] focus:ring-2 focus:ring-[#00f2fe]/30 focus:shadow-[0_0_25px_rgba(0,242,254,0.25)] text-white text-sm transition-all outline-none"
                  />
                  <label 
                    for="contact-email"
                    class="absolute left-4 top-2 text-[10px] font-mono uppercase tracking-wider text-[#00f2fe] transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-xs peer-placeholder-shown:text-slate-400 peer-focus:top-2 peer-focus:text-[10px] peer-focus:text-[#00f2fe] pointer-events-none"
                  >
                    {{ t.emailLabel }}
                  </label>
                </div>
              </div>

              <!-- Subject Field -->
              <div class="relative group">
                <input
                  id="contact-subject"
                  v-model="form.subject"
                  type="text"
                  required
                  placeholder=" "
                  class="peer w-full px-4 pt-6 pb-2.5 rounded-2xl bg-[#05060b]/85 border border-white/[0.1] focus:border-[#ff5500] focus:ring-2 focus:ring-[#ff5500]/30 focus:shadow-[0_0_25px_rgba(255,85,0,0.25)] text-white text-sm transition-all outline-none"
                />
                <label 
                  for="contact-subject"
                  class="absolute left-4 top-2 text-[10px] font-mono uppercase tracking-wider text-amber-400 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-xs peer-placeholder-shown:text-slate-400 peer-focus:top-2 peer-focus:text-[10px] peer-focus:text-[#ff5500] pointer-events-none"
                >
                  {{ t.subjectLabel }}
                </label>
              </div>

              <!-- Message Field -->
              <div class="relative group">
                <textarea
                  id="contact-message"
                  v-model="form.message"
                  required
                  rows="4"
                  placeholder=" "
                  class="peer w-full px-4 pt-6 pb-3 rounded-2xl bg-[#05060b]/85 border border-white/[0.1] focus:border-[#ff5500] focus:ring-2 focus:ring-[#ff5500]/30 focus:shadow-[0_0_25px_rgba(255,85,0,0.25)] text-white text-sm transition-all outline-none resize-none"
                ></textarea>
                <label 
                  for="contact-message"
                  class="absolute left-4 top-2 text-[10px] font-mono uppercase tracking-wider text-amber-400 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-xs peer-placeholder-shown:text-slate-400 peer-focus:top-2 peer-focus:text-[10px] peer-focus:text-[#ff5500] pointer-events-none"
                >
                  {{ t.messageLabel }}
                </label>
              </div>

              <!-- Error alert if submission failed -->
              <div v-if="submitError" class="p-3.5 rounded-2xl bg-red-950/70 border border-red-500/40 text-red-200 text-xs font-mono">
                {{ submitError }}
              </div>

              <!-- Submit Action Row with Magnetic Button -->
              <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <span v-if="submitSuccess" class="text-xs font-mono text-emerald-400 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                  <span>{{ t.messageSent }}</span>
                </span>
                <span v-else class="text-[11px] font-mono text-slate-400 flex items-center gap-2">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                  <span>256-bit TLS Encrypted Endpoint</span>
                </span>

                <button
                  ref="submitBtnRef"
                  type="submit"
                  :disabled="isSubmitting"
                  @mousemove="handleSubmitMagnetic"
                  @mouseleave="resetSubmitMagnetic"
                  :style="submitMagneticStyle"
                  class="px-8 py-4 rounded-2xl font-bold text-xs tracking-wider uppercase text-white bg-gradient-to-r from-[#ff5500] via-amber-500 to-[#f59e0b] border border-amber-300/40 shadow-[0_0_30px_rgba(255,85,0,0.45)] hover:shadow-[0_0_45px_rgba(255,85,0,0.8)] flex items-center justify-center gap-2.5 cursor-pointer disabled:opacity-60 transition-shadow"
                >
                  <span v-if="isSubmitting" class="w-4 h-4 rounded-full border-2 border-white border-t-transparent animate-spin"></span>
                  <span v-if="isSubmitting">{{ t.sendingMessage }}</span>
                  <span v-else-if="submitSuccess">Transmission Sent ✓</span>
                  <span v-else>{{ t.sendMessage }}</span>
                  <svg v-if="!isSubmitting && !submitSuccess" class="w-4 h-4 transform -rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                  </svg>
                </button>
              </div>

            </form>
          </div>

        </div>

        <!-- ======================================================== -->
        <!-- BOTTOM DOCK: OMNI-CHANNEL SOCIAL & DEVELOPER NETWORKS    -->
        <!-- ======================================================== -->
        <div class="relative z-10 mt-10 pt-8 border-t border-white/[0.08]">
          <div class="flex flex-wrap items-center justify-between gap-2 mb-5">
            <div class="flex items-center gap-2 font-mono text-xs text-slate-300">
              <span class="w-2 h-2 rounded-full bg-[#00f2fe] animate-pulse"></span>
              <span class="font-bold tracking-wider uppercase">// GLOBAL DEVELOPER, DESIGN &amp; SOCIAL NETWORKS</span>
            </div>
            <span class="text-[11px] font-mono text-slate-400">
              @mohammed-alhwaiti • Verified Profiles
            </span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a
              v-for="social in socialNetworkCards"
              :key="social.id"
              :href="social.url"
              target="_blank"
              rel="noopener noreferrer"
              class="social-3d-card group/soc relative p-3.5 rounded-2xl bg-[#070a12]/90 border border-white/[0.08] hover:border-amber-500/45 transition-all duration-300 hover:-translate-y-1.5 flex items-center justify-between gap-2.5 overflow-hidden"
            >
              <!-- Subtle Hover Radial Glow -->
              <div 
                class="pointer-events-none absolute -right-4 -top-4 w-16 h-16 rounded-full blur-xl opacity-0 group-hover/soc:opacity-40 transition-opacity duration-300"
                :style="{ backgroundColor: social.color }"
              ></div>

              <div class="flex items-center gap-2.5 min-w-0 relative z-10">
                <span 
                  class="w-9 h-9 rounded-xl shrink-0 flex items-center justify-center border border-white/[0.08] transition-transform duration-300 group-hover/soc:scale-110"
                  :style="{ backgroundColor: `${social.color}15`, color: social.color }"
                  v-html="social.svg"
                ></span>
                <div class="min-w-0">
                  <div class="text-xs font-bold text-white group-hover/soc:text-amber-300 transition-colors truncate">
                    {{ social.name }}
                  </div>
                  <div class="text-[10px] font-mono text-slate-400 truncate">
                    {{ social.handle }}
                  </div>
                </div>
              </div>

              <span 
                class="relative z-10 text-xs font-mono text-slate-500 group-hover/soc:text-white transform group-hover/soc:translate-x-0.5 group-hover/soc:-translate-y-0.5 transition-all shrink-0"
              >
                ↗
              </span>
            </a>
          </div>
        </div>

      </div>

    </div>
  </section>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import confetti from 'canvas-confetti'
import { portfolioService } from '@/services/portfolioService'
import { personalInfo } from '@/data/portfolioData'
import { lang, isRtl, t } from '@/services/siteLanguage'

const props = defineProps({
  profile: {
    type: Object,
    default: () => ({})
  },
  socialLinks: {
    type: Array,
    default: () => []
  }
})

const currentProfile = computed(() => {
  return Object.keys(props.profile || {}).length ? { ...personalInfo, ...props.profile } : personalInfo
})

const resolvedEmail = computed(() => {
  return currentProfile.value.email || 'contact@alhwaiti.dev'
})

// 1-Click Email Copy State
const emailCopied = ref(false)
const copyEmailToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(resolvedEmail.value)
    emailCopied.value = true
    setTimeout(() => {
      emailCopied.value = false
    }, 2000)
  } catch {
    // Fallback copy method
    const textarea = document.createElement('textarea')
    textarea.value = resolvedEmail.value
    document.body.appendChild(textarea)
    textarea.select()
    document.execCommand('copy')
    document.body.removeChild(textarea)
    emailCopied.value = true
    setTimeout(() => {
      emailCopied.value = false
    }, 2000)
  }
}

// Helper to lookup dynamic item from backend socialLinks array or profile object
const findDynamicChannel = (keywords) => {
  if (Array.isArray(props.socialLinks) && props.socialLinks.length > 0) {
    return props.socialLinks.find((item) => {
      const p = (item.platform || item.name || item.title || '').toLowerCase()
      return keywords.some((k) => p.includes(k))
    })
  }
  return null
}

const findDynamicUrl = (keywords, fallbackUrl) => {
  const match = findDynamicChannel(keywords)
  if (match && (match.url || match.link || match.href)) {
    return match.url || match.link || match.href
  }
  for (const k of keywords) {
    if (currentProfile.value[k] && typeof currentProfile.value[k] === 'string') {
      return currentProfile.value[k]
    }
  }
  return fallbackUrl
}

const DEFAULT_SVG_MAP = {
  whatsapp: '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824z"/></svg>',
  telegram: '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>',
  discord: '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>',
  github: '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>',
  linkedin: '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>',
  behance: '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.372 0 0 5.373 0 12s5.372 12 12 12 12-5.373 12-12S18.628 0 12 0zm9.885 11.441c-2.575-.422-4.943-.445-7.103-.073-.244-.563-.497-1.125-.767-1.68 2.31-1 4.165-2.358 5.548-4.082 1.35 1.594 2.197 3.619 2.322 5.835zm-3.842-7.282c-1.205 1.554-2.868 2.783-4.986 3.68-1.016-1.861-2.178-3.676-3.488-5.438.779-.197 1.591-.304 2.431-.304 2.275 0 4.368.779 6.043 2.062zm-10.516-.993c1.331 1.742 2.511 3.538 3.537 5.381-2.43.715-5.331 1.082-8.684 1.105.692-2.835 2.601-5.193 5.147-6.486zm-5.44 8.834l.013-.256c3.849-.005 7.169-.448 9.95-1.322.233.475.456.952.67 1.432-3.38 1.057-6.165 3.222-8.337 6.48-1.432-1.719-2.296-3.927-2.296-6.334zm3.829 7.81c1.969-3.088 4.482-5.098 7.598-6.027.928 2.42 1.609 4.91 2.043 7.46-3.349 1.291-6.953.666-9.641-1.433zm11.686.43c-.438-2.353-1.08-4.653-1.92-6.897 1.876-.265 3.94-.196 6.199.196-.437 2.786-2.028 5.192-4.279 6.701z"/></svg>',
  x: '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
  instagram: '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
  facebook: '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
  generic: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>'
}

const resolveSvgForPlatform = (platformKey) => {
  const key = (platformKey || '').toLowerCase()
  for (const [k, svg] of Object.entries(DEFAULT_SVG_MAP)) {
    if (key.includes(k)) return svg
  }
  if (key.includes('twitter')) return DEFAULT_SVG_MAP.x
  if (key.includes('dribbble')) return DEFAULT_SVG_MAP.behance
  return DEFAULT_SVG_MAP.generic
}

// Priority Direct Chat Channels (WhatsApp, Telegram, Discord) synced with Admin Social Hub
const directChatChannels = computed(() => {
  const defaults = [
    {
      id: 'whatsapp',
      keywords: ['whatsapp', 'wa'],
      name: 'WhatsApp Direct',
      handle: 'Instant Chat',
      color: '#10b981',
      sort_order: 1,
      url: findDynamicUrl(['whatsapp', 'wa'], currentProfile.value.whatsapp || 'https://wa.me/970599000000'),
      svg: DEFAULT_SVG_MAP.whatsapp
    },
    {
      id: 'telegram',
      keywords: ['telegram', 'tg'],
      name: 'Telegram',
      handle: '@mohammed_alhwaiti',
      color: '#00f2fe',
      sort_order: 2,
      url: findDynamicUrl(['telegram', 'tg'], 'https://t.me/mohammed_alhwaiti'),
      svg: DEFAULT_SVG_MAP.telegram
    },
    {
      id: 'discord',
      keywords: ['discord'],
      name: 'Discord',
      handle: 'alhwaiti.dev',
      color: '#818cf8',
      sort_order: 3,
      url: findDynamicUrl(['discord'], 'https://discord.com'),
      svg: DEFAULT_SVG_MAP.discord
    }
  ]

  return defaults
    .map((def) => {
      const dyn = findDynamicChannel(def.keywords)
      if (!dyn) return def
      return {
        ...def,
        name: dyn.name || def.name,
        handle: dyn.handle || def.handle,
        color: dyn.color || def.color,
        url: dyn.url || def.url,
        is_active: dyn.is_active !== false,
        sort_order: dyn.sort_order ?? def.sort_order
      }
    })
    .filter((ch) => ch.is_active !== false)
    .sort((a, b) => (a.sort_order || 99) - (b.sort_order || 99))
})

// Complete Social & Developer Network Matrix (6 Core Platforms + Custom Admin Additions)
const socialNetworkCards = computed(() => {
  const defaults = [
    {
      id: 'github',
      keywords: ['github', 'git'],
      name: 'GitHub',
      handle: '@mohammed-alhwaiti',
      color: '#f8fafc',
      sort_order: 4,
      url: findDynamicUrl(['github', 'git'], currentProfile.value.github || 'https://github.com'),
      svg: DEFAULT_SVG_MAP.github
    },
    {
      id: 'linkedin',
      keywords: ['linkedin'],
      name: 'LinkedIn',
      handle: 'in/mohammed-alhwaiti',
      color: '#38bdf8',
      sort_order: 5,
      url: findDynamicUrl(['linkedin'], currentProfile.value.linkedin || 'https://linkedin.com'),
      svg: DEFAULT_SVG_MAP.linkedin
    },
    {
      id: 'behance',
      keywords: ['behance', 'dribbble'],
      name: 'Behance / UI',
      handle: '@mohammed-alhwaiti',
      color: '#ec4899',
      sort_order: 6,
      url: findDynamicUrl(['behance', 'dribbble'], currentProfile.value.dribbble || 'https://dribbble.com'),
      svg: DEFAULT_SVG_MAP.behance
    },
    {
      id: 'x',
      keywords: ['x', 'twitter'],
      name: 'X (Twitter)',
      handle: '@alhwaiti_dev',
      color: '#f59e0b',
      sort_order: 7,
      url: findDynamicUrl(['x', 'twitter'], currentProfile.value.x || 'https://x.com'),
      svg: DEFAULT_SVG_MAP.x
    },
    {
      id: 'instagram',
      keywords: ['instagram', 'ig'],
      name: 'Instagram',
      handle: '@mohammed.alhwaiti',
      color: '#ff5500',
      sort_order: 8,
      url: findDynamicUrl(['instagram', 'ig'], currentProfile.value.instagram || 'https://instagram.com'),
      svg: DEFAULT_SVG_MAP.instagram
    },
    {
      id: 'facebook',
      keywords: ['facebook', 'fb'],
      name: 'Facebook',
      handle: 'mohammed.alhwaiti',
      color: '#60a5fa',
      sort_order: 9,
      url: findDynamicUrl(['facebook', 'fb'], currentProfile.value.facebook || 'https://facebook.com'),
      svg: DEFAULT_SVG_MAP.facebook
    }
  ]

  const merged = defaults.map((def) => {
    const dyn = findDynamicChannel(def.keywords)
    if (!dyn) return def
    return {
      ...def,
      name: dyn.name || def.name,
      handle: dyn.handle || def.handle,
      color: dyn.color || def.color,
      url: dyn.url || def.url,
      is_active: dyn.is_active !== false,
      sort_order: dyn.sort_order ?? def.sort_order
    }
  })

  // Append any extra custom social channels added in Admin that aren't in the 9 built-in presets
  if (Array.isArray(props.socialLinks)) {
    const builtInKeywords = ['whatsapp', 'wa', 'telegram', 'tg', 'discord', 'github', 'git', 'linkedin', 'behance', 'dribbble', 'x', 'twitter', 'instagram', 'ig', 'facebook', 'fb']
    props.socialLinks.forEach((item) => {
      const p = (item.platform || item.name || '').toLowerCase()
      const isBuiltIn = builtInKeywords.some((k) => p.includes(k))
      if (!isBuiltIn && item.is_active !== false && item.url) {
        merged.push({
          id: item.id || p,
          name: item.name || item.platform,
          handle: item.handle || '@mohammed_alhwaiti',
          color: item.color || '#00f2fe',
          sort_order: item.sort_order || 99,
          url: item.url,
          is_active: true,
          svg: resolveSvgForPlatform(p)
        })
      }
    })
  }

  return merged
    .filter((ch) => ch.is_active !== false)
    .sort((a, b) => (a.sort_order || 99) - (b.sort_order || 99))
})

// Existing Contact Form State & Submission Logic (100% Preserved)
const form = reactive({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const isSubmitting = ref(false)
const submitSuccess = ref(false)
const submitError = ref('')

// Magnetic Submit Button Physics
const submitBtnRef = ref(null)
const submitMagneticStyle = ref({ transform: 'translate3d(0px, 0px, 0px)', transition: 'transform 200ms ease-out' })

const handleSubmitMagnetic = (e) => {
  if (!submitBtnRef.value || isSubmitting.value) return
  const rect = submitBtnRef.value.getBoundingClientRect()
  const x = (e.clientX - rect.left - rect.width / 2) * 0.25
  const y = (e.clientY - rect.top - rect.height / 2) * 0.25
  submitMagneticStyle.value = {
    transform: `translate3d(${x.toFixed(1)}px, ${y.toFixed(1)}px, 0px)`,
    transition: 'transform 60ms linear'
  }
}

const resetSubmitMagnetic = () => {
  submitMagneticStyle.value = {
    transform: 'translate3d(0px, 0px, 0px)',
    transition: 'transform 400ms cubic-bezier(0.16, 1, 0.3, 1)'
  }
}

const triggerConfetti = () => {
  confetti({
    particleCount: 80,
    spread: 70,
    origin: { y: 0.6 },
    colors: ['#ff5500', '#f59e0b', '#00f2fe', '#ffffff']
  })
}

const handleSubmit = async () => {
  isSubmitting.value = true
  submitError.value = ''

  try {
    await portfolioService.submitContactForm({
      name: form.name,
      email: form.email,
      subject: form.subject,
      message: form.message
    })

    submitSuccess.value = true
    triggerConfetti()

    setTimeout(() => {
      form.name = ''
      form.email = ''
      form.subject = ''
      form.message = ''
      submitSuccess.value = false
    }, 4500)
  } catch (err) {
    const validationErrors = err?.response?.data?.errors
    if (validationErrors && typeof validationErrors === 'object') {
      submitError.value = Object.values(validationErrors).flat().join(' • ')
    } else {
      submitError.value = err?.response?.data?.message || err.message || 'Failed to transmit message. Please try again or reach out directly.'
    }
  } finally {
    isSubmitting.value = false
  }
}
</script>
