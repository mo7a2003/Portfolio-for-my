<template>
  <section id="blog" class="blog-section relative py-24 sm:py-32" :dir="isRtl ? 'rtl' : 'ltr'">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
      
      <!-- Section Header -->
      <div class="section-header flex flex-col md:flex-row md:items-end justify-between mb-12 sm:mb-16">
        <div>
          <div class="section-badge inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] text-[#00f2fe] font-mono text-xs tracking-wider uppercase mb-3 shadow-[0_0_20px_rgba(0,242,254,0.15)]">
            <span class="w-2 h-2 rounded-full bg-[#00f2fe] animate-pulse"></span>
            <span>// {{ isRtl ? 'سجل الهندسة والأفكار التقنية' : 'ENGINEERING LOG & INSIGHTS' }}</span>
          </div>
          <h2 class="section-title text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
            <template v-if="isRtl">
              أحدث <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#ff5500] via-amber-400 to-[#00f2fe]">المقالات الهندسية</span>
            </template>
            <template v-else>
              Latest <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#ff5500] via-amber-400 to-[#00f2fe]">Articles</span>
            </template>
          </h2>
        </div>
        <p class="mt-4 md:mt-0 text-slate-400 max-w-md text-sm sm:text-base leading-relaxed">
          {{ isRtl
            ? 'مقالات متخصصة في رسومات WebGL عالية الأداء، معمارية Vue 3 المتقدمة، وتصميم واجهات المستخدم المستقبلية الفاخرة.'
            : 'Explorations in high-performance WebGL rendering, architectural patterns in Vue 3, and futuristic dark-luxury UI systems.'
          }}
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading && displayPosts.length === 0" class="py-16 text-center text-xs font-mono text-cyan-400 flex flex-col items-center gap-3">
        <div class="w-6 h-6 border-2 border-cyan-400 border-t-transparent rounded-full animate-spin"></div>
        <span>{{ isRtl ? 'جاري تحميل المقالات الهندسية...' : 'Loading engineering insights...' }}</span>
      </div>

      <!-- Multi-Panel Glass Articles Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
        <article
          v-for="(post, idx) in displayPosts"
          :key="post.id"
          class="blog-card group relative rounded-3xl backdrop-blur-2xl bg-white/[0.03] border border-white/[0.08] hover:border-[#ff5500]/50 transition-all duration-500 hover:-translate-y-2 shadow-[0_20px_50px_rgba(0,0,0,0.65)] hover:shadow-[0_25px_60px_rgba(255,85,0,0.18)] flex flex-col justify-between overflow-hidden cursor-pointer"
          @click="openPost(post)"
        >
          <!-- Top Multi-Panel Visual Banner with Hover Zoom -->
          <div class="relative h-44 w-full overflow-hidden bg-[#05060b] border-b border-white/[0.08]">
            
            <!-- Real Uploaded Cover Image from DB if available -->
            <img 
              v-if="post.image" 
              :src="post.image" 
              :alt="post.title" 
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
              @error="(e) => post.image = ''"
            />

            <!-- Sleek Fallback Cyber Code / Vector Telemetry Graphic (if no image in DB) -->
            <div 
              v-else
              class="w-full h-full flex items-center justify-center p-6 transition-transform duration-700 group-hover:scale-110"
              :style="{
                background: idx % 3 === 0 
                  ? 'radial-gradient(circle at 30% 30%, rgba(255,85,0,0.28), rgba(5,6,11,0.95) 75%)' 
                  : idx % 3 === 1 
                    ? 'radial-gradient(circle at 70% 30%, rgba(0,242,254,0.25), rgba(5,6,11,0.95) 75%)' 
                    : 'radial-gradient(circle at 50% 30%, rgba(124,58,237,0.28), rgba(5,6,11,0.95) 75%)'
              }"
            >
              <div class="w-full p-3.5 rounded-2xl bg-[#090b14]/80 border border-white/[0.08] font-mono text-[10px] text-slate-300 shadow-inner">
                <div class="flex items-center justify-between text-slate-500 mb-1.5">
                  <span>// INSIGHT_0{{ idx + 1 }}.TS</span>
                  <span class="text-[#ff5500]">60FPS</span>
                </div>
                <div class="truncate text-cyan-300">
                  {{ post.codeSnippet ? post.codeSnippet.split('\n')[0] : '// High-Performance Architecture' }}
                </div>
              </div>
            </div>

            <!-- Floating Tag Pill -->
            <span class="absolute top-3.5 left-4 px-3 py-1 rounded-full text-[10px] font-mono font-bold bg-[#05060b]/90 border border-white/[0.12] text-[#00f2fe] backdrop-blur-md">
              {{ post.tag }}
            </span>
            <span class="absolute top-3.5 right-4 px-2.5 py-1 rounded-full text-[10px] font-mono bg-[#05060b]/80 text-slate-300">
              {{ post.readTime }}
            </span>
          </div>

          <!-- Post Content Panel -->
          <div class="p-6 sm:p-7 flex-1 flex flex-col justify-between">
            <div>
              <h3 class="text-lg sm:text-xl font-extrabold text-white group-hover:text-amber-300 transition-colors leading-snug mb-3">
                {{ post.title }}
              </h3>
              <p class="text-xs sm:text-sm text-slate-300/80 leading-relaxed mb-6 line-clamp-3">
                {{ post.summary }}
              </p>
            </div>

            <!-- Bottom Footer with Glowing Read-More Arrow -->
            <div class="pt-4 border-t border-white/[0.08] flex items-center justify-between">
              <span class="text-[11px] font-mono text-slate-400">
                {{ post.date }}
              </span>
              <span class="text-xs font-bold text-amber-400 group-hover:text-[#ff5500] flex items-center gap-2 transition-colors">
                <span>{{ isRtl ? 'اقرأ المقال' : 'Read Insight' }}</span>
                <span class="w-7 h-7 rounded-full bg-[#ff5500]/15 border border-[#ff5500]/40 flex items-center justify-center group-hover:bg-[#ff5500] group-hover:text-white transition-all shadow-[0_0_15px_rgba(255,85,0,0.3)]" :class="isRtl ? 'group-hover:-translate-x-1' : 'group-hover:translate-x-1'">
                  {{ isRtl ? '←' : '→' }}
                </span>
              </span>
            </div>
          </div>
        </article>
      </div>

    </div>

    <!-- Article Reading Modal Dialog -->
    <div 
      v-if="activePost"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
      :dir="isRtl ? 'rtl' : 'ltr'"
    >
      <div 
        class="fixed inset-0 bg-[#05060b]/90 backdrop-blur-xl animate-fadeIn"
        @click="activePost = null"
      ></div>

      <div class="relative w-full max-w-2xl rounded-3xl bg-[#090b14] border border-[#ff5500]/40 shadow-[0_0_60px_rgba(255,85,0,0.25)] overflow-hidden z-10 my-8 p-6 sm:p-8">
        
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
          <span class="px-3 py-1 rounded-full text-xs font-mono bg-[#ff5500]/15 text-amber-300 border border-[#ff5500]/30">
            {{ activePost.tag }} • {{ activePost.readTime }}
          </span>
          <button 
            @click="activePost = null"
            class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/15 text-slate-300 hover:text-white flex items-center justify-center cursor-pointer"
          >
            ✕
          </button>
        </div>

        <h3 class="text-2xl font-black text-white mb-4">
          {{ activePost.title }}
        </h3>

        <div class="text-xs font-mono text-slate-400 mb-6">
          {{ isRtl
            ? `نُشر بتاريخ ${activePost.date} بواسطة محمد الحويطي`
            : `Published on ${activePost.date} by Mohammed Alhwaiti`
          }}
        </div>

        <!-- Cover Image in Modal if available -->
        <div v-if="activePost.image" class="mb-6 rounded-2xl overflow-hidden border border-white/10 max-h-64">
          <img :src="activePost.image" :alt="activePost.title" class="w-full h-full object-cover" />
        </div>

        <div class="prose prose-invert text-sm text-slate-300 leading-relaxed space-y-4 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar whitespace-pre-line">
          <p v-if="activePost.summary" class="font-medium text-slate-200">
            {{ activePost.summary }}
          </p>
          <div v-if="activePost.content" class="text-slate-300">
            {{ activePost.content }}
          </div>
          <div v-if="activePost.codeSnippet" class="p-4 rounded-xl bg-[#05060b] border border-white/10 font-mono text-xs text-[#00f2fe] whitespace-pre-wrap">
            {{ activePost.codeSnippet }}
          </div>
        </div>

        <div class="mt-8 pt-4 border-t border-white/10 flex justify-end">
          <button 
            @click="activePost = null"
            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#ff5500] to-amber-500 text-white text-xs font-bold shadow-[0_0_20px_rgba(255,85,0,0.4)] cursor-pointer"
          >
            {{ isRtl ? 'إغلاق المقال' : 'Close Insight' }}
          </button>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import apiClient from '../services/apiClient'
import { resolveAssetUrl, extractArray } from '../services/portfolioService'
import { lang, isRtl } from '../services/siteLanguage'

const props = defineProps({
  articles: {
    type: Array,
    default: () => []
  }
})

const activePost = ref(null)
const apiArticles = ref([])
const isLoading = ref(true)

const openPost = (post) => {
  activePost.value = post
}

// Initial curated fallback insights (used if DB articles are empty)
const defaultPosts = [
  {
    id: 'default_1',
    tag: 'Three.js & WebGL',
    tag_ar: 'Three.js ورسومات 3D',
    readTime: '6 min read',
    readTime_ar: '6 دقائق قراءة',
    date: 'Oct 2024',
    title: 'Architecting 60fps 3D Web Apps with Three.js and Vue 3',
    title_ar: 'بناء تطبيقات ويب ثلاثية الأبعاد بسرعة 60 إطاراً في الثانية باستخدام Three.js و Vue 3',
    summary: 'Strategies for memory lifecycle management, instanced geometry batching, and reactive camera tracking inside Vue 3 Composition API.',
    summary_ar: 'استراتيجيات إدارة الذاكرة، تجميع الأشكال الهندسية، وتتبع الكاميرا التفاعلي داخل نظام Vue 3 Composition API.',
    content: 'Building high-framerate 3D experiences on the modern web demands rigorous control over WebGL render loops and garbage collection. In Vue 3, integrating Three.js requires careful unmounting of textures, geometry buffers, and animation frame IDs to prevent memory leaks during client-side navigation.\n\nBy leveraging ACESFilmicToneMapping and PBR roughness-metallic shading models, we achieve photorealistic metallic gold and crystal caustics without overburdening mobile GPUs.',
    content_ar: 'يتطلب بناء تجارب ثلاثية الأبعاد بمعدل إطارات مرتفع على الويب تحكماً دقيقاً في دورات معالجة WebGL والتخلص التلقائي من الذاكرة الزائدة. يتيح دمج Three.js مع Vue 3 إمكانية تحرير الخامات والـ buffers بدقة لمنع تسريب الذاكرة.',
    codeSnippet: '// Clean disposal pattern in Vue 3\nonBeforeUnmount(() => {\n  cancelAnimationFrame(rafId)\n  renderer.dispose()\n  scene.clear()\n})',
    image: ''
  },
  {
    id: 'default_2',
    tag: 'CSS & UI/UX',
    tag_ar: 'تصميم الواجهات UI/UX',
    readTime: '5 min read',
    readTime_ar: '5 دقائق قراءة',
    date: 'Sep 2024',
    title: 'The Mathematics of Hyper-Realistic Glassmorphism in Cyber Interfaces',
    title_ar: 'هندسة تأثير الزجاج ثلاثي الأبعاد في واجهات السايبر المستقبلية',
    summary: 'How multi-layered backdrop filters, specular noise overlays, and micro-border gradients create authentic crystal depth.',
    summary_ar: 'كيفية دمج الفلاتر الضبابية المتعددة والانعكاسات الحافة لإنتاج عمق زجاجي كريستالي حقيقي دون التأثير على الأداء.',
    content: 'Glassmorphism is more than just applying a CSS blur. To make glass feel physical and tangible in dark cyber environments, you need subtle specular highlights on top edges and deep obsidian shadows underneath.',
    content_ar: 'تأثير Glassmorphism يتجاوز مجرد إضافة خلفية ضبابية؛ بل يعتمد على دمج انعكاسات ضوئية على الحواف العلوية مع تظليل عميق أسفل البطاقات الزجاجية.',
    codeSnippet: '.glass-card {\n  background: linear-gradient(135deg, rgba(15,23,42,0.6), rgba(7,9,14,0.85));\n  backdrop-filter: blur(16px);\n  border: 1px solid rgba(255, 255, 255, 0.08);\n}',
    image: ''
  },
  {
    id: 'default_3',
    tag: 'Fintech & Case Study',
    tag_ar: 'تقنية مالية ودراسة حالة',
    readTime: '7 min read',
    readTime_ar: '7 دقائق قراءة',
    date: 'Aug 2024',
    title: 'From Concept to 1st Place: Engineering the WAFARCASH 3D Analytics Engine',
    title_ar: 'من الفكرة إلى المركز الأول: هندسة محرك التحليلات ثلاثي الأبعاد لتطبيق وفّر كاش',
    summary: 'A technical retrospective on turning dry cash flow records into intuitive 3D volumetric graph topologies that earned top honors.',
    summary_ar: 'دراسة حالة تقنية لتحويل السجلات المالية الجافة إلى رسوم بيانية مجسمة ثلاثية الأبعاد حازت على جائزة عنان لأفضل مشروع تخرج.',
    content: 'Traditional financial spreadsheets fail to communicate complex cash flow dynamics at a glance. WAFARCASH replaced flat charts with a 3D volumetric topology, letting users rotate and inspect income and burn rates in physical space.',
    content_ar: 'استبدل تطبيق وفّر كاش الجداول المسطحة بمجسمات طوبولوجية ثلاثية الأبعاد تتيح للمستخدم استكشاف التدفق المالي في الفضاء التفاعلي.',
    codeSnippet: '// Procedural volumetric graph generation\nconst height = (val / maxVal) * 2.5;\nconst pillar = new THREE.Mesh(new THREE.BoxGeometry(0.3, height, 0.3), goldMat);',
    image: ''
  }
]

const fetchArticles = async () => {
  try {
    const res = await apiClient.get('/articles')
    const list = extractArray(res, 'articles')
    if (list && list.length > 0) {
      apiArticles.value = list
    }
  } catch (e) {
    // Graceful fallback to default curated articles
  } finally {
    isLoading.value = false
  }
}

const displayPosts = computed(() => {
  const isArabic = isRtl.value
  const source = (apiArticles.value.length > 0) 
    ? apiArticles.value 
    : (props.articles.length > 0 ? props.articles : defaultPosts)

  return source.map(a => {
    let rawDate = a.published_at || a.publishedAt || a.created_at || a.date
    let formattedDate = rawDate
    if (rawDate && typeof rawDate === 'string' && rawDate.includes('-')) {
      try {
        formattedDate = new Date(rawDate).toLocaleDateString(isArabic ? 'ar-EG' : 'en-GB', {
          month: 'short',
          year: 'numeric'
        })
      } catch {}
    }

    const title = isArabic ? (a.title_ar || a.titleAr || a.title) : (a.title || a.title_ar)
    const summary = isArabic ? (a.excerpt_ar || a.excerptAr || a.summary_ar || a.excerpt || a.summary || '') : (a.excerpt || a.summary || a.excerpt_ar || '')
    const tag = isArabic ? (a.category_ar || a.categoryAr || a.tag_ar || a.category || a.tag || 'عام') : (a.category || a.tag || 'Engineering')
    const readTime = isArabic ? (a.read_time_ar || a.readTime_ar || a.read_time || a.readTime || '5 دقائق قراءة') : (a.read_time || a.readTime || '5 min read')
    const content = isArabic ? (a.content_ar || a.contentAr || a.content || '') : (a.content || a.content_ar || '')
    const rawImage = a.image || a.cover || a.cover_image || ''
    const resolvedImage = rawImage ? resolveAssetUrl(rawImage) : ''

    return {
      id: a.id,
      title,
      summary,
      tag,
      readTime,
      date: formattedDate,
      content,
      codeSnippet: a.codeSnippet || a.code_snippet || '',
      image: resolvedImage
    }
  })
})

const onPortfolioUpdated = () => {
  fetchArticles()
}

onMounted(() => {
  fetchArticles()
  window.addEventListener('portfolio-updated', onPortfolioUpdated)
})

onBeforeUnmount(() => {
  window.removeEventListener('portfolio-updated', onPortfolioUpdated)
})
</script>
