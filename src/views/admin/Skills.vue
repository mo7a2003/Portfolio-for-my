<template>
  <div class="space-y-6">
    
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 border border-gold-500/30 text-xs font-mono text-gold-400 mb-2">
          <Cpu class="w-3.5 h-3.5 text-gold-400" />
          <span>Admin\SkillController Endpoints</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
          Technical Skills & Capabilities
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 font-mono mt-1">
          Manage core frontend, 3D WebGL, backend and design stack badges
        </p>
      </div>

      <!-- Add / Toggle Skill Button -->
      <button
        v-if="!isModalOpen"
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs transition-all shadow-[0_0_22px_rgba(212,175,55,0.4)] cursor-pointer self-start sm:self-auto"
      >
        <Plus class="w-4 h-4 text-slate-950 stroke-[3]" />
        <span>Add New Skill</span>
      </button>
      <button
        v-else
        @click="closeModal"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 hover:border-gold-500/40 text-slate-300 hover:text-white text-xs font-mono transition-all cursor-pointer self-start sm:self-auto"
      >
        <X class="w-4 h-4" />
        <span>Back to Skills Matrix</span>
      </button>
    </div>

    <!-- ======================================================== -->
    <!-- Add / Edit Skill Card Studio Container                   -->
    <!-- ======================================================== -->
    <div 
      v-if="isModalOpen" 
      class="max-w-3xl mx-auto w-full my-2"
    >
      <div class="p-6 sm:p-8 md:p-10 rounded-3xl bg-slate-950/80 border border-amber-500/30 backdrop-blur-2xl shadow-[0_0_60px_rgba(212,175,55,0.15)] space-y-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-800">
          <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400 shadow-[0_0_20px_rgba(212,175,55,0.2)]">
              <Cpu class="w-6 h-6 text-gold-400" />
            </div>
            <div>
              <div class="flex items-center gap-2">
                <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                  {{ editingSkill ? `Edit Skill: ${form.name}` : 'Add Technical Skill' }}
                </h2>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-amber-500/10 text-amber-300 border border-amber-500/30">
                  {{ editingSkill ? 'PUT/PATCH' : 'POST' }}
                </span>
              </div>
              <p class="text-xs text-slate-400 font-mono mt-1">
                Admin\SkillController@{{ editingSkill ? 'update' : 'store' }} // Dynamic Stack Badge
              </p>
            </div>
          </div>

          <button 
            type="button"
            @click="closeModal" 
            class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900/90 hover:bg-slate-800 border border-slate-800 hover:border-gold-500/40 text-slate-400 hover:text-white text-xs font-mono transition-all cursor-pointer"
          >
            <X class="w-4 h-4" />
            <span>Close Form</span>
          </button>
        </div>

        <form @submit.prevent="saveSkill" class="space-y-6">
          
          <!-- Inline Error Banner -->
          <div 
            v-if="modalError" 
            class="p-4 rounded-xl bg-red-950/70 border border-red-500/40 text-red-200 text-xs font-mono flex items-center justify-between shadow-[0_0_20px_rgba(239,68,68,0.25)]"
          >
            <div class="flex items-center gap-2.5">
              <AlertCircle class="w-5 h-5 text-red-400 shrink-0" />
              <span>{{ modalError }}</span>
            </div>
            <button type="button" @click="modalError = ''" class="text-red-400 hover:text-white">
              <X class="w-4 h-4" />
            </button>
          </div>

          <!-- Section 1: Skill Info -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-2">Skill Name *</label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="e.g. TypeScript, Three.js, Docker"
                class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 text-white placeholder-slate-500 rounded-xl px-4 py-3 text-xs outline-none transition-all font-mono"
              />
            </div>

            <div>
              <label class="block text-xs font-mono text-slate-300 mb-2">Category *</label>
              <input
                v-model="form.category"
                type="text"
                required
                placeholder="e.g. Frontend, 3D & Motion, Backend"
                class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 text-white placeholder-slate-500 rounded-xl px-4 py-3 text-xs outline-none transition-all font-mono"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-2">Proficiency Level</label>
              <select
                v-model="form.level"
                class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white outline-none font-mono cursor-pointer"
              >
                <option value="Master">Master</option>
                <option value="Expert">Expert</option>
                <option value="Advanced">Advanced</option>
                <option value="Proficient">Proficient</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-mono text-slate-300 mb-2">Brand Color Hex</label>
              <div class="flex items-center gap-2.5">
                <input
                  v-model="form.color"
                  type="text"
                  placeholder="#fbbf24"
                  class="flex-1 bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white font-mono outline-none transition-all"
                />
                <input 
                  v-model="form.color" 
                  type="color" 
                  class="w-11 h-11 rounded-xl cursor-pointer bg-slate-900 border border-slate-800 p-1" 
                />
              </div>
            </div>
          </div>

          <!-- Skill Icon / SVG Upload -->
          <div class="space-y-2">
            <label class="block text-xs font-mono text-slate-300">Skill Icon / SVG Logo</label>
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80">
              <div v-if="iconPreview || form.icon" class="w-12 h-12 rounded-xl overflow-hidden border border-amber-500/40 bg-slate-950 flex items-center justify-center shrink-0 shadow-[0_0_15px_rgba(245,158,11,0.2)]">
                <img 
                  :src="iconPreview || resolveAssetUrl(form.icon) || form.icon" 
                  alt="Icon" 
                  class="w-8 h-8 object-contain" 
                  @error="(e) => e.target.style.display = 'none'" 
                />
              </div>
              <div class="flex-1 space-y-2">
                <input 
                  type="file" 
                  @change="handleIconUpload" 
                  accept="image/*,.svg"
                  class="w-full text-xs font-mono text-slate-400 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500/20 file:text-amber-300 hover:file:bg-amber-500/30 cursor-pointer"
                />
                <input
                  v-model="form.icon"
                  type="text"
                  placeholder="Or enter icon name/URL (e.g. vue, react, laravel, /icons/vue.svg)"
                  class="w-full bg-slate-950/80 border border-slate-800/80 rounded-xl px-3 py-1.5 text-xs text-slate-300 font-mono outline-none"
                />
              </div>
            </div>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-2">Skill Summary / Use-Case *</label>
            <textarea
              v-model="form.description"
              rows="3"
              required
              placeholder="e.g. Static typing, generics, robust frontend architectural interfaces..."
              class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl p-4 text-xs text-white placeholder-slate-500 outline-none transition-all leading-relaxed"
            ></textarea>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-slate-800">
            <button
              type="button"
              @click="closeModal"
              :disabled="isSubmitting"
              class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 hover:text-white text-xs font-mono cursor-pointer disabled:opacity-50"
            >
              Cancel & Discard
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs tracking-wide shadow-[0_0_30px_rgba(212,175,55,0.4)] cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="isSubmitting" class="w-4 h-4 rounded-full border-2 border-slate-950 border-t-transparent animate-spin"></span>
              <span>{{ isSubmitting ? (editingSkill ? 'Saving Changes...' : 'Uploading & Adding...') : (editingSkill ? 'Save Skill Changes' : 'Add Skill to Matrix') }}</span>
            </button>
          </div>

        </form>

      </div>
    </div>

    <!-- ======================================================== -->
    <!-- Skills Matrix & Filters View (Shown when not editing)   -->
    <!-- ======================================================== -->
    <div v-else class="space-y-6">

      <!-- Search & Category Filters -->
      <div class="backdrop-blur-xl bg-slate-950/60 border border-slate-800 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Search Input -->
        <div class="relative w-full md:w-80">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search skills (e.g. Vue, Three.js)..."
            class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-2.5 text-xs font-mono outline-none transition-all"
          />
        </div>

        <!-- Category Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 custom-scrollbar">
          <button
            v-for="cat in skillCategories"
            :key="cat"
            @click="selectedCategory = cat"
            class="px-3 py-1.5 rounded-xl text-xs font-mono transition-all shrink-0 cursor-pointer"
            :class="selectedCategory === cat 
              ? 'bg-gold-500/20 text-gold-300 border border-gold-500/40 shadow-[0_0_12px_rgba(212,175,55,0.25)]' 
              : 'bg-slate-900/60 text-slate-400 hover:text-slate-200 border border-slate-800'"
          >
            {{ cat }}
          </button>
        </div>

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

    <!-- Loading Skeleton Grid -->
    <div v-if="isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
      <div 
        v-for="n in 8" 
        :key="n"
        class="rounded-2xl p-5 bg-slate-950/60 border border-slate-800 animate-pulse space-y-3"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <div class="w-3 h-3 rounded-full bg-slate-800"></div>
            <div class="w-24 h-4 bg-slate-800 rounded"></div>
          </div>
          <div class="w-14 h-5 bg-slate-800 rounded-full"></div>
        </div>
        <div class="w-full h-10 bg-slate-900/60 rounded-xl"></div>
        <div class="w-20 h-3 bg-slate-800 rounded"></div>
      </div>
    </div>

    <!-- Empty State -->
    <div 
      v-else-if="filteredSkills.length === 0" 
      class="py-16 px-6 rounded-2xl bg-slate-950/60 border border-slate-800/80 text-center max-w-xl mx-auto backdrop-blur-xl shadow-xl"
    >
      <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center mx-auto mb-4 shadow-[0_0_20px_rgba(245,158,11,0.2)]">
        <Cpu class="w-7 h-7" />
      </div>
      <h3 class="text-lg font-bold text-white mb-1.5">
        {{ selectedCategory === 'All' ? 'No Skills in Database' : `No Skills in "${selectedCategory}"` }}
      </h3>
      <p class="text-xs text-slate-400 font-mono mb-6 leading-relaxed">
        Your portfolio matrix currently renders zero mock skills. Click below to add your real technical stack.
      </p>
      <button 
        @click="openCreateModal" 
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(212,175,55,0.35)] cursor-pointer"
      >
        <Plus class="w-4 h-4 stroke-[3]" />
        <span>Add First Skill</span>
      </button>
    </div>

    <!-- Skills Cards Grid -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
      <div
        v-for="skill in filteredSkills"
        :key="skill.name"
        class="group relative backdrop-blur-xl bg-slate-950/60 border border-slate-800 hover:border-gold-500/50 rounded-2xl p-5 transition-all duration-300 hover:shadow-[0_0_25px_rgba(212,175,55,0.18)] flex flex-col justify-between"
      >
        <div>
          <!-- Header with Swatch & Actions -->
          <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center shrink-0 overflow-hidden shadow-inner">
                <img 
                  v-if="skill.icon && (skill.icon.startsWith('http') || skill.icon.startsWith('/') || skill.icon.includes('.'))"
                  :src="resolveAssetUrl(skill.icon)" 
                  :alt="skill.name" 
                  class="w-5 h-5 object-contain"
                  @error="(e) => e.target.style.display = 'none'"
                />
                <span 
                  v-else
                  class="w-3 h-3 rounded-full shrink-0"
                  :style="{ backgroundColor: skill.color || '#fbbf24', boxShadow: `0 0 10px ${skill.color || '#fbbf24'}` }"
                ></span>
              </div>
              <h3 class="text-sm font-bold text-white group-hover:text-gold-300 transition-colors">
                {{ skill.name }}
              </h3>
            </div>

            <!-- Level Badge -->
            <span 
              class="px-2 py-0.5 rounded-full text-[10px] font-mono border"
              :class="getLevelBadgeClass(skill.level)"
            >
              {{ skill.level || 'Expert' }}
            </span>
          </div>

          <!-- Category Pill -->
          <div class="mb-3">
            <span class="inline-block px-2 py-0.5 rounded bg-slate-900 border border-slate-800 text-slate-400 text-[10px] font-mono">
              {{ skill.category }}
            </span>
          </div>

          <!-- Description -->
          <p class="text-xs text-slate-400 leading-relaxed mb-4">
            {{ skill.description }}
          </p>
        </div>

        <!-- Card Bottom Actions -->
        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs font-mono">
          <span class="text-[10px] text-slate-500">{{ skill.color }}</span>
          
          <div class="flex items-center gap-1.5">
            <button
              @click="openEditModal(skill)"
              class="p-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-gold-300 border border-slate-800 transition-colors"
              title="Edit Skill"
            >
              <Edit3 class="w-3.5 h-3.5" />
            </button>
            <button
              @click="confirmDelete(skill)"
              class="p-1.5 rounded-lg bg-red-950/30 hover:bg-red-950/60 text-red-400 hover:text-red-200 border border-red-500/20 transition-colors"
              title="Delete Skill"
            >
              <Trash2 class="w-3.5 h-3.5" />
            </button>
          </div>
        </div>

      </div>
    </div>

    <!-- Empty State -->
    <div 
      v-if="filteredSkills.length === 0" 
      class="text-center py-16 backdrop-blur-xl bg-slate-950/40 border border-slate-800 rounded-2xl"
    >
      <Cpu class="w-12 h-12 text-slate-600 mx-auto mb-3" />
      <h3 class="text-sm font-bold text-white">No technical skills match criteria</h3>
      <p class="text-xs text-slate-500 font-mono mt-1">Try another search keyword or category tab</p>
    </div>

    </div> <!-- End of v-else Skills Matrix Container -->

    <!-- Delete Confirmation Modal -->
    <div 
      v-if="skillToDelete" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
    >
      <div class="relative w-full max-w-md bg-[#030712] border border-red-500/40 rounded-2xl p-6 shadow-[0_0_40px_rgba(239,68,68,0.25)]">
        <h3 class="text-base font-bold text-white mb-2 flex items-center gap-2">
          <AlertCircle class="w-5 h-5 text-red-400" />
          <span>Confirm Skill Deletion</span>
        </h3>
        <p class="text-xs text-slate-300 mb-6 font-sans">
          Are you sure you want to remove <strong class="text-white">{{ skillToDelete.name }}</strong> from your portfolio skills matrix?
        </p>
        <div class="flex items-center justify-end gap-3 font-mono text-xs">
          <button 
            @click="skillToDelete = null"
            class="px-4 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white"
          >
            Cancel
          </button>
          <button 
            @click="executeDelete"
            class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold"
          >
            Yes, Remove Skill
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  Cpu, 
  Plus, 
  Search, 
  Edit3, 
  Trash2, 
  X, 
  CheckCircle2, 
  AlertCircle 
} from 'lucide-vue-next'
import { adminApi } from '../../services/adminApi'
import { resolveAssetUrl } from '../../services/portfolioService'

const emit = defineEmits(['stats-updated'])

const skills = ref([])
const searchQuery = ref('')
const selectedCategory = ref('All')
const toastMessage = ref('')
const isErrorToast = ref(false)
const isLoading = ref(true)

const isModalOpen = ref(false)
const isSubmitting = ref(false)
const modalError = ref('')
const editingSkill = ref(null)
const skillToDelete = ref(null)

const selectedIconFile = ref(null)
const iconPreview = ref('')

const form = ref({
  name: '',
  category: 'Frontend',
  level: 'Expert',
  color: '#fbbf24',
  icon: '',
  description: ''
})

const skillCategories = ['All', 'Frontend', 'Styling', 'Languages', 'Backend', 'Database', 'UI/UX', 'DevOps', '3D & Motion']

const notifySync = () => {
  localStorage.setItem('portfolio_sync_trigger', Date.now().toString())
  window.dispatchEvent(new CustomEvent('portfolio-updated'))
}

const loadSkills = async () => {
  isLoading.value = true
  try {
    skills.value = await adminApi.getSkills()
  } catch (err) {
    console.warn('Failed to load skills from DB', err)
    skills.value = []
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  loadSkills()
})

const filteredSkills = computed(() => {
  return skills.value.filter(s => {
    const matchesSearch = 
      (s.name || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (s.category || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (s.description || '').toLowerCase().includes(searchQuery.value.toLowerCase())

    const matchesCategory = 
      selectedCategory.value === 'All' || 
      (s.category || '').toLowerCase().includes(selectedCategory.value.toLowerCase())

    return matchesSearch && matchesCategory
  })
})

const getLevelBadgeClass = (level) => {
  switch (level) {
    case 'Master':
      return 'border-purple-500/40 text-purple-300 bg-purple-500/10'
    case 'Expert':
      return 'border-gold-500/40 text-gold-300 bg-gold-500/10'
    case 'Advanced':
      return 'border-emerald-500/40 text-emerald-300 bg-emerald-500/10'
    default:
      return 'border-amber-500/40 text-amber-300 bg-amber-500/10'
  }
}

const handleIconUpload = (event) => {
  const file = event.target.files?.[0]
  if (file) {
    selectedIconFile.value = file
    iconPreview.value = URL.createObjectURL(file)
  }
}

const openCreateModal = () => {
  editingSkill.value = null
  selectedIconFile.value = null
  iconPreview.value = ''
  modalError.value = ''
  form.value = {
    name: '',
    category: 'Frontend',
    level: 'Expert',
    color: '#fbbf24',
    icon: '',
    description: ''
  }
  isModalOpen.value = true
}

const openEditModal = (skill) => {
  editingSkill.value = skill
  selectedIconFile.value = null
  modalError.value = ''
  iconPreview.value = resolveAssetUrl(skill.icon || skill.image || '') || skill.icon || ''
  form.value = { ...skill }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  editingSkill.value = null
  selectedIconFile.value = null
  iconPreview.value = ''
  modalError.value = ''
  isSubmitting.value = false
}

const saveSkill = async () => {
  isSubmitting.value = true
  modalError.value = ''

  try {
    const savedSkill = await adminApi.saveSkill(form.value, selectedIconFile.value, editingSkill.value?.id)

    // Re-populate reactive form state with returned database resource
    if (savedSkill && typeof savedSkill === 'object') {
      form.value = {
        ...form.value,
        ...savedSkill,
        name: savedSkill.name || form.value.name,
        category: savedSkill.category || form.value.category,
        level: savedSkill.level || savedSkill.proficiency || form.value.level,
        color: savedSkill.color || form.value.color,
        icon: savedSkill.icon || form.value.icon,
        description: savedSkill.description || form.value.description
      }
      iconPreview.value = form.value.icon
      selectedIconFile.value = null
    }

    toastMessage.value = editingSkill.value
      ? `Skill "${form.value.name}" successfully updated.`
      : `Skill "${form.value.name}" added to matrix.`
    isErrorToast.value = false

    notifySync()
    await loadSkills()
    emit('stats-updated')
    closeModal()
  } catch (err) {
    const detailedMsg = adminApi.formatApiError(err, 'Failed to save skill.')
    modalError.value = detailedMsg
    toastMessage.value = detailedMsg
    isErrorToast.value = true
  } finally {
    isSubmitting.value = false
    setTimeout(() => { toastMessage.value = '' }, 5000)
  }
}

const confirmDelete = (skill) => {
  skillToDelete.value = skill
}

const executeDelete = async () => {
  if (!skillToDelete.value) return
  const identifier = skillToDelete.value.id || skillToDelete.value.name
  try {
    await adminApi.deleteSkill(identifier)
    toastMessage.value = `Skill "${skillToDelete.value.name}" removed.`
    isErrorToast.value = false
    skillToDelete.value = null
    notifySync()
    await loadSkills()
    emit('stats-updated')
  } catch (err) {
    toastMessage.value = adminApi.formatApiError(err, 'Error deleting skill.')
    isErrorToast.value = true
    skillToDelete.value = null
  } finally {
    setTimeout(() => { toastMessage.value = '' }, 4000)
  }
}
</script>

