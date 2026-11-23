<template>
  <div class="space-y-6">
    
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-xs font-mono text-cyan-400 mb-2">
          <BookOpen class="w-3.5 h-3.5 text-cyan-400" />
          <span>Admin\ArticleController • Insights Hub</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
          Articles &amp; Insights Manager
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 font-mono mt-1">
          Publish, edit, and curate bilingual technical articles and engineering deep dives
        </p>
      </div>

      <!-- Add Article Button -->
      <button
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-400 to-blue-600 hover:from-cyan-300 hover:to-blue-500 text-slate-950 font-bold text-xs transition-all shadow-[0_0_22px_rgba(0,242,254,0.4)] cursor-pointer self-start sm:self-auto"
      >
        <Plus class="w-4 h-4 text-slate-950 stroke-[3]" />
        <span>Write New Article</span>
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

    <!-- Summary Telemetry Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
        <div>
          <span class="text-[10px] font-mono uppercase text-slate-500 block">TOTAL ARTICLES</span>
          <span class="text-2xl font-black text-white font-mono">{{ articles.length }}</span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
          <BookOpen class="w-5 h-5" />
        </div>
      </div>

      <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
        <div>
          <span class="text-[10px] font-mono uppercase text-slate-500 block">PUBLISHED LIVE</span>
          <span class="text-2xl font-black text-emerald-400 font-mono">
            {{ articles.filter(a => a.is_published !== false && a.isPublished !== false).length }}
          </span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
          <CheckCircle2 class="w-5 h-5" />
        </div>
      </div>

      <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between">
        <div>
          <span class="text-[10px] font-mono uppercase text-slate-500 block">DRAFTS &amp; QUEUE</span>
          <span class="text-2xl font-black text-amber-400 font-mono">
            {{ articles.filter(a => a.is_published === false || a.isPublished === false).length }}
          </span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
          <Clock class="w-5 h-5" />
        </div>
      </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-2xl p-4 flex flex-col sm:flex-row gap-3">
      <div class="relative flex-1">
        <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search articles by title, topic, or content..."
          class="w-full pl-10 pr-4 py-2 bg-slate-900/80 border border-slate-700/80 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500/60 font-mono"
        />
      </div>

      <select
        v-model="statusFilter"
        class="bg-slate-900/80 border border-slate-700/80 rounded-xl px-3 py-2 text-xs text-slate-300 font-mono focus:outline-none focus:border-cyan-500/60"
      >
        <option value="all">All Statuses</option>
        <option value="published">Published</option>
        <option value="draft">Drafts</option>
      </select>
    </div>

    <!-- Articles Table / Bento List -->
    <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-2xl p-6 shadow-xl">
      <div v-if="isLoading" class="py-16 text-center text-xs font-mono text-cyan-400 flex flex-col items-center gap-3">
        <div class="w-6 h-6 border-2 border-cyan-400 border-t-transparent rounded-full animate-spin"></div>
        <span>Syncing articles vault...</span>
      </div>

      <div v-else-if="filteredArticles.length === 0" class="py-16 text-center text-xs font-mono text-slate-400">
        No articles found matching your criteria. Click "Write New Article" to create your first insight.
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-left text-xs font-sans">
          <thead>
            <tr class="border-b border-slate-800 text-[11px] font-mono uppercase text-slate-400">
              <th class="pb-3 pl-2">Article / Topic</th>
              <th class="pb-3">Category</th>
              <th class="pb-3">Read Time</th>
              <th class="pb-3">Published Date</th>
              <th class="pb-3 text-center">Status</th>
              <th class="pb-3 text-right pr-2">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60">
            <tr 
              v-for="article in filteredArticles" 
              :key="article.id"
              class="hover:bg-slate-900/40 transition-colors group"
            >
              <!-- Article Title & Arabic Subtitle -->
              <td class="py-4 pl-2 max-w-xs sm:max-w-md">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 shrink-0 mt-0.5 overflow-hidden">
                    <img 
                      v-if="article.image" 
                      :src="resolveAssetUrl(article.image)" 
                      :alt="article.title" 
                      class="w-full h-full object-cover"
                      @error="(e) => e.target.style.display = 'none'"
                    />
                    <FileText v-else class="w-5 h-5" />
                  </div>
                  <div>
                    <h4 class="font-bold text-white text-sm group-hover:text-cyan-300 transition-colors line-clamp-1">
                      {{ article.title }}
                    </h4>
                    <p v-if="article.title_ar" class="text-xs text-amber-400/90 font-sans mt-0.5 line-clamp-1" dir="rtl">
                      {{ article.title_ar }}
                    </p>
                    <p class="text-[11px] text-slate-400 font-mono mt-1 line-clamp-1">
                      {{ article.excerpt || article.summary || 'No excerpt provided' }}
                    </p>
                  </div>
                </div>
              </td>

              <!-- Category -->
              <td class="py-4 whitespace-nowrap">
                <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold uppercase bg-slate-900 border border-slate-700 text-cyan-300">
                  {{ article.category || 'General' }}
                </span>
                <span v-if="article.category_ar" class="block text-[10px] text-slate-400 mt-1" dir="rtl">
                  {{ article.category_ar }}
                </span>
              </td>

              <!-- Read Time -->
              <td class="py-4 whitespace-nowrap font-mono text-slate-300 text-xs">
                {{ article.read_time || article.readTime || '5 min read' }}
              </td>

              <!-- Published Date -->
              <td class="py-4 whitespace-nowrap font-mono text-slate-400 text-xs">
                {{ formatDate(article.published_at || article.publishedAt || article.created_at) }}
              </td>

              <!-- Status Toggle -->
              <td class="py-4 whitespace-nowrap text-center">
                <button
                  @click="togglePublish(article)"
                  :disabled="isUpdatingStatus === article.id"
                  class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono font-bold uppercase border transition-all cursor-pointer"
                  :class="isArticlePublished(article)
                    ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-400 hover:bg-emerald-500/25'
                    : 'bg-amber-500/15 border-amber-500/40 text-amber-400 hover:bg-amber-500/25'"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="isArticlePublished(article) ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400'"></span>
                  <span>{{ isArticlePublished(article) ? 'Published' : 'Draft' }}</span>
                </button>
              </td>

              <!-- Actions -->
              <td class="py-4 whitespace-nowrap text-right pr-2">
                <div class="flex items-center justify-end gap-1.5">
                  <button
                    @click="openEditModal(article)"
                    class="p-2 rounded-lg bg-slate-800/80 hover:bg-cyan-500/20 text-slate-300 hover:text-cyan-300 border border-slate-700 hover:border-cyan-500/40 transition-all cursor-pointer"
                    title="Edit Article"
                  >
                    <Edit3 class="w-3.5 h-3.5" />
                  </button>
                  <button
                    @click="confirmDelete(article)"
                    class="p-2 rounded-lg bg-slate-800/80 hover:bg-red-500/20 text-slate-300 hover:text-red-400 border border-slate-700 hover:border-red-500/40 transition-all cursor-pointer"
                    title="Delete Article"
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

    <!-- Create / Edit Article Modal -->
    <div 
      v-if="isModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
    >
      <div class="fixed inset-0 bg-black/80 backdrop-blur-md" @click="closeModal"></div>

      <div class="relative w-full max-w-3xl rounded-3xl bg-[#070b16] border border-cyan-500/40 shadow-[0_0_60px_rgba(0,242,254,0.2)] overflow-hidden z-10 my-8 flex flex-col max-h-[90vh]">
        
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-800 flex items-center justify-between bg-slate-950/70">
          <div>
            <span class="text-[10px] font-mono uppercase text-cyan-400 block">// INSIGHT EDITOR</span>
            <h3 class="text-lg font-bold text-white">
              {{ isEditing ? 'Edit Article' : 'Compose New Article' }}
            </h3>
          </div>
          <button @click="closeModal" class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition-colors">
            <X class="w-4 h-4" />
          </button>
        </div>

        <!-- Language Tab Switcher -->
        <div class="px-6 pt-4 border-b border-slate-800/80 flex items-center gap-2 bg-slate-950/40">
          <button
            type="button"
            @click="activeTab = 'en'"
            class="px-4 py-2 text-xs font-mono font-bold rounded-t-xl transition-all cursor-pointer border-b-2"
            :class="activeTab === 'en' 
              ? 'text-cyan-400 border-cyan-400 bg-cyan-500/10' 
              : 'text-slate-400 border-transparent hover:text-slate-200'"
          >
            🇬🇧 English Content
          </button>
          <button
            type="button"
            @click="activeTab = 'ar'"
            class="px-4 py-2 text-xs font-mono font-bold rounded-t-xl transition-all cursor-pointer border-b-2"
            :class="activeTab === 'ar' 
              ? 'text-amber-400 border-amber-400 bg-amber-500/10' 
              : 'text-slate-400 border-transparent hover:text-slate-200'"
          >
            🇵🇸 المحتوى بالعربية
          </button>
        </div>

        <!-- Form Body -->
        <form @submit.prevent="handleSubmit" class="p-6 overflow-y-auto space-y-4 flex-1 custom-scrollbar">
          
          <!-- English Tab Fields -->
          <div v-show="activeTab === 'en'" class="space-y-4">
            <div>
              <label class="block text-[11px] font-mono text-slate-300 uppercase mb-1.5">Article Title (English) *</label>
              <input
                v-model="form.title"
                required
                type="text"
                placeholder="e.g. Next-Gen Shader Pipelines with Three.js & Vue 3"
                class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500/60 font-sans"
              />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-[11px] font-mono text-slate-300 uppercase mb-1.5">Category *</label>
                <input
                  v-model="form.category"
                  required
                  type="text"
                  placeholder="e.g. WebGL & 3D, Architecture, Vue 3"
                  class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500/60 font-mono"
                />
              </div>

              <div>
                <label class="block text-[11px] font-mono text-slate-300 uppercase mb-1.5">Read Time</label>
                <input
                  v-model="form.read_time"
                  type="text"
                  placeholder="e.g. 6 min read"
                  class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500/60 font-mono"
                />
              </div>
            </div>

            <div>
              <label class="block text-[11px] font-mono text-slate-300 uppercase mb-1.5">Short Excerpt / Summary *</label>
              <textarea
                v-model="form.excerpt"
                required
                rows="2"
                placeholder="A compelling high-level summary of the engineering insight..."
                class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500/60 font-sans"
              ></textarea>
            </div>

            <div>
              <label class="block text-[11px] font-mono text-slate-300 uppercase mb-1.5">Article Body / Content *</label>
              <textarea
                v-model="form.content"
                required
                rows="6"
                placeholder="Write full article content or technical explanation here..."
                class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500/60 font-mono custom-scrollbar"
              ></textarea>
            </div>
          </div>

          <!-- Arabic Tab Fields -->
          <div v-show="activeTab === 'ar'" class="space-y-4" dir="rtl">
            <div>
              <label class="block text-[11px] font-mono text-amber-300 uppercase mb-1.5 text-right">عنوان المقال بالعربية</label>
              <input
                v-model="form.title_ar"
                type="text"
                placeholder="مثال: بناء معمارية رسومية ثلاثية الأبعاد باستخدام Three.js و Vue 3"
                class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-amber-500/60 font-sans text-right"
              />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-[11px] font-mono text-amber-300 uppercase mb-1.5 text-right">التصنيف بالعربية</label>
                <input
                  v-model="form.category_ar"
                  type="text"
                  placeholder="مثال: رسومات ثلاثية الأبعاد، هندسة البرمجيات"
                  class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-amber-500/60 font-sans text-right"
                />
              </div>

              <div>
                <label class="block text-[11px] font-mono text-amber-300 uppercase mb-1.5 text-right">وقت القراءة (بالعربية)</label>
                <input
                  v-model="form.read_time_ar"
                  type="text"
                  placeholder="مثال: 5 دقائق قراءة"
                  class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-amber-500/60 font-sans text-right"
                />
              </div>
            </div>

            <div>
              <label class="block text-[11px] font-mono text-amber-300 uppercase mb-1.5 text-right">الموجز / المقتطف بالعربية</label>
              <textarea
                v-model="form.excerpt_ar"
                rows="2"
                placeholder="موجز شيق عن المقال والتحديات الهندسية المكتسبة..."
                class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-amber-500/60 font-sans text-right"
              ></textarea>
            </div>

            <div>
              <label class="block text-[11px] font-mono text-amber-300 uppercase mb-1.5 text-right">محتوى المقال الكامل بالعربية</label>
              <textarea
                v-model="form.content_ar"
                rows="6"
                placeholder="اكتب المحتوى الكامل للمقال هنا..."
                class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-amber-500/60 font-sans text-right custom-scrollbar"
              ></textarea>
            </div>
          </div>

          <!-- Cover Image Upload & Common Settings -->
          <div class="pt-4 border-t border-slate-800 space-y-4">
            <div>
              <label class="block text-[11px] font-mono text-slate-300 uppercase mb-1.5">Cover Image (Upload or URL)</label>
              <div class="flex items-center gap-3">
                <input
                  type="file"
                  ref="fileInputRef"
                  @change="handleImageFileChange"
                  accept="image/jpeg,image/png,image/webp,image/gif"
                  class="hidden"
                />
                <button
                  type="button"
                  @click="$refs.fileInputRef.click()"
                  class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-xs font-mono text-slate-200 transition-colors cursor-pointer flex items-center gap-2"
                >
                  <Upload class="w-3.5 h-3.5 text-cyan-400" />
                  <span>Choose Image</span>
                </button>
                <input
                  v-model="form.image"
                  type="text"
                  placeholder="Or enter image URL: https://..."
                  class="flex-1 px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500/60 font-mono"
                />
              </div>

              <!-- Preview if exists -->
              <div v-if="imagePreview || form.image" class="mt-2.5 w-32 h-20 rounded-xl overflow-hidden border border-slate-700 bg-slate-900">
                <img 
                  :src="imagePreview || resolveAssetUrl(form.image)" 
                  alt="Cover Preview" 
                  class="w-full h-full object-cover"
                  @error="(e) => e.target.style.display = 'none'"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-[11px] font-mono text-slate-300 uppercase mb-1.5">Publication Date</label>
                <input
                  v-model="form.published_at"
                  type="datetime-local"
                  class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500/60 font-mono"
                />
              </div>

              <div class="flex items-center gap-3 pt-6">
                <input
                  v-model="form.is_published"
                  id="is_published"
                  type="checkbox"
                  class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-slate-950"
                />
                <label for="is_published" class="text-xs font-mono text-slate-200 cursor-pointer">
                  Publish Immediately to Live Site
                </label>
              </div>
            </div>
          </div>

          <!-- Modal Actions Footer -->
          <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-mono text-slate-300 transition-colors cursor-pointer"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="px-5 py-2 rounded-xl bg-gradient-to-r from-cyan-400 to-blue-600 hover:from-cyan-300 hover:to-blue-500 text-slate-950 font-bold text-xs transition-all shadow-[0_0_20px_rgba(0,242,254,0.4)] cursor-pointer flex items-center gap-2"
            >
              <div v-if="isSubmitting" class="w-3.5 h-3.5 border-2 border-slate-950 border-t-transparent rounded-full animate-spin"></div>
              <span>{{ isEditing ? 'Update Article' : 'Publish Article' }}</span>
            </button>
          </div>

        </form>

      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div 
      v-if="articleToDelete"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
    >
      <div class="relative w-full max-w-md rounded-2xl bg-[#070b16] border border-red-500/40 p-6 shadow-2xl space-y-4">
        <div class="flex items-center gap-3 text-red-400">
          <AlertCircle class="w-6 h-6 shrink-0" />
          <h3 class="font-bold text-white text-base">Delete Article</h3>
        </div>
        <p class="text-xs text-slate-300 leading-relaxed font-mono">
          Are you sure you want to permanently delete <span class="text-white font-bold">"{{ articleToDelete.title }}"</span>? This action cannot be undone.
        </p>
        <div class="flex items-center justify-end gap-3 pt-2">
          <button
            @click="articleToDelete = null"
            class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-mono text-slate-300 transition-colors cursor-pointer"
          >
            Cancel
          </button>
          <button
            @click="deleteArticleConfirmed"
            :disabled="isDeleting"
            class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs transition-colors cursor-pointer flex items-center gap-2"
          >
            <div v-if="isDeleting" class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            <span>Delete Permanently</span>
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import {
  BookOpen,
  Plus,
  Search,
  CheckCircle2,
  AlertCircle,
  Clock,
  Edit3,
  Trash2,
  X,
  FileText,
  Upload
} from 'lucide-vue-next'
import { adminApi } from '../../services/adminApi'
import { resolveAssetUrl } from '../../services/portfolioService'

const emit = defineEmits(['stats-updated'])

const articles = ref([])
const isLoading = ref(true)
const isSubmitting = ref(false)
const isDeleting = ref(false)
const isUpdatingStatus = ref(null)
const searchQuery = ref('')
const statusFilter = ref('all')
const activeTab = ref('en')

const toastMessage = ref('')
const isErrorToast = ref(false)

const isModalOpen = ref(false)
const isEditing = ref(false)
const currentArticleId = ref(null)
const articleToDelete = ref(null)
const selectedFile = ref(null)
const imagePreview = ref('')

const form = reactive({
  title: '',
  category: 'WebGL & Architecture',
  read_time: '5 min read',
  excerpt: '',
  content: '',
  title_ar: '',
  category_ar: '',
  read_time_ar: '',
  excerpt_ar: '',
  content_ar: '',
  image: '',
  published_at: '',
  is_published: true
})

const showToast = (message, isError = false) => {
  toastMessage.value = message
  isErrorToast.value = isError
  setTimeout(() => {
    toastMessage.value = ''
  }, 4500)
}

const fetchArticles = async () => {
  isLoading.value = true
  try {
    const list = await adminApi.getArticles()
    articles.value = Array.isArray(list) ? list : []
  } catch (err) {
    showToast('Failed to load articles from API: ' + (err.message || 'Network error'), true)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchArticles()
})

const filteredArticles = computed(() => {
  return articles.value.filter(article => {
    // Search query
    const query = searchQuery.value.toLowerCase().trim()
    const matchesSearch = !query || 
      (article.title && article.title.toLowerCase().includes(query)) ||
      (article.title_ar && article.title_ar.includes(query)) ||
      (article.category && article.category.toLowerCase().includes(query)) ||
      (article.excerpt && article.excerpt.toLowerCase().includes(query))

    // Status filter
    const isPub = isArticlePublished(article)
    const matchesStatus = statusFilter.value === 'all' ||
      (statusFilter.value === 'published' && isPub) ||
      (statusFilter.value === 'draft' && !isPub)

    return matchesSearch && matchesStatus
  })
})

const isArticlePublished = (article) => {
  return article.is_published === true || article.isPublished === true || article.status === 'published'
}

const formatDate = (dateStr) => {
  if (!dateStr) return 'Not Published'
  try {
    return new Date(dateStr).toLocaleDateString('en-GB', {
      day: 'numeric',
      month: 'short',
      year: 'numeric'
    })
  } catch {
    return dateStr
  }
}

const openCreateModal = () => {
  isEditing.value = false
  currentArticleId.value = null
  selectedFile.value = null
  imagePreview.value = ''
  activeTab.value = 'en'
  Object.assign(form, {
    title: '',
    category: 'Engineering & 3D',
    read_time: '5 min read',
    excerpt: '',
    content: '',
    title_ar: '',
    category_ar: '',
    read_time_ar: '',
    excerpt_ar: '',
    content_ar: '',
    image: '',
    published_at: new Date().toISOString().slice(0, 16),
    is_published: true
  })
  isModalOpen.value = true
}

const openEditModal = (article) => {
  isEditing.value = true
  currentArticleId.value = article.id
  selectedFile.value = null
  imagePreview.value = ''
  activeTab.value = 'en'

  let pubDate = ''
  if (article.published_at || article.publishedAt) {
    try {
      pubDate = new Date(article.published_at || article.publishedAt).toISOString().slice(0, 16)
    } catch {}
  }

  Object.assign(form, {
    title: article.title || '',
    category: article.category || 'General',
    read_time: article.read_time || article.readTime || '5 min read',
    excerpt: article.excerpt || article.summary || '',
    content: article.content || '',
    title_ar: article.title_ar || article.titleAr || '',
    category_ar: article.category_ar || article.categoryAr || '',
    read_time_ar: article.read_time_ar || '',
    excerpt_ar: article.excerpt_ar || article.excerptAr || '',
    content_ar: article.content_ar || article.contentAr || '',
    image: article.image || article.cover || '',
    published_at: pubDate,
    is_published: isArticlePublished(article)
  })
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  selectedFile.value = null
  imagePreview.value = ''
}

const handleImageFileChange = (e) => {
  const file = e.target.files?.[0]
  if (file) {
    selectedFile.value = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

const handleSubmit = async () => {
  isSubmitting.value = true
  try {
    const payload = new FormData()
    payload.append('title', form.title)
    payload.append('category', form.category)
    payload.append('read_time', form.read_time)
    payload.append('excerpt', form.excerpt)
    payload.append('content', form.content)
    payload.append('is_published', form.is_published ? '1' : '0')

    if (form.published_at) payload.append('published_at', form.published_at)
    if (form.title_ar) payload.append('title_ar', form.title_ar)
    if (form.category_ar) payload.append('category_ar', form.category_ar)
    if (form.excerpt_ar) payload.append('excerpt_ar', form.excerpt_ar)
    if (form.content_ar) payload.append('content_ar', form.content_ar)

    if (selectedFile.value) {
      payload.append('image', selectedFile.value)
    } else if (form.image) {
      payload.append('image', form.image)
    }

    if (isEditing.value) {
      await adminApi.updateArticle(currentArticleId.value, payload)
      showToast('Article updated successfully!')
    } else {
      await adminApi.createArticle(payload)
      showToast('New article published successfully!')
    }

    closeModal()
    await fetchArticles()
    emit('stats-updated')
  } catch (err) {
    showToast('Failed to save article: ' + (err.response?.data?.message || err.message), true)
  } finally {
    isSubmitting.value = false
  }
}

const togglePublish = async (article) => {
  isUpdatingStatus.value = article.id
  const nextStatus = !isArticlePublished(article)
  try {
    await adminApi.toggleArticlePublish(article.id, nextStatus)
    article.is_published = nextStatus
    article.isPublished = nextStatus
    showToast(`Article status changed to ${nextStatus ? 'Published' : 'Draft'}`)
    emit('stats-updated')
  } catch (err) {
    showToast('Failed to update article status: ' + err.message, true)
  } finally {
    isUpdatingStatus.value = null
  }
}

const confirmDelete = (article) => {
  articleToDelete.value = article
}

const deleteArticleConfirmed = async () => {
  if (!articleToDelete.value) return
  isDeleting.value = true
  try {
    await adminApi.deleteArticle(articleToDelete.value.id)
    showToast('Article deleted successfully')
    articleToDelete.value = null
    await fetchArticles()
    emit('stats-updated')
  } catch (err) {
    showToast('Failed to delete article: ' + err.message, true)
  } finally {
    isDeleting.value = false
  }
}
</script>
