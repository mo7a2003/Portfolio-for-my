<template>
  <div class="space-y-6">
    
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 border border-gold-500/30 text-xs font-mono text-gold-400 mb-2">
          <Share2 class="w-3.5 h-3.5 text-gold-400" />
          <span>Admin\SocialLinkController • Omni-Channel Hub</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
          Social &amp; Contact Hub Manager
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 font-mono mt-1">
          Configure Direct Email, WhatsApp/Phone, and all Omni-Channel Social Networks synced to the public portfolio
        </p>
      </div>

      <!-- Add Social Link Button -->
      <button
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs transition-all shadow-[0_0_22px_rgba(212,175,55,0.4)] cursor-pointer self-start sm:self-auto"
      >
        <Plus class="w-4 h-4 text-slate-950 stroke-[3]" />
        <span>Add Social Channel</span>
      </button>
    </div>

    <!-- Feedback Toast -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div 
        v-if="toastMessage" 
        class="p-4 rounded-xl border text-xs font-mono flex items-center justify-between"
        :class="isErrorToast 
          ? 'bg-red-950/70 border-red-500/40 text-red-200 shadow-[0_0_20px_rgba(239,68,68,0.25)]' 
          : 'bg-emerald-950/70 border-emerald-500/40 text-emerald-200 shadow-[0_0_20px_rgba(16,185,129,0.2)]'"
      >
        <div class="flex items-center gap-2">
          <AlertCircle v-if="isErrorToast" class="w-4 h-4 text-red-400 shrink-0" />
          <CheckCircle2 v-else class="w-4 h-4 text-emerald-400 shrink-0" />
          <span>{{ toastMessage }}</span>
        </div>
        <button @click="toastMessage = ''" class="hover:text-white cursor-pointer" :class="isErrorToast ? 'text-red-400' : 'text-emerald-400'">
          <X class="w-3.5 h-3.5" />
        </button>
      </div>
    </transition>

    <!-- ======================================================== -->
    <!-- 1. QUICK SETTINGS BAR: DIRECT EMAIL & WHATSAPP / PHONE   -->
    <!-- ======================================================== -->
    <div class="backdrop-blur-2xl bg-slate-950/70 border border-gold-500/30 rounded-2xl p-5 sm:p-6 shadow-xl">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-amber-400 font-bold">
          <span>⚡ DIRECT CONTACT CAPSULE SETTINGS (1-CLICK COPY &amp; INSTANT WHATSAPP)</span>
        </div>
        <span class="text-[10px] font-mono text-emerald-400">● Live Sync Enabled</span>
      </div>

      <form @submit.prevent="saveDirectContact" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
        <div class="md:col-span-4">
          <label class="block text-[11px] font-mono text-slate-400 mb-1.5">Direct Electronic Mail (1-Click Copy)</label>
          <input
            v-model="directContact.email"
            type="email"
            required
            placeholder="contact@alhwaiti.dev"
            class="w-full bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
          />
        </div>

        <div class="md:col-span-4">
          <label class="block text-[11px] font-mono text-slate-400 mb-1.5">Direct WhatsApp / Phone Link</label>
          <input
            v-model="directContact.whatsapp"
            type="text"
            placeholder="https://wa.me/970599000000"
            class="w-full bg-slate-900 border border-slate-800 focus:border-emerald-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
          />
        </div>

        <div class="md:col-span-2">
          <label class="block text-[11px] font-mono text-slate-400 mb-1.5">Base Location</label>
          <input
            v-model="directContact.location"
            type="text"
            placeholder="Saudi Arabia / Remote"
            class="w-full bg-slate-900 border border-slate-800 focus:border-cyan-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
          />
        </div>

        <div class="md:col-span-2">
          <button
            type="submit"
            class="w-full py-2.5 px-4 rounded-xl bg-amber-500/20 hover:bg-amber-500 border border-amber-500/50 text-amber-300 hover:text-slate-950 font-mono font-bold text-xs transition-all cursor-pointer"
          >
            Save Direct Info
          </button>
        </div>
      </form>
    </div>

    <!-- ======================================================== -->
    <!-- 2. INTERACTIVE SOCIAL & MESSAGING CHANNELS TABLE         -->
    <!-- ======================================================== -->
    <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-2xl p-6 shadow-xl">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs font-sans">
          <thead>
            <tr class="border-b border-slate-800 text-[11px] font-mono uppercase text-slate-400">
              <th class="pb-3 pl-2">Order</th>
              <th class="pb-3">Channel &amp; Category</th>
              <th class="pb-3">Display Handle</th>
              <th class="pb-3">Direct URL</th>
              <th class="pb-3 text-center">Active Toggle</th>
              <th class="pb-3 text-right pr-2">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60">
            <tr 
              v-for="link in sortedSocialLinks" 
              :key="link.id"
              class="hover:bg-slate-900/40 transition-colors group"
            >
              <!-- Sort Order Input -->
              <td class="py-4 pl-2 w-20">
                <input
                  type="number"
                  min="1"
                  max="99"
                  :value="link.sort_order || link.order || 1"
                  @change="updateSortOrder(link, $event.target.value)"
                  class="w-14 bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-lg px-2 py-1 text-center font-mono text-xs text-amber-300 outline-none"
                />
              </td>

              <!-- Channel Name & Category Badge -->
              <td class="py-4">
                <div class="flex items-center gap-3">
                  <div 
                    class="w-9 h-9 rounded-xl border flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform font-mono font-bold text-xs"
                    :style="{
                      backgroundColor: `${link.color || '#f59e0b'}18`,
                      borderColor: `${link.color || '#f59e0b'}45`,
                      color: link.color || '#f59e0b'
                    }"
                  >
                    {{ (link.platform || 'SO').slice(0, 2).toUpperCase() }}
                  </div>
                  <div>
                    <div class="font-bold text-white text-sm flex items-center gap-2">
                      <span>{{ link.platform }}</span>
                      <span 
                        class="px-2 py-0.5 rounded text-[9px] font-mono uppercase"
                        :class="link.category === 'direct_chat' 
                          ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' 
                          : 'bg-[#00f2fe]/10 text-[#00f2fe] border border-[#00f2fe]/30'"
                      >
                        {{ link.category === 'direct_chat' ? 'Direct Chat' : 'Social Dock' }}
                      </span>
                    </div>
                  </div>
                </div>
              </td>

              <!-- Display Handle -->
              <td class="py-4 text-slate-300 font-mono text-xs">
                {{ link.handle || '@mohammed-alhwaiti' }}
              </td>

              <!-- Direct URL -->
              <td class="py-4">
                <a
                  :href="link.url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="text-gold-400 hover:text-gold-300 underline font-mono text-xs flex items-center gap-1.5 max-w-[260px] truncate"
                >
                  <span class="truncate">{{ link.url }}</span>
                  <ExternalLink class="w-3 h-3 shrink-0" />
                </a>
              </td>

              <!-- Active Toggle -->
              <td class="py-4 text-center">
                <button
                  type="button"
                  @click="toggleActive(link)"
                  class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono border transition-all cursor-pointer"
                  :class="link.is_active !== false 
                    ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-300' 
                    : 'bg-slate-900 text-slate-500 border-slate-800'"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="link.is_active !== false ? 'bg-emerald-400' : 'bg-slate-500'"></span>
                  <span>{{ link.is_active !== false ? 'Visible' : 'Hidden' }}</span>
                </button>
              </td>

              <!-- Actions -->
              <td class="py-4 text-right pr-2">
                <div class="inline-flex items-center gap-2">
                  <button
                    type="button"
                    @click="openEditModal(link)"
                    class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-gold-300 border border-slate-800 hover:border-gold-500/30 transition-colors cursor-pointer"
                    title="Edit Channel"
                  >
                    <Edit3 class="w-3.5 h-3.5" />
                  </button>
                  <button
                    type="button"
                    @click="confirmDelete(link)"
                    class="p-2 rounded-xl bg-red-950/30 hover:bg-red-950/60 text-red-400 hover:text-red-200 border border-red-500/20 transition-colors cursor-pointer"
                    title="Delete Channel"
                  >
                    <Trash2 class="w-3.5 h-3.5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ======================================================== -->
    <!-- Add / Edit Social Link Modal (With Platform Presets)     -->
    <!-- ======================================================== -->
    <div 
      v-if="isModalOpen" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
    >
      <div class="relative w-full max-w-xl bg-[#030712] border border-gold-500/40 rounded-3xl p-6 sm:p-8 shadow-[0_0_60px_rgba(212,175,55,0.25)] max-h-[90vh] overflow-y-auto custom-scrollbar">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-5">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400">
              <Share2 class="w-5 h-5 text-gold-400" />
            </div>
            <div>
              <h2 class="text-lg font-bold text-white">
                {{ editingLink ? `Edit Channel: ${form.platform}` : 'Add Omni-Channel Link' }}
              </h2>
              <p class="text-xs text-slate-400 font-mono">
                Select a platform preset to auto-populate category &amp; neon color
              </p>
            </div>
          </div>
          <button @click="closeModal" class="text-slate-400 hover:text-white p-1 rounded-lg cursor-pointer">
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Platform Preset Selector Pills -->
        <div class="mb-5">
          <label class="block text-[11px] font-mono uppercase text-amber-400 mb-2">
            Platform Preset Selector (1-Click Auto-Fill)
          </label>
          <div class="flex flex-wrap gap-1.5">
            <button
              v-for="preset in platformPresets"
              :key="preset.platform"
              type="button"
              @click="applyPlatformPreset(preset)"
              class="px-2.5 py-1.5 rounded-xl border text-[11px] font-mono flex items-center gap-1.5 transition-all cursor-pointer"
              :class="form.platform === preset.platform 
                ? 'bg-amber-500/20 border-amber-400 text-white font-bold' 
                : 'bg-slate-900 border-slate-800 text-slate-400 hover:text-white'"
            >
              <span class="w-2 h-2 rounded-full" :style="{ backgroundColor: preset.color }"></span>
              <span>{{ preset.platform }}</span>
            </button>
          </div>
        </div>

        <form @submit.prevent="saveLink" class="space-y-4">
          
          <!-- Inline Validation Error Banner (err.response.data.errors) -->
          <div 
            v-if="modalError" 
            class="p-3.5 rounded-xl bg-red-950/70 border border-red-500/40 text-red-200 text-xs font-mono flex items-center justify-between"
          >
            <div class="flex items-center gap-2">
              <AlertCircle class="w-4 h-4 text-red-400 shrink-0" />
              <span>{{ modalError }}</span>
            </div>
            <button type="button" @click="modalError = ''" class="text-red-400 hover:text-white">
              <X class="w-4 h-4" />
            </button>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Platform Name *</label>
              <input
                v-model="form.platform"
                type="text"
                required
                placeholder="e.g. GitHub / WhatsApp Direct"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Display Handle</label>
              <input
                v-model="form.handle"
                type="text"
                placeholder="e.g. @mohammed-alhwaiti"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Placement Category</label>
              <select
                v-model="form.category"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3 py-2.5 text-xs text-white font-mono outline-none"
              >
                <option value="direct_chat">Direct Chat (Left Hub)</option>
                <option value="social_network">Social Dock (Bottom Matrix)</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Neon Accent Color</label>
              <input
                v-model="form.color"
                type="text"
                placeholder="#00f2fe"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Sort Order</label>
              <input
                v-model.number="form.sort_order"
                type="number"
                min="1"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Direct URL Target *</label>
            <input
              v-model="form.url"
              type="url"
              required
              placeholder="https://github.com/..."
              class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
            />
          </div>

          <div class="flex items-center gap-3 pt-2">
            <label class="flex items-center gap-2 cursor-pointer select-none text-xs font-mono text-slate-300">
              <input
                v-model="form.is_active"
                type="checkbox"
                class="rounded bg-slate-900 border-slate-700 text-gold-500 h-4 w-4 accent-amber-500 cursor-pointer"
              />
              <span>Active &amp; visible on Public Portfolio Contact Hub</span>
            </label>
          </div>

          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
            <button
              type="button"
              @click="closeModal"
              :disabled="isSubmitting"
              class="px-4 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white text-xs font-mono cursor-pointer disabled:opacity-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(212,175,55,0.35)] cursor-pointer disabled:opacity-50"
            >
              <span v-if="isSubmitting" class="w-3.5 h-3.5 rounded-full border-2 border-slate-950 border-t-transparent animate-spin"></span>
              <span>{{ isSubmitting ? 'Saving...' : (editingLink ? 'Save Channel' : 'Add Channel') }}</span>
            </button>
          </div>

        </form>

      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div 
      v-if="linkToDelete" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
    >
      <div class="relative w-full max-w-md bg-[#030712] border border-red-500/40 rounded-2xl p-6 shadow-[0_0_40px_rgba(239,68,68,0.25)]">
        <h3 class="text-base font-bold text-white mb-2 flex items-center gap-2">
          <AlertCircle class="w-5 h-5 text-red-400" />
          <span>Confirm Channel Deletion</span>
        </h3>
        <p class="text-xs text-slate-300 mb-6 font-sans">
          Are you sure you want to remove <strong class="text-white">{{ linkToDelete.platform }}</strong>?
        </p>
        <div class="flex items-center justify-end gap-3 font-mono text-xs">
          <button @click="linkToDelete = null" class="px-4 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white cursor-pointer">
            Cancel
          </button>
          <button @click="executeDelete" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold cursor-pointer">
            Yes, Delete Channel
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Share2, Plus, ExternalLink, Edit3, Trash2, X, CheckCircle2, AlertCircle } from 'lucide-vue-next'
import { adminApi } from '../../services/adminApi'
import { personalInfo } from '../../data/portfolioData'

const emit = defineEmits(['stats-updated'])

const socialLinks = ref([])
const toastMessage = ref('')
const isErrorToast = ref(false)
const modalError = ref('')
const isSubmitting = ref(false)
const isModalOpen = ref(false)
const editingLink = ref(null)
const linkToDelete = ref(null)

const directContact = ref({
  email: personalInfo.email || 'contact@alhwaiti.dev',
  whatsapp: personalInfo.whatsapp || 'https://wa.me/970599000000',
  location: personalInfo.location || 'Saudi Arabia / Remote'
})

const platformPresets = [
  { platform: 'WhatsApp Direct', category: 'direct_chat', handle: 'Instant Chat', color: '#10b981', url: 'https://wa.me/970599000000' },
  { platform: 'Telegram', category: 'direct_chat', handle: '@mohammed_alhwaiti', color: '#00f2fe', url: 'https://t.me/mohammed_alhwaiti' },
  { platform: 'Discord', category: 'direct_chat', handle: 'alhwaiti.dev', color: '#818cf8', url: 'https://discord.com' },
  { platform: 'GitHub', category: 'social_network', handle: '@mohammed-alhwaiti', color: '#f8fafc', url: 'https://github.com' },
  { platform: 'LinkedIn', category: 'social_network', handle: 'in/mohammed-alhwaiti', color: '#38bdf8', url: 'https://linkedin.com' },
  { platform: 'Behance / UI', category: 'social_network', handle: '@mohammed-alhwaiti', color: '#ec4899', url: 'https://dribbble.com' },
  { platform: 'X (Twitter)', category: 'social_network', handle: '@alhwaiti_dev', color: '#f59e0b', url: 'https://x.com' },
  { platform: 'Instagram', category: 'social_network', handle: '@mohammed.alhwaiti', color: '#ff5500', url: 'https://instagram.com' },
  { platform: 'Facebook', category: 'social_network', handle: 'mohammed.alhwaiti', color: '#60a5fa', url: 'https://facebook.com' }
]

const form = ref({
  platform: '',
  category: 'social_network',
  handle: '',
  url: '',
  color: '#f59e0b',
  sort_order: 1,
  is_active: true
})

const sortedSocialLinks = computed(() => {
  return [...socialLinks.value].sort(
    (a, b) => (a.sort_order || a.order || 1) - (b.sort_order || b.order || 1)
  )
})

const showToast = (msg, isErr = false) => {
  toastMessage.value = msg
  isErrorToast.value = isErr
  setTimeout(() => {
    toastMessage.value = ''
  }, 4000)
}

const loadSocialLinks = async () => {
  try {
    socialLinks.value = await adminApi.getSocialLinks()
  } catch (err) {
    showToast(adminApi.formatApiError(err, 'Failed to load social links.'), true)
  }
  try {
    const cachedProf = localStorage.getItem('cached_portfolio_profile')
    if (cachedProf) {
      const parsed = JSON.parse(cachedProf)
      directContact.value = {
        email: parsed.email || directContact.value.email,
        whatsapp: parsed.whatsapp || directContact.value.whatsapp,
        location: parsed.location || directContact.value.location
      }
    }
  } catch {}
}

onMounted(() => {
  loadSocialLinks()
})

const saveDirectContact = async () => {
  try {
    await adminApi.saveDirectContactInfo(directContact.value)
    showToast('Direct Email, WhatsApp & Location synced to Public Portfolio.')
  } catch (err) {
    showToast(adminApi.formatApiError(err, 'Failed to save direct contact info.'), true)
  }
}

const applyPlatformPreset = (preset) => {
  form.value.platform = preset.platform
  form.value.category = preset.category
  form.value.handle = form.value.handle || preset.handle
  form.value.color = preset.color
  if (!form.value.url) {
    form.value.url = preset.url
  }
}

const openCreateModal = () => {
  editingLink.value = null
  modalError.value = ''
  form.value = {
    platform: '',
    category: 'social_network',
    handle: '',
    url: '',
    color: '#f59e0b',
    sort_order: socialLinks.value.length + 1,
    is_active: true
  }
  isModalOpen.value = true
}

const openEditModal = (link) => {
  editingLink.value = link
  modalError.value = ''
  form.value = {
    platform: link.platform || link.name || '',
    category: link.category || 'social_network',
    handle: link.handle || '',
    url: link.url || '',
    color: link.color || '#f59e0b',
    sort_order: link.sort_order || link.order || 1,
    is_active: link.is_active !== false
  }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  editingLink.value = null
  modalError.value = ''
  isSubmitting.value = false
}

const saveLink = async () => {
  isSubmitting.value = true
  modalError.value = ''
  try {
    if (editingLink.value) {
      await adminApi.updateSocialLink(editingLink.value.id, form.value)
      showToast(`Channel "${form.value.platform}" updated and synced.`)
    } else {
      await adminApi.createSocialLink(form.value)
      showToast(`Channel "${form.value.platform}" added to Hub.`)
    }
    await loadSocialLinks()
    closeModal()
    emit('stats-updated')
  } catch (err) {
    const detailed = adminApi.formatApiError(err, 'Failed to save social channel.')
    modalError.value = detailed
    showToast(detailed, true)
  } finally {
    isSubmitting.value = false
  }
}

const toggleActive = async (link) => {
  const nextState = link.is_active === false
  try {
    link.is_active = nextState
    await adminApi.updateSocialLink(link.id, { ...link, is_active: nextState })
    await loadSocialLinks()
    showToast(`"${link.platform}" is now ${nextState ? 'Visible' : 'Hidden'} on public portfolio.`)
  } catch (err) {
    link.is_active = !nextState
    showToast(adminApi.formatApiError(err, 'Failed to update channel status.'), true)
  }
}

const updateSortOrder = async (link, newOrder) => {
  const parsed = parseInt(newOrder, 10) || 1
  try {
    link.sort_order = parsed
    await adminApi.updateSocialLink(link.id, { ...link, sort_order: parsed })
    await loadSocialLinks()
    showToast(`Order for "${link.platform}" updated to #${parsed}.`)
  } catch (err) {
    showToast(adminApi.formatApiError(err, 'Failed to update sort order.'), true)
  }
}

const confirmDelete = (link) => {
  linkToDelete.value = link
}

const executeDelete = async () => {
  if (!linkToDelete.value) return
  const target = linkToDelete.value
  try {
    await adminApi.deleteSocialLink(target.id)
    linkToDelete.value = null
    await loadSocialLinks()
    showToast(`Channel "${target.platform}" removed.`)
    emit('stats-updated')
  } catch (err) {
    showToast(adminApi.formatApiError(err, 'Failed to delete channel.'), true)
  }
}
</script>
