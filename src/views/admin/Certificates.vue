<template>
  <div class="space-y-6">
    
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 border border-gold-500/30 text-xs font-mono text-gold-400 mb-2">
          <Award class="w-3.5 h-3.5 text-gold-400" />
          <span>Admin\CertificateController • Honors Vault</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
          Certificates &amp; Awards Manager
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 font-mono mt-1">
          Manage academic degrees, honors, fellowships, and verified credentials synced to the 3D Holographic Vault
        </p>
      </div>

      <!-- Add Certificate Button -->
      <button
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs transition-all shadow-[0_0_22px_rgba(212,175,55,0.4)] cursor-pointer self-start sm:self-auto"
      >
        <Plus class="w-4 h-4 text-slate-950 stroke-[3]" />
        <span>Add Credential / Award</span>
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

    <!-- Summary Telemetry Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
        <div>
          <span class="text-[10px] font-mono uppercase text-slate-500 block">TOTAL CREDENTIALS</span>
          <span class="text-2xl font-black text-white font-mono">{{ certificates.length }}</span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
          <Award class="w-5 h-5" />
        </div>
      </div>

      <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
        <div>
          <span class="text-[10px] font-mono uppercase text-slate-500 block">ACTIVE IN PUBLIC VAULT</span>
          <span class="text-2xl font-black text-emerald-400 font-mono">
            {{ certificates.filter(c => c.is_active !== false).length }}
          </span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
          <CheckCircle2 class="w-5 h-5" />
        </div>
      </div>

      <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
        <div>
          <span class="text-[10px] font-mono uppercase text-slate-500 block">HONORS &amp; FELLOWSHIPS</span>
          <span class="text-2xl font-black text-[#00f2fe] font-mono">100% Verified</span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-[#00f2fe]/10 border border-[#00f2fe]/30 flex items-center justify-center text-[#00f2fe]">
          <ShieldCheck class="w-5 h-5" />
        </div>
      </div>
    </div>

    <!-- Certificates Table / Grid View -->
    <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-2xl p-6 shadow-xl">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs font-sans">
          <thead>
            <tr class="border-b border-slate-800 text-[11px] font-mono uppercase text-slate-400">
              <th class="pb-3 pl-2">Badge &amp; Accent</th>
              <th class="pb-3">Credential Title &amp; Skills</th>
              <th class="pb-3">Issuer &amp; Date</th>
              <th class="pb-3">Credential ID</th>
              <th class="pb-3 text-center">Active Status</th>
              <th class="pb-3 text-right pr-2">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60">
            <tr 
              v-for="cert in certificates" 
              :key="cert.id"
              class="hover:bg-slate-900/40 transition-colors group"
            >
              <!-- Badge & Accent -->
              <td class="py-4 pl-2 whitespace-nowrap">
                <span 
                  class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono font-bold uppercase border"
                  :style="{
                    backgroundColor: `${cert.accent || '#f59e0b'}18`,
                    borderColor: `${cert.accent || '#f59e0b'}55`,
                    color: cert.accent || '#f59e0b'
                  }"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :style="{ backgroundColor: cert.accent || '#f59e0b' }"></span>
                  <span>{{ cert.badge || 'VERIFIED' }}</span>
                </span>
              </td>

              <!-- Title, Project & Skill Tags -->
              <td class="py-4 max-w-md pr-4">
                <div class="font-bold text-white text-sm">
                  {{ cert.title }}
                </div>
                <div v-if="cert.project" class="text-[11px] font-mono text-amber-400/90 mt-0.5">
                  Project: {{ cert.project }}
                </div>
                <div class="flex flex-wrap gap-1 mt-1.5">
                  <span
                    v-for="tag in (cert.tags || []).slice(0, 4)"
                    :key="tag"
                    class="px-2 py-0.5 rounded bg-slate-900 border border-slate-800 font-mono text-[10px] text-slate-400"
                  >
                    {{ tag }}
                  </span>
                </div>
              </td>

              <!-- Issuer & Date -->
              <td class="py-4">
                <div class="text-slate-200 font-medium text-xs">
                  {{ cert.issuer }}
                </div>
                <div class="text-[11px] font-mono text-[#00f2fe] mt-0.5">
                  // {{ cert.year || '2026' }}
                </div>
              </td>

              <!-- Credential Hash ID -->
              <td class="py-4 whitespace-nowrap">
                <span class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 font-mono text-[10px] text-slate-300">
                  {{ cert.hash }}
                </span>
              </td>

              <!-- Active Toggle Switch -->
              <td class="py-4 text-center whitespace-nowrap">
                <button
                  type="button"
                  @click="toggleActive(cert)"
                  class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono border transition-all cursor-pointer"
                  :class="cert.is_active !== false 
                    ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-300' 
                    : 'bg-slate-900 text-slate-500 border-slate-800'"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="cert.is_active !== false ? 'bg-emerald-400' : 'bg-slate-500'"></span>
                  <span>{{ cert.is_active !== false ? 'Active' : 'Hidden' }}</span>
                </button>
              </td>

              <!-- Actions -->
              <td class="py-4 text-right pr-2 whitespace-nowrap">
                <div class="inline-flex items-center gap-2">
                  <button
                    type="button"
                    @click="openEditModal(cert)"
                    class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-gold-300 border border-slate-800 hover:border-gold-500/30 transition-colors cursor-pointer"
                    title="Edit Credential"
                  >
                    <Edit3 class="w-3.5 h-3.5" />
                  </button>
                  <button
                    type="button"
                    @click="confirmDelete(cert)"
                    class="p-2 rounded-xl bg-red-950/30 hover:bg-red-950/60 text-red-400 hover:text-red-200 border border-red-500/20 transition-colors cursor-pointer"
                    title="Delete Credential"
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
    <!-- Add / Edit Certificate Modal                             -->
    <!-- ======================================================== -->
    <div 
      v-if="isModalOpen" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
    >
      <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto custom-scrollbar bg-[#050811] border border-gold-500/40 rounded-3xl p-6 sm:p-8 shadow-[0_0_60px_rgba(212,175,55,0.22)]">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-6">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400">
              <Award class="w-5 h-5" />
            </div>
            <div>
              <h2 class="text-lg font-bold text-white">
                {{ editingCert ? 'Edit Certificate / Award' : 'Add New Certificate / Award' }}
              </h2>
              <p class="text-xs text-slate-400 font-mono">
                Synced directly to Public 3D Holographic Certifications Vault
              </p>
            </div>
          </div>
          <button @click="closeModal" class="text-slate-400 hover:text-white p-1 rounded-lg cursor-pointer">
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="saveCert" class="space-y-4">
          
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

          <!-- Title & Issuer -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Certificate / Award Title *</label>
              <input
                v-model="form.title"
                type="text"
                required
                placeholder="e.g. Anan Award — Best Graduation Project"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Issuing Organization *</label>
              <input
                v-model="form.issuer"
                type="text"
                required
                placeholder="e.g. University College of Applied Sciences (UCAS)"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
              />
            </div>
          </div>

          <!-- Arabic Localization (AR) -->
          <div class="p-4 rounded-2xl bg-amber-500/5 border border-amber-500/20 space-y-3">
            <div class="flex items-center gap-2 text-xs font-mono text-amber-400 font-bold">
              <span>🌐 Arabic Localization (الترجمة العربية)</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-mono text-slate-300 mb-1.5">عنوان الشهادة / الجائزة بالعربية</label>
                <input
                  v-model="form.title_ar"
                  type="text"
                  dir="rtl"
                  placeholder="مثال: جائزة عنان — أفضل مشروع تخرج"
                  class="w-full bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
                />
              </div>
              <div>
                <label class="block text-xs font-mono text-slate-300 mb-1.5">الجهة المانحة بالعربية</label>
                <input
                  v-model="form.issuer_ar"
                  type="text"
                  dir="rtl"
                  placeholder="مثال: الكلية الجامعية للعلوم التطبيقية"
                  class="w-full bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
                />
              </div>
            </div>
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">الوصف والتفاصيل بالعربية</label>
              <textarea
                v-model="form.description_ar"
                rows="2"
                dir="rtl"
                placeholder="تفاصيل التكريم أو التخصص باللغة العربية..."
                class="w-full bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none resize-none"
              ></textarea>
            </div>
          </div>

          <!-- Year, Badge Text & Associated Project -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Year / Issue Date *</label>
              <input
                v-model="form.year"
                type="text"
                required
                placeholder="2026"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Badge Text *</label>
              <input
                v-model="form.badge"
                type="text"
                required
                placeholder="1ST PLACE HONOR"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono uppercase outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Associated Project</label>
              <input
                v-model="form.project"
                type="text"
                placeholder="e.g. WafarCash Smart Platform"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
              />
            </div>
          </div>

          <!-- Accent Color Selector & Credential Hash ID -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Accent Glow Color</label>
              <div class="flex items-center gap-2">
                <button
                  v-for="preset in accentPresets"
                  :key="preset.value"
                  type="button"
                  @click="form.accent = preset.value"
                  class="flex-1 py-2 px-2.5 rounded-xl border text-[11px] font-mono font-bold flex items-center justify-center gap-1.5 transition-all cursor-pointer"
                  :class="form.accent === preset.value 
                    ? 'border-white bg-white/10 text-white shadow-sm scale-105' 
                    : 'border-slate-800 bg-slate-900/70 text-slate-400 hover:text-white'"
                >
                  <span class="w-2.5 h-2.5 rounded-full" :style="{ backgroundColor: preset.value }"></span>
                  <span>{{ preset.label }}</span>
                </button>
              </div>
            </div>

            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Credential Hash ID</label>
              <input
                v-model="form.hash"
                type="text"
                placeholder="ID // VERIFIED-UCAS-2026-01"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
              />
            </div>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Credential Description &amp; Impact *</label>
            <textarea
              v-model="form.description"
              rows="3"
              required
              placeholder="Describe the honor, fellowship, or technical specialization..."
              class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none resize-none"
            ></textarea>
          </div>

          <!-- Skills (Comma-Separated or Tag Input) & Verification URL -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Skills / Tags (Comma-separated)</label>
              <input
                v-model="form.tagsInput"
                type="text"
                placeholder="FinTech Architecture, Vue 3, Laravel"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">External Verification URL (Optional)</label>
              <input
                v-model="form.url"
                type="url"
                placeholder="https://verify.ucas.edu.ps/..."
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
              />
            </div>
          </div>

          <!-- Optional Certificate Scan / Image Upload (storage/app/public) -->
          <div class="p-3.5 rounded-2xl bg-slate-900/60 border border-slate-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <label class="block text-xs font-mono text-slate-300">Certificate Scan / Image Upload (Optional)</label>
              <span class="text-[10px] font-mono text-slate-500">Uploads to storage/app/public via POST /api/admin/media/upload</span>
            </div>
            <input
              type="file"
              accept="image/*,.pdf"
              @change="handleCertFileUpload"
              class="text-xs font-mono text-slate-400 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500/20 file:text-amber-300 hover:file:bg-amber-500/30 cursor-pointer"
            />
          </div>

          <!-- Active Checkbox -->
          <div class="pt-1">
            <label class="inline-flex items-center gap-2 cursor-pointer select-none text-xs font-mono text-slate-300">
              <input
                v-model="form.is_active"
                type="checkbox"
                class="rounded bg-slate-900 border-slate-700 text-gold-500 h-4 w-4 accent-amber-500 cursor-pointer"
              />
              <span>Display in Public 3D Holographic Certifications Vault</span>
            </label>
          </div>

          <!-- Submit Actions -->
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
              class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(212,175,55,0.35)] cursor-pointer disabled:opacity-50"
            >
              <span v-if="isSubmitting" class="w-3.5 h-3.5 rounded-full border-2 border-slate-950 border-t-transparent animate-spin"></span>
              <span>{{ isSubmitting ? 'Saving...' : (editingCert ? 'Save Changes' : 'Add Credential') }}</span>
            </button>
          </div>

        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div 
      v-if="certToDelete" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
    >
      <div class="relative w-full max-w-md bg-[#050811] border border-red-500/40 rounded-2xl p-6 shadow-[0_0_40px_rgba(239,68,68,0.25)]">
        <h3 class="text-base font-bold text-white mb-2 flex items-center gap-2">
          <AlertCircle class="w-5 h-5 text-red-400" />
          <span>Confirm Credential Deletion</span>
        </h3>
        <p class="text-xs text-slate-300 mb-6">
          Are you sure you want to remove <strong class="text-white">{{ certToDelete.title }}</strong> from the Certifications Vault?
        </p>
        <div class="flex items-center justify-end gap-3 font-mono text-xs">
          <button @click="certToDelete = null" class="px-4 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white cursor-pointer">
            Cancel
          </button>
          <button @click="executeDelete" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold cursor-pointer">
            Yes, Delete
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Award, Plus, Edit3, Trash2, X, CheckCircle2, AlertCircle, ShieldCheck } from 'lucide-vue-next'
import { adminApi } from '../../services/adminApi'

const emit = defineEmits(['stats-updated'])

const certificates = ref([])
const toastMessage = ref('')
const isErrorToast = ref(false)
const modalError = ref('')
const isSubmitting = ref(false)
const isModalOpen = ref(false)
const editingCert = ref(null)
const certToDelete = ref(null)
const selectedCertFile = ref(null)

const accentPresets = [
  { label: 'Amber', value: '#f59e0b' },
  { label: 'Cyan', value: '#00f2fe' },
  { label: 'Emerald', value: '#10b981' },
  { label: 'Orange', value: '#ff5500' }
]

const form = ref({
  title: '',
  title_ar: '',
  issuer: '',
  issuer_ar: '',
  year: '2026',
  badge: 'VERIFIED CREDENTIAL',
  badge_ar: '',
  project: '',
  accent: '#f59e0b',
  hash: 'ID // VERIFIED-2026',
  description: '',
  description_ar: '',
  tagsInput: '',
  url: '',
  image: '',
  is_active: true
})

const showToast = (msg, isErr = false) => {
  toastMessage.value = msg
  isErrorToast.value = isErr
  setTimeout(() => {
    toastMessage.value = ''
  }, 4000)
}

const loadCertificates = async () => {
  try {
    certificates.value = await adminApi.getCertificates()
  } catch (err) {
    showToast(adminApi.formatApiError(err, 'Failed to load certificates.'), true)
  }
}

onMounted(() => {
  loadCertificates()
})

const handleCertFileUpload = (e) => {
  const file = e.target.files?.[0]
  if (file) {
    selectedCertFile.value = file
  }
}

const openCreateModal = () => {
  editingCert.value = null
  selectedCertFile.value = null
  modalError.value = ''
  const nextIndex = String(certificates.value.length + 1).padStart(2, '0')
  form.value = {
    title: '',
    title_ar: '',
    issuer: '',
    issuer_ar: '',
    year: '2026',
    badge: 'VERIFIED CREDENTIAL',
    badge_ar: '',
    project: '',
    accent: '#f59e0b',
    hash: `ID // VERIFIED-2026-${nextIndex}`,
    description: '',
    description_ar: '',
    tagsInput: '',
    url: '',
    image: '',
    is_active: true
  }
  isModalOpen.value = true
}

const openEditModal = (cert) => {
  editingCert.value = cert
  selectedCertFile.value = null
  modalError.value = ''
  form.value = {
    title: cert.title || '',
    title_ar: cert.title_ar || cert.titleAr || '',
    issuer: cert.issuer || '',
    issuer_ar: cert.issuer_ar || cert.issuerAr || '',
    year: cert.year || cert.date || cert.issue_date || '2026',
    badge: cert.badge || 'VERIFIED CREDENTIAL',
    badge_ar: cert.badge_ar || cert.badgeAr || '',
    project: cert.project || '',
    accent: cert.accent || '#f59e0b',
    hash: cert.hash || 'ID // VERIFIED-2026',
    description: cert.description || '',
    description_ar: cert.description_ar || cert.descriptionAr || '',
    tagsInput: Array.isArray(cert.tags) ? cert.tags.join(', ') : (cert.tags || ''),
    url: cert.url || '',
    image: cert.image || '',
    is_active: cert.is_active !== false
  }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  editingCert.value = null
  selectedCertFile.value = null
  modalError.value = ''
  isSubmitting.value = false
}

const saveCert = async () => {
  isSubmitting.value = true
  modalError.value = ''
  const tags = form.value.tagsInput
    .split(',')
    .map((s) => s.trim())
    .filter(Boolean)

  try {
    await adminApi.saveCertificate(
      {
        ...form.value,
        tags
      },
      editingCert.value?.id || null,
      selectedCertFile.value
    )

    await loadCertificates()
    showToast(
      editingCert.value
        ? `Credential "${form.value.title}" updated and synced.`
        : `Credential "${form.value.title}" added to Honors Vault.`
    )
    closeModal()
    emit('stats-updated')
  } catch (err) {
    const detailed = adminApi.formatApiError(err, 'Failed to save credential.')
    modalError.value = detailed
    showToast(detailed, true)
  } finally {
    isSubmitting.value = false
  }
}

const toggleActive = async (cert) => {
  const nextState = cert.is_active === false
  try {
    cert.is_active = nextState
    await adminApi.saveCertificate(cert, cert.id)
    await loadCertificates()
    showToast(`"${cert.title}" is now ${nextState ? 'Visible' : 'Hidden'} on public portfolio.`)
  } catch (err) {
    cert.is_active = !nextState
    showToast(adminApi.formatApiError(err, 'Failed to update status.'), true)
  }
}

const confirmDelete = (cert) => {
  certToDelete.value = cert
}

const executeDelete = async () => {
  if (!certToDelete.value) return
  const target = certToDelete.value
  try {
    await adminApi.deleteCertificate(target.id)
    certToDelete.value = null
    await loadCertificates()
    showToast(`Credential "${target.title}" removed.`)
    emit('stats-updated')
  } catch (err) {
    showToast(adminApi.formatApiError(err, 'Failed to delete credential.'), true)
  }
}
</script>
