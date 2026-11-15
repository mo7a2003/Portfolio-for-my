<template>
  <div class="space-y-8 max-w-5xl">
    
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 border border-gold-500/30 text-xs font-mono text-gold-400 mb-2">
          <UserCheck class="w-3.5 h-3.5 text-gold-400" />
          <span>Admin\ProfileController Endpoints</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
          Profile & Identity Management
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 font-mono mt-1">
          Update personal branding, biography, contact information and master access passkey
        </p>
      </div>

      <!-- Save Profile Button -->
      <button
        @click="saveProfile"
        :disabled="isSaving"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs transition-all shadow-[0_0_22px_rgba(212,175,55,0.4)] cursor-pointer self-start sm:self-auto disabled:opacity-50"
      >
        <Save class="w-4 h-4 text-slate-950 stroke-[2.5]" />
        <span>{{ isSaving ? 'Saving...' : 'Save Profile Changes' }}</span>
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
        <button @click="toastMessage = ''" class="hover:text-white" :class="isErrorToast ? 'text-red-400' : 'text-emerald-400'">
          <X class="w-3.5 h-3.5" />
        </button>
      </div>
    </transition>

    <!-- SECTION 1: Personal Branding & Identity Form -->
    <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl">
      <div class="flex items-center gap-3 pb-4 border-b border-slate-800/80 mb-6">
        <div class="w-9 h-9 rounded-xl bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400">
          <User class="w-5 h-5 text-gold-400" />
        </div>
        <div>
          <h2 class="text-base sm:text-lg font-bold text-white">
            Personal Branding & Bio
          </h2>
          <p class="text-xs text-slate-400 font-mono">
            Directly reflected across the Hero, About, and Footer sections
          </p>
        </div>
      </div>

      <form @submit.prevent="saveProfile" class="space-y-6">
        
        <!-- Live Avatar & Portrait Preview Row -->
        <div class="flex flex-col sm:flex-row items-center gap-6 p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80">
          <div class="relative shrink-0">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-gold-400 to-amber-600 p-[2px] shadow-[0_0_22px_rgba(212,175,55,0.4)]">
              <img 
                :src="portraitPreview || resolveAssetUrl(profile.portrait) || '/mohammed-portrait.png'" 
                alt="Portrait" 
                class="w-full h-full object-cover rounded-[14px] bg-slate-900"
                @error="(e) => e.target.src = '/mohammed-portrait.png'"
              />
            </div>
          </div>
          <div class="flex-1 space-y-3 w-full">
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1">Hero 3D Portrait Image (Upload or URL)</label>
              <div class="flex flex-col sm:flex-row gap-2">
                <input
                  v-model="profile.portrait"
                  type="text"
                  placeholder="/mohammed-portrait.png"
                  class="flex-1 bg-slate-950 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2 text-xs font-mono text-white outline-none"
                />
                <label class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-gold-500/15 hover:bg-gold-500/25 border border-gold-500/30 text-gold-300 text-xs font-mono cursor-pointer transition-colors shrink-0">
                  <UploadCloud class="w-3.5 h-3.5" />
                  <span>{{ isUploadingPortrait ? 'Uploading...' : 'Upload Portrait' }}</span>
                  <input type="file" accept="image/*" @change="handlePortraitUpload" class="hidden" :disabled="isUploadingPortrait" />
                </label>
              </div>
            </div>
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1">Navbar & About Mini Avatar (Upload or URL)</label>
              <div class="flex flex-col sm:flex-row gap-2">
                <input
                  v-model="profile.avatar"
                  type="text"
                  placeholder="/developer-portrait.jpg"
                  class="flex-1 bg-slate-950 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2 text-xs font-mono text-white outline-none"
                />
                <label class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 text-xs font-mono cursor-pointer transition-colors shrink-0">
                  <UploadCloud class="w-3.5 h-3.5" />
                  <span>{{ isUploadingAvatar ? 'Uploading...' : 'Upload Avatar' }}</span>
                  <input type="file" accept="image/*" @change="handleAvatarUpload" class="hidden" :disabled="isUploadingAvatar" />
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Name & Professional Titles -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Full English Name *</label>
            <input
              v-model="profile.name"
              type="text"
              required
              class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Full Arabic Name</label>
            <input
              v-model="profile.nameAr"
              type="text"
              dir="rtl"
              class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Professional Title *</label>
            <input
              v-model="profile.title"
              type="text"
              required
              class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Location / Availability</label>
            <input
              v-model="profile.location"
              type="text"
              class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>
        </div>

        <!-- Bios -->
        <div>
          <label class="block text-xs font-mono text-slate-300 mb-1.5">Short Bio / Subtitle (Hero Tagline)</label>
          <input
            v-model="profile.shortBio"
            type="text"
            placeholder="Crafting exceptional, interactive 3D web experiences..."
            class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
          />
        </div>

        <div>
          <label class="block text-xs font-mono text-slate-300 mb-1.5">Comprehensive Biography (About Section)</label>
          <textarea
            v-model="profile.fullBio"
            rows="4"
            placeholder="A passionate front-end developer and UI/UX designer..."
            class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white leading-relaxed outline-none"
          ></textarea>
        </div>

        <!-- Contact Information -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Direct Contact Email *</label>
            <input
              v-model="profile.email"
              type="email"
              required
              class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">WhatsApp Contact URL or Number</label>
            <input
              v-model="profile.whatsapp"
              type="text"
              placeholder="https://wa.me/970599000000"
              class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
            />
          </div>
        </div>

      </form>
    </div>

    <!-- SECTION 2: Curriculum Vitae (CV) & Resume Management -->
    <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800/80">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400 shadow-[0_0_15px_rgba(212,175,55,0.2)]">
            <FileText class="w-5 h-5 text-gold-400" />
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h2 class="text-base sm:text-lg font-bold text-white">
                Curriculum Vitae (CV) & Resume Document
              </h2>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-gold-500/10 text-gold-400 border border-gold-500/30">
                PDF / DOCX
              </span>
            </div>
            <p class="text-xs text-slate-400 font-mono mt-0.5">
              Available for public download across the Hero & About sections (GET /api/cv/download)
            </p>
          </div>
        </div>

        <!-- Hidden Native File Input -->
        <input 
          ref="cvFileInput"
          type="file"
          accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
          @change="handleCvFileUpload"
          class="hidden"
        />

        <!-- Trigger Button -->
        <button
          type="button"
          @click="triggerCvFileSelect"
          :disabled="isUploadingCv"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs transition-all shadow-[0_0_20px_rgba(212,175,55,0.3)] cursor-pointer disabled:opacity-50 self-start sm:self-auto"
        >
          <span v-if="isUploadingCv" class="w-3.5 h-3.5 rounded-full border-2 border-slate-950 border-t-transparent animate-spin"></span>
          <UploadCloud v-else class="w-4 h-4 stroke-[2.5]" />
          <span>{{ isUploadingCv ? 'Uploading Document...' : (activeCv ? 'Upload / Replace CV' : 'Upload First CV') }}</span>
        </button>
      </div>

      <!-- ACTIVE CV CARD (Shown when CV exists) -->
      <div 
        v-if="activeCv" 
        class="p-5 sm:p-6 rounded-2xl bg-gradient-to-b from-slate-900/80 via-slate-950/90 to-slate-950 border border-gold-500/30 shadow-[0_0_30px_rgba(212,175,55,0.12)] space-y-4"
      >
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          
          <!-- File Details -->
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500/20 to-gold-500/10 border border-gold-500/40 flex flex-col items-center justify-center shrink-0 shadow-gold-glow/20">
              <FileCheck class="w-6 h-6 text-gold-400 mb-0.5" />
              <span class="text-[9px] font-mono font-bold text-gold-300 uppercase">PDF</span>
            </div>
            
            <div class="space-y-1">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-bold text-white tracking-wide font-mono break-all">
                  {{ activeCv.filename || 'Mohammed_Alhwaiti_CV.pdf' }}
                </span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-emerald-500/15 border border-emerald-500/40 text-emerald-400 flex items-center gap-1.5">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                  <span>Active & Downloadable</span>
                </span>
              </div>
              <p class="text-xs font-mono text-slate-400">
                Uploaded: <span class="text-slate-300">{{ formatCvDate(activeCv.updatedAt) }}</span>
                <span class="mx-2 text-slate-600">•</span>
                Size: <span class="text-gold-400">{{ activeCv.size || '245 KB' }}</span>
              </p>
            </div>
          </div>

          <!-- Action Buttons: Download, Replace, Delete -->
          <div class="flex items-center gap-2 self-start md:self-auto flex-wrap font-mono text-xs">
            <!-- Download Button -->
            <button
              type="button"
              @click="testDownloadCv"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 hover:border-gold-500/40 text-slate-200 hover:text-white transition-all cursor-pointer"
              title="Test download file"
            >
              <Download class="w-3.5 h-3.5 text-cyan-400" />
              <span>Download</span>
            </button>

            <!-- Replace Button -->
            <button
              type="button"
              @click="triggerCvFileSelect"
              :disabled="isUploadingCv"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 hover:border-gold-500/40 text-slate-200 hover:text-white transition-all cursor-pointer disabled:opacity-50"
              title="Upload a new version of the CV"
            >
              <RefreshCw class="w-3.5 h-3.5 text-gold-400" />
              <span>Replace</span>
            </button>

            <!-- Delete Button -->
            <button
              type="button"
              @click="deleteCv"
              :disabled="isDeletingCv"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-red-950/30 hover:bg-red-950/60 border border-red-500/30 hover:border-red-500/60 text-red-400 hover:text-red-200 transition-all cursor-pointer disabled:opacity-50"
              title="Delete CV document"
            >
              <span v-if="isDeletingCv" class="w-3.5 h-3.5 rounded-full border-2 border-red-400 border-t-transparent animate-spin"></span>
              <Trash2 v-else class="w-3.5 h-3.5" />
              <span>Delete</span>
            </button>
          </div>

        </div>
      </div>

      <!-- EMPTY STATE / DROPZONE (Shown when no active CV) -->
      <div 
        v-else 
        @click="triggerCvFileSelect"
        class="border-2 border-dashed border-slate-800 hover:border-gold-500/50 rounded-2xl p-8 text-center bg-slate-900/30 hover:bg-gold-500/[0.02] transition-all cursor-pointer group space-y-3"
      >
        <div class="w-12 h-12 rounded-2xl bg-gold-500/10 border border-gold-500/20 text-gold-400 flex items-center justify-center mx-auto group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(212,175,55,0.15)]">
          <UploadCloud class="w-6 h-6 text-gold-400" />
        </div>
        <div>
          <h3 class="text-sm font-bold text-white group-hover:text-gold-300 transition-colors">
            No Active CV Document Uploaded Yet
          </h3>
          <p class="text-xs text-slate-400 font-mono mt-1">
            Click here to upload your resume (PDF or DOCX up to 15MB)
          </p>
        </div>
      </div>

    </div>

    <!-- SECTION 3: Security & Admin Passkey Form -->
    <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl">
      <div class="flex items-center gap-3 pb-4 border-b border-slate-800/80 mb-6">
        <div class="w-9 h-9 rounded-xl bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400">
          <KeyRound class="w-5 h-5 text-gold-400" />
        </div>
        <div>
          <h2 class="text-base sm:text-lg font-bold text-white">
            Security & Authentication Passkey
          </h2>
          <p class="text-xs text-slate-400 font-mono">
            Update Laravel Sanctum admin credentials and passkey protection
          </p>
        </div>
      </div>

      <form @submit.prevent="updatePassword" class="space-y-4 max-w-xl">
        
        <div>
          <label class="block text-xs font-mono text-slate-300 mb-1.5">Current Admin Passkey *</label>
          <input
            v-model="passwordForm.current_password"
            type="password"
            required
            placeholder="••••••••••••"
            class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
          />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">New Passkey (min 8 chars) *</label>
            <input
              v-model="passwordForm.new_password"
              type="password"
              required
              minlength="8"
              placeholder="••••••••••••"
              class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
            />
          </div>
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Confirm New Passkey *</label>
            <input
              v-model="passwordForm.new_password_confirmation"
              type="password"
              required
              minlength="8"
              placeholder="••••••••••••"
              class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
            />
          </div>
        </div>

        <!-- Password Error or Success Message -->
        <div v-if="passwordError" class="p-3 rounded-xl bg-red-950/60 border border-red-500/40 text-red-200 text-xs font-mono">
          {{ passwordError }}
        </div>

        <div class="pt-2">
          <button
            type="submit"
            :disabled="isUpdatingPassword"
            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs font-mono shadow-[0_0_20px_rgba(212,175,55,0.35)] cursor-pointer disabled:opacity-50"
          >
            {{ isUpdatingPassword ? 'Updating...' : 'Update Passkey' }}
          </button>
        </div>

      </form>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { 
  UserCheck, 
  User, 
  Save, 
  KeyRound, 
  CheckCircle2, 
  AlertCircle,
  FileText,
  UploadCloud,
  Download,
  Trash2,
  RefreshCw,
  FileCheck,
  X 
} from 'lucide-vue-next'
import { adminApi } from '../../services/adminApi'
import { portfolioService, resolveAssetUrl } from '../../services/portfolioService'
import { setProfileState } from '../../services/profileState'

const isUploadingPortrait = ref(false)
const isUploadingAvatar = ref(false)
const selectedPortraitFile = ref(null)
const selectedAvatarFile = ref(null)
const portraitPreview = ref('')
const avatarPreview = ref('')

const handlePortraitUpload = async (event) => {
  const file = event.target.files?.[0]
  if (!file) return
  selectedPortraitFile.value = file
  portraitPreview.value = URL.createObjectURL(file)
  isUploadingPortrait.value = true
  try {
    const uploaded = await adminApi.uploadMedia(file)
    if (uploaded?.url || uploaded?.path) {
      const resolvedUrl = resolveAssetUrl(uploaded.url || uploaded.path)
      profile.value.portrait = resolvedUrl
      selectedPortraitFile.value = null
      setProfileState({ ...profile.value, portrait: resolvedUrl, portrait_image: resolvedUrl, portrait_url: resolvedUrl })
      toastMessage.value = `Portrait image "${file.name}" uploaded and synced.`
      isErrorToast.value = false
    }
  } catch (err) {
    toastMessage.value = adminApi.formatApiError(err, 'Failed to upload portrait image.')
    isErrorToast.value = true
  } finally {
    isUploadingPortrait.value = false
    if (event.target) event.target.value = ''
    setTimeout(() => { toastMessage.value = '' }, 4000)
  }
}

const handleAvatarUpload = async (event) => {
  const file = event.target.files?.[0]
  if (!file) return
  selectedAvatarFile.value = file
  avatarPreview.value = URL.createObjectURL(file)
  isUploadingAvatar.value = true
  try {
    const uploaded = await adminApi.uploadMedia(file)
    if (uploaded?.url || uploaded?.path) {
      const resolvedUrl = resolveAssetUrl(uploaded.url || uploaded.path)
      profile.value.avatar = resolvedUrl
      selectedAvatarFile.value = null
      setProfileState({ ...profile.value, avatar: resolvedUrl, avatar_url: resolvedUrl })
      toastMessage.value = `Navbar avatar "${file.name}" uploaded and synced.`
      isErrorToast.value = false
    }
  } catch (err) {
    toastMessage.value = adminApi.formatApiError(err, 'Failed to upload avatar image.')
    isErrorToast.value = true
  } finally {
    isUploadingAvatar.value = false
    if (event.target) event.target.value = ''
    setTimeout(() => { toastMessage.value = '' }, 4000)
  }
}

const profile = ref({
  name: '',
  nameAr: '',
  title: '',
  subtitle: '',
  shortBio: '',
  fullBio: '',
  bio: '',
  location: '',
  email: '',
  whatsapp: '',
  phone: '',
  portrait: '',
  avatar: '',
  cv: '',
  cv_url: '',
  cv_name: '',
  cv_size: '',
  cv_updated_at: ''
})

const activeCv = ref(null)
const isUploadingCv = ref(false)
const isDeletingCv = ref(false)
const cvFileInput = ref(null)

const passwordForm = ref({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

const isSaving = ref(false)
const isUpdatingPassword = ref(false)
const toastMessage = ref('')
const isErrorToast = ref(false)
const passwordError = ref('')

const formatCvDate = (dateStr) => {
  if (!dateStr) return 'Recently uploaded'
  try {
    const d = new Date(dateStr)
    if (isNaN(d.getTime())) return dateStr
    return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
  } catch (e) {
    return dateStr
  }
}

const loadCv = async () => {
  try {
    const details = await adminApi.getCvDetails()
    if (details && (details.filename || details.url)) {
      activeCv.value = details
    } else if (profile.value?.cv || profile.value?.cv_url) {
      activeCv.value = {
        url: profile.value.cv_url || profile.value.cv,
        filename: profile.value.cv_name || profile.value.cv_filename || 'Mohammed_Alhwaiti_CV.pdf',
        size: profile.value.cv_size || '245 KB',
        updatedAt: profile.value.cv_updated_at || new Date().toISOString()
      }
    }
  } catch (e) {
    console.warn('Error loading CV details:', e)
  }
}

const triggerCvFileSelect = () => {
  if (cvFileInput.value) cvFileInput.value.click()
}

const handleCvFileUpload = async (event) => {
  const file = event.target.files?.[0]
  if (!file) return

  // Validate format
  const validExts = ['.pdf', '.doc', '.docx']
  const ext = file.name.substring(file.name.lastIndexOf('.')).toLowerCase()
  if (!validExts.includes(ext)) {
    toastMessage.value = 'Invalid document format. Please upload a PDF or DOCX file.'
    isErrorToast.value = true
    return
  }

  isUploadingCv.value = true
  toastMessage.value = ''
  isErrorToast.value = false

  try {
    const uploaded = await adminApi.uploadCv(file)
    activeCv.value = uploaded
    toastMessage.value = `CV document "${file.name}" uploaded and published successfully.`
    isErrorToast.value = false
    setTimeout(() => { toastMessage.value = '' }, 4000)
  } catch (err) {
    toastMessage.value = adminApi.formatApiError(err, 'Failed to upload CV document.')
    isErrorToast.value = true
  } finally {
    isUploadingCv.value = false
    if (event.target) event.target.value = ''
  }
}

const deleteCv = async () => {
  if (!confirm('Are you sure you want to delete the active CV document? Visitors will no longer be able to download it.')) {
    return
  }

  isDeletingCv.value = true
  toastMessage.value = ''
  isErrorToast.value = false

  try {
    await adminApi.deleteCv()
    activeCv.value = null
    toastMessage.value = 'CV document successfully deleted.'
    isErrorToast.value = false
    setTimeout(() => { toastMessage.value = '' }, 4000)
  } catch (err) {
    toastMessage.value = adminApi.formatApiError(err, 'Failed to delete CV.')
    isErrorToast.value = true
  } finally {
    isDeletingCv.value = false
  }
}

const testDownloadCv = async () => {
  try {
    await portfolioService.downloadCv(activeCv.value?.url)
  } catch (err) {
    toastMessage.value = 'Could not trigger download: ' + err.message
    isErrorToast.value = true
  }
}

const loadProfile = async () => {
  try {
    const res = await adminApi.getProfile()
    if (res && typeof res === 'object') {
      const data = res.data || res
      profile.value = {
        ...profile.value,
        ...data,
        name: data.name || data.full_name || profile.value.name,
        nameAr: data.nameAr || data.name_ar || profile.value.nameAr,
        title: data.title || data.headline || data.role || profile.value.title,
        subtitle: data.subtitle || data.tagline || data.short_bio || data.shortBio || profile.value.subtitle,
        shortBio: data.shortBio || data.short_bio || data.subtitle || data.summary || profile.value.shortBio,
        fullBio: data.fullBio || data.full_bio || data.bio || data.about || profile.value.fullBio,
        bio: data.bio || data.full_bio || data.fullBio || profile.value.bio,
        location: data.location || profile.value.location,
        email: data.email || data.contact_email || profile.value.email,
        whatsapp: data.whatsapp || data.whatsapp_url || profile.value.whatsapp,
        portrait: data.portrait || data.portrait_url || profile.value.portrait,
        avatar: data.avatar || data.avatar_url || profile.value.avatar
      }
    }
  } catch (err) {
    console.error('Failed to load profile', err)
  } finally {
    await loadCv()
  }
}

onMounted(() => {
  loadProfile()
})

const saveProfile = async () => {
  isSaving.value = true
  toastMessage.value = ''
  isErrorToast.value = false
  try {
    const payload = {
      ...profile.value,
      name: profile.value.name,
      name_ar: profile.value.nameAr,
      nameAr: profile.value.nameAr,
      title: profile.value.title,
      subtitle: profile.value.shortBio || profile.value.subtitle || profile.value.title,
      short_bio: profile.value.shortBio,
      shortBio: profile.value.shortBio,
      full_bio: profile.value.fullBio,
      fullBio: profile.value.fullBio,
      bio: profile.value.fullBio || profile.value.shortBio,
      about: profile.value.fullBio || profile.value.shortBio,
      location: profile.value.location,
      email: profile.value.email,
      whatsapp: profile.value.whatsapp || null,
      portrait: profile.value.portrait || null,
      portrait_image: profile.value.portrait || null,
      portrait_url: profile.value.portrait || null,
      avatar: profile.value.avatar || null,
      avatar_url: profile.value.avatar || null
    }
    const updated = await adminApi.updateProfile(payload, {
      portraitFile: selectedPortraitFile.value,
      avatarFile: selectedAvatarFile.value
    })
    if (updated) {
      const data = updated.data || updated
      if (typeof data === 'object') {
        profile.value = { ...profile.value, ...data }
        setProfileState(profile.value)
      }
    }
    selectedPortraitFile.value = null
    selectedAvatarFile.value = null
    portraitPreview.value = ''
    avatarPreview.value = ''
    localStorage.setItem('portfolio_sync_trigger', Date.now().toString())
    window.dispatchEvent(new CustomEvent('portfolio-updated'))
    toastMessage.value = 'Personal branding and profile details saved successfully.'
    isErrorToast.value = false
    setTimeout(() => { toastMessage.value = '' }, 4000)
  } catch (err) {
    console.error('Failed to save profile', err)
    toastMessage.value = adminApi.formatApiError(err, 'Failed to save profile.')
    isErrorToast.value = true
  } finally {
    isSaving.value = false
  }
}

const updatePassword = async () => {
  passwordError.value = ''
  if (passwordForm.value.new_password !== passwordForm.value.new_password_confirmation) {
    passwordError.value = 'New passkey and confirmation do not match.'
    return
  }

  isUpdatingPassword.value = true
  try {
    await adminApi.updatePassword(passwordForm.value)
    toastMessage.value = 'Admin authentication passkey updated successfully.'
    isErrorToast.value = false
    passwordForm.value = {
      current_password: '',
      new_password: '',
      new_password_confirmation: ''
    }
    setTimeout(() => { toastMessage.value = '' }, 4000)
  } catch (err) {
    passwordError.value = adminApi.formatApiError(err, 'Passkey update failed.')
  } finally {
    isUpdatingPassword.value = false
  }
}
</script>

