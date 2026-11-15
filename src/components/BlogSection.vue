<template>
  <section id="blog" class="blog-section relative py-24 sm:py-32">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
      
      <!-- Section Header -->
      <div class="section-header flex flex-col md:flex-row md:items-end justify-between mb-12 sm:mb-16">
        <div>
          <div class="section-badge inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] text-[#00f2fe] font-mono text-xs tracking-wider uppercase mb-3 shadow-[0_0_20px_rgba(0,242,254,0.15)]">
            <span class="w-2 h-2 rounded-full bg-[#00f2fe] animate-pulse"></span>
            <span>// ENGINEERING LOG &amp; INSIGHTS</span>
          </div>
          <h2 class="section-title text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
            Latest <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#ff5500] via-amber-400 to-[#00f2fe]">Articles</span>
          </h2>
        </div>
        <p class="mt-4 md:mt-0 text-slate-400 max-w-md text-sm sm:text-base leading-relaxed">
          Explorations in high-performance WebGL rendering, architectural patterns in Vue 3, and futuristic dark-luxury UI systems.
        </p>
      </div>

      <!-- Multi-Panel Glass Articles Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
        <article
          v-for="(post, idx) in posts"
          :key="post.id"
          class="blog-card group relative rounded-3xl backdrop-blur-2xl bg-white/[0.03] border border-white/[0.08] hover:border-[#ff5500]/50 transition-all duration-500 hover:-translate-y-2 shadow-[0_20px_50px_rgba(0,0,0,0.65)] hover:shadow-[0_25px_60px_rgba(255,85,0,0.18)] flex flex-col justify-between overflow-hidden cursor-pointer"
          @click="openPost(post)"
        >
          <!-- Top Multi-Panel Visual Banner with Hover Zoom -->
          <div class="relative h-44 w-full overflow-hidden bg-[#05060b] border-b border-white/[0.08]">
            <div 
              class="w-full h-full flex items-center justify-center p-6 transition-transform duration-700 group-hover:scale-110"
              :style="{
                background: idx === 0 
                  ? 'radial-gradient(circle at 30% 30%, rgba(255,85,0,0.28), rgba(5,6,11,0.95) 75%)' 
                  : idx === 1 
                    ? 'radial-gradient(circle at 70% 30%, rgba(0,242,254,0.25), rgba(5,6,11,0.95) 75%)' 
                    : 'radial-gradient(circle at 50% 30%, rgba(124,58,237,0.28), rgba(5,6,11,0.95) 75%)'
              }"
            >
              <!-- Decorative Code / Vector Telemetry Graphic -->
              <div class="w-full p-3.5 rounded-2xl bg-[#090b14]/80 border border-white/[0.08] font-mono text-[10px] text-slate-300 shadow-inner">
                <div class="flex items-center justify-between text-slate-500 mb-1.5">
                  <span>// INSIGHT_0{{ idx + 1 }}.TS</span>
                  <span class="text-[#ff5500]">60FPS</span>
                </div>
                <div class="truncate text-cyan-300">{{ post.codeSnippet.split('\n')[0] }}</div>
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
              <p class="text-xs sm:text-sm text-slate-300/80 leading-relaxed mb-6">
                {{ post.summary }}
              </p>
            </div>

            <!-- Bottom Footer with Glowing Read-More Arrow -->
            <div class="pt-4 border-t border-white/[0.08] flex items-center justify-between">
              <span class="text-[11px] font-mono text-slate-400">
                {{ post.date }}
              </span>
              <span class="text-xs font-bold text-amber-400 group-hover:text-[#ff5500] flex items-center gap-2 transition-colors">
                <span>Read Insight</span>
                <span class="w-7 h-7 rounded-full bg-[#ff5500]/15 border border-[#ff5500]/40 flex items-center justify-center group-hover:bg-[#ff5500] group-hover:text-white group-hover:translate-x-1 transition-all shadow-[0_0_15px_rgba(255,85,0,0.3)]">
                  →
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
          Published on {{ activePost.date }} by Mohammed Alhwaiti
        </div>

        <div class="prose prose-invert text-sm text-slate-300 leading-relaxed space-y-4 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar">
          <p>{{ activePost.content1 }}</p>
          <div class="p-4 rounded-xl bg-[#05060b] border border-white/10 font-mono text-xs text-[#00f2fe] whitespace-pre-wrap">
            {{ activePost.codeSnippet }}
          </div>
          <p>{{ activePost.content2 }}</p>
        </div>

        <div class="mt-8 pt-4 border-t border-white/10 flex justify-end">
          <button 
            @click="activePost = null"
            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#ff5500] to-amber-500 text-white text-xs font-bold shadow-[0_0_20px_rgba(255,85,0,0.4)] cursor-pointer"
          >
            Close Insight
          </button>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const activePost = ref(null)

const openPost = (post) => {
  activePost.value = post
}

const posts = [
  {
    id: 1,
    tag: 'Three.js & WebGL',
    readTime: '6 min read',
    date: 'Oct 2024',
    title: 'Architecting 60fps 3D Web Apps with Three.js and Vue 3',
    summary: 'Strategies for memory lifecycle management, instanced geometry batching, and reactive camera tracking inside Vue 3 Composition API.',
    content1: 'Building high-framerate 3D experiences on the modern web demands rigorous control over WebGL render loops and garbage collection. In Vue 3, integrating Three.js requires careful unmounting of textures, geometry buffers, and animation frame IDs to prevent memory leaks during client-side navigation.',
    codeSnippet: '// Clean disposal pattern in Vue 3\nonBeforeUnmount(() => {\n  cancelAnimationFrame(rafId)\n  renderer.dispose()\n  scene.clear()\n})',
    content2: 'By leveraging ACESFilmicToneMapping and PBR roughness-metallic shading models, we achieve photorealistic metallic gold and crystal caustics without overburdening mobile GPUs.'
  },
  {
    id: 2,
    tag: 'CSS & UI/UX',
    readTime: '5 min read',
    date: 'Sep 2024',
    title: 'The Mathematics of Hyper-Realistic Glassmorphism in Cyber Interfaces',
    summary: 'How multi-layered backdrop filters, specular noise overlays, and micro-border gradients create authentic crystal depth.',
    content1: 'Glassmorphism is more than just applying a CSS blur. To make glass feel physical and tangible in dark cyber environments, you need subtle specular highlights on top edges and deep obsidian shadows underneath.',
    codeSnippet: '.glass-card {\n  background: linear-gradient(135deg, rgba(15,23,42,0.6), rgba(7,9,14,0.85));\n  backdrop-filter: blur(16px);\n  border: 1px solid rgba(255, 255, 255, 0.08);\n}',
    content2: 'When combined with dynamic cursor lighting that tracks the pointer position, the interface responds to user presence like authentic frosted crystal.'
  },
  {
    id: 3,
    tag: 'Fintech & Case Study',
    readTime: '7 min read',
    date: 'Aug 2024',
    title: 'From Concept to 1st Place: Engineering the WAFARCASH 3D Analytics Engine',
    summary: 'A technical retrospective on turning dry cash flow records into intuitive 3D volumetric graph topologies that earned top honors.',
    content1: 'Traditional financial spreadsheets fail to communicate complex cash flow dynamics at a glance. WAFARCASH replaced flat charts with a 3D volumetric topology, letting users rotate and inspect income and burn rates in physical space.',
    codeSnippet: '// Procedural volumetric graph generation\nconst height = (val / maxVal) * 2.5;\nconst pillar = new THREE.Mesh(new THREE.BoxGeometry(0.3, height, 0.3), goldMat);',
    content2: 'The result was recognized with the 1st Place Distinction for Outstanding Graduation Project, validating the intersection of 3D spatial UI with fintech intelligence.'
  }
]
</script>
