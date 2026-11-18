<template>
  <section id="skills" class="skills-section relative py-24 sm:py-32">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
      
      <!-- Section Header -->
      <div class="section-header flex flex-col md:flex-row md:items-end justify-between mb-12 sm:mb-16">
        <div>
          <div class="section-badge inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] text-amber-400 font-mono text-xs tracking-wider uppercase mb-3 shadow-[0_0_20px_rgba(255,85,0,0.15)]">
            <span class="w-2 h-2 rounded-full bg-[#ff5500] animate-pulse"></span>
            <span>{{ t.skillsSubtitle }}</span>
          </div>
          <h2 class="section-title text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white">
            {{ t.skillsTitle }} <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#ff5500] via-amber-400 to-[#00f2fe]">{{ t.skillsTitleHighlight }}</span>
          </h2>
        </div>
        <p class="mt-4 md:mt-0 text-slate-400 max-w-md text-sm sm:text-base leading-relaxed">
          Ranked glassmorphic capsules of technologies powering WebGL 3D immersion, ultra-fast architectures, and fluid dark-luxury interfaces.
        </p>
      </div>

      <!-- Category Filter Tabs with Smooth Active Pill -->
      <div class="flex flex-wrap items-center gap-2 mb-12 p-1.5 rounded-2xl backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] w-fit">
        <button
          v-for="cat in categories"
          :key="cat"
          @click="activeCategory = cat"
          class="relative px-4 py-2 rounded-xl text-xs font-mono font-bold tracking-wider transition-all duration-300 cursor-pointer"
          :class="[
            activeCategory === cat
              ? 'bg-gradient-to-r from-[#ff5500] to-[#f59e0b] text-white shadow-[0_0_20px_rgba(255,85,0,0.45)] scale-105'
              : 'text-slate-400 hover:text-white hover:bg-white/[0.04]'
          ]"
        >
          {{ cat }}
        </button>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="isLoading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
        <div 
          v-for="n in 12" 
          :key="n"
          class="h-[62px] p-3 rounded-2xl bg-white/[0.03] border border-white/[0.07] animate-pulse flex items-center gap-3"
        >
          <div class="w-9 h-9 rounded-xl bg-slate-800/70 shrink-0"></div>
          <div class="flex-1 space-y-1.5">
            <div class="w-16 h-3 bg-slate-800/80 rounded"></div>
            <div class="w-24 h-2 bg-slate-800/50 rounded"></div>
          </div>
        </div>
      </div>

      <!-- Empty State (No Skills in Database) -->
      <div 
        v-else-if="filteredSkills.length === 0" 
        class="py-16 px-6 rounded-3xl backdrop-blur-xl bg-white/[0.03] border border-white/[0.08] text-center max-w-2xl mx-auto shadow-2xl"
      >
        <div class="w-16 h-16 rounded-2xl bg-[#ff5500]/10 border border-[#ff5500]/30 text-amber-400 flex items-center justify-center mx-auto mb-4 shadow-[0_0_25px_rgba(255,85,0,0.2)]">
          <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">
          {{ activeCategory === 'All' ? 'Skills Matrix Empty' : `No Skills in "${activeCategory}"` }}
        </h3>
        <p class="text-xs sm:text-sm text-slate-400 font-mono mb-6 leading-relaxed">
          Dynamic skills will render strictly from your database once saved via the Admin Skills Matrix.
        </p>
        <router-link 
          to="/admin/skills" 
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#ff5500] to-amber-500 text-white font-bold text-xs shadow-[0_0_20px_rgba(255,85,0,0.35)] hover:scale-105 transition-transform"
        >
          <span>Open Skills Matrix</span>
          <span>→</span>
        </router-link>
      </div>

      <!-- Ultra-Compact Cyber-Capsules (5-to-6 columns, ~62px height) -->
      <TransitionGroup
        v-else
        name="skill-flip"
        tag="div"
        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3"
      >
        <div
          v-for="(skill, idx) in filteredSkills"
          :key="skill.id || skill.name || idx"
          class="skill-card skill-capsule group relative overflow-hidden rounded-2xl bg-[#0b0e17]/90 border border-white/[0.07] hover:border-amber-500/50 px-3.5 py-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_28px_-8px_rgba(245,158,11,0.22)] cursor-pointer select-none"
        >
          <!-- Ambient Hover Glow -->
          <div class="pointer-events-none absolute -right-6 -top-6 h-16 w-16 rounded-full bg-amber-500/10 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

          <!-- Tiny Index Badge (#01, #02, ...) -->
          <span class="absolute top-2 right-2.5 font-mono text-[10px] text-white/20 group-hover:text-amber-400/70 transition-colors">
            #{{ String(idx + 1).padStart(2, '0') }}
          </span>

          <div class="flex items-center gap-3">
            <!-- Compact 38x38 Icon Box -->
            <div 
              class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/[0.04] border border-white/[0.08] group-hover:scale-110 group-hover:border-amber-500/40 transition-all duration-300"
              :style="{
                boxShadow: `0 4px 12px rgba(0,0,0,0.5), 0 0 10px ${skill.color ? skill.color + '25' : 'rgba(255,85,0,0.15)'}`
              }"
            >
              <div class="w-5 h-5 flex items-center justify-center filter drop-shadow-md">
                  <!-- 1. Custom Uploaded Image or Absolute/Storage URL -->
                  <img 
                    v-if="isImageUrl(skill.icon || skill.image) && !failedImages[skill.name]" 
                    :src="skill.icon || skill.image" 
                    :alt="skill.name" 
                    class="h-5 w-5 object-contain filter drop-shadow"
                    @error="failedImages[skill.name] = true"
                  />

                  <!-- Postman Official Icon -->
                  <svg v-else-if="matchesTech(skill, 'postman')" viewBox="0 0 256 256" class="w-5 h-5">
                    <circle cx="128" cy="128" r="128" fill="#FF6C37"/>
                    <path fill="#FFFFFF" d="M185.3 103.7c-2.8-11.4-12.7-19.9-24.8-19.9-1.9 0-3.7.2-5.5.6-7.8-10.4-20.2-16.7-33.6-16.7-22.3 0-40.8 16.7-43 38.6-11.6 3.1-20.1 13.5-20.1 25.9 0 14.8 12.1 26.9 26.9 26.9h95.2c16 0 29-13 29-29 0-12.6-8.1-23.4-19.3-27.2l-4.8.8z"/>
                    <path fill="#FF6C37" d="M125.7 132.8l-15.4-15.4 6.7-6.7 8.7 8.7 18.7-18.7 6.7 6.7z"/>
                  </svg>

                  <!-- React Icon -->
                  <svg v-else-if="matchesTech(skill, 'react')" viewBox="-11.5 -10.23174 23 20.46348" class="w-5 h-5">
                    <circle cx="0" cy="0" r="2.05" fill="#61dafb"/>
                    <g stroke="#61dafb" stroke-width="1" fill="none">
                      <ellipse rx="11" ry="4.2"/>
                      <ellipse rx="11" ry="4.2" transform="rotate(60)"/>
                      <ellipse rx="11" ry="4.2" transform="rotate(120)"/>
                    </g>
                  </svg>

                  <!-- TypeScript Icon -->
                  <svg v-else-if="matchesTech(skill, 'typescript') || matchesTech(skill, 'ts')" viewBox="0 0 64 64" class="w-5 h-5">
                    <rect width="64" height="64" rx="10" fill="#3178C6"/>
                    <path d="M12 28h18v6h-6v20h-6V34h-6v-6zm23.5 14c1.5 2 3.5 3 6.5 3 3 0 5-1.5 5-3.5 0-2-1.5-3-4.5-4l-2-1c-5-2-7.5-4.5-7.5-8.5 0-4.5 3.5-8 9-8 4.5 0 7.5 1.5 9.5 5l-4.5 3.5c-1-2-2.5-3-5-3-2.5 0-4 1-4 2.5 0 1.5 1 2.5 3.5 3.5l2 1c6 2 8.5 4.5 8.5 9 0 5-4 8.5-10.5 8.5-6 0-9.5-2.5-11.5-6.5l5.5-3.5z" fill="#FFFFFF"/>
                  </svg>

                  <!-- Docker Icon -->
                  <svg v-else-if="matchesTech(skill, 'docker')" viewBox="0 0 24 24" class="w-5 h-5" fill="#2496ED">
                    <path d="M13.983 11.078h2.119a.186.186 0 00.186-.185V9.006a.186.186 0 00-.186-.186h-2.119a.185.185 0 00-.185.185v1.888c0 .102.083.185.185.185m-2.954-5.43h2.118a.186.186 0 00.186-.186V3.574a.186.186 0 00-.186-.185h-2.118a.185.185 0 00-.185.185v1.888c0 .102.082.185.185.185m0 2.716h2.118a.187.187 0 00.186-.186V6.29a.186.186 0 00-.186-.185h-2.118a.185.185 0 00-.185.185v1.887c0 .102.082.186.185.186m-2.93 0h2.12a.186.186 0 00.184-.186V6.29a.185.185 0 00-.185-.185H8.1a.185.185 0 00-.185.185v1.887c0 .102.083.186.185.186m-2.964 0h2.119a.186.186 0 00.185-.186V6.29a.185.185 0 00-.185-.185H5.136a.186.186 0 00-.186.185v1.887c0 .102.084.186.186.186m5.893 2.715h2.118a.186.186 0 00.186-.185V9.006a.186.186 0 00-.186-.186h-2.118a.185.185 0 00-.185.185v1.888c0 .102.082.185.185.185m-2.93 0h2.12a.185.185 0 00.184-.185V9.006a.185.185 0 00-.184-.186H8.1a.185.185 0 00-.185.185v1.888c0 .102.083.185.185.185m-2.964 0h2.119a.185.185 0 00.185-.185V9.006a.185.185 0 00-.185-.186H5.136a.186.186 0 00-.186.185v1.888c0 .102.084.185.186.185m-2.928 0h2.119a.185.185 0 00.185-.185V9.006a.185.185 0 00-.185-.186H2.208a.185.185 0 00-.185.185v1.888c0 .102.082.185.185.185M23.75 9.893c-.613-.418-1.603-.42-2.31-.072l-.337.165s-.383-.69-1.29-.69c-.438 0-.916.142-1.393.42l-.46-.388a.723.723 0 00-.518-.182l-.47.014c.068-.675-.12-1.378-.564-1.93-1.002-1.248-2.883-1.428-4.131-.427l-.46.368a.185.185 0 00-.06.208l.196.6c.038.118.156.19.278.17l.452-.075c.813-.135 1.57.172 1.895.772.332.613.123 1.348-.52 1.836l-.372.282a.186.186 0 00-.056.206l.162.493c.036.11.144.182.26.173.08-.007.808-.094 1.637.284.978.446 1.547 1.353 1.521 2.428-.052 2.148-1.507 3.864-4.325 5.1-4.08 1.79-10.155 1.79-14.234 0-1.89-.83-3.23-1.92-3.873-3.15C.28 17.5.54 16.32 1.258 15.39c.818-1.06 2.155-1.74 3.765-1.914a.185.185 0 00.165-.185V11.45a.186.186 0 00-.186-.185H2.88a.185.185 0 00-.185.185v1.842c-1.82.21-3.328 1.01-4.225 2.25-.97 1.34-1.127 2.97-.442 4.59.837 1.97 2.81 3.48 5.706 4.368 4.75 1.455 11.83 1.455 16.58 0 3.73-1.142 5.99-3.24 6.36-5.91.24-1.72-.41-3.22-1.74-4.28"/>
                  </svg>

                  <!-- Python Icon -->
                  <svg v-else-if="matchesTech(skill, 'python')" viewBox="0 0 110 110" class="w-5 h-5">
                    <path fill="#3776AB" d="M54.5 2C32.1 2 33.5 11.6 33.5 11.6l.03 12h21.4v3H24.5s-14.4-1.6-14.4 21.2 12.6 22 12.6 22h7.6v-10.7s-.4-12.7 12.5-12.7h21.3s12.1-.2 12.1-11.8V13.8S80 2 54.5 2zm-12.6 7c2.4 0 4.4 2 4.4 4.4s-2 4.4-4.4 4.4-4.4-2-4.4-4.4 2-4.4 4.4-4.4z"/>
                    <path fill="#FFD43B" d="M55.5 108c22.4 0 21-9.6 21-9.6l-.03-12H55.07v-3h30.43s14.4 1.6 14.4-21.2-12.6-22-12.6-22h-7.6v10.7s.4 12.7-12.5 12.7H45.9s-12.1.2-12.1 11.8v20.8S30 108 55.5 108zm12.6-7c-2.4 0-4.4-2-4.4-4.4s2-4.4 4.4-4.4 4.4 2 4.4 4.4-2 4.4-4.4 4.4z"/>
                  </svg>

                  <!-- Vue Icon -->
                  <svg v-else-if="matchesTech(skill, 'vue')" viewBox="0 0 261 226" class="w-5 h-5">
                    <path d="M161.096.001l-30.225 52.351L100.647.001H-.005l130.877 226.28L261.749.001z" fill="#41B883"/>
                    <path d="M161.096.001l-30.225 52.351L100.647.001H52.346l78.526 135.792L209.398.001z" fill="#34495E"/>
                  </svg>

                  <!-- Tailwind CSS Icon -->
                  <svg v-else-if="matchesTech(skill, 'tailwind')" viewBox="0 0 48 48" class="w-5 h-5">
                    <path fill="#38bdf8" d="M24 10c-5.5 0-9 2.8-10.5 8.3 2.1-1.4 4.5-1.9 7.2-1.4 1.5.3 2.6 1.4 3.8 2.6C26.5 21.5 29.2 24 35 24c5.5 0 9-2.8 10.5-8.3-2.1 1.4-4.5 1.9-7.2 1.4-1.5-.3-2.6-1.4-3.8-2.6C32.5 12.5 29.8 10 24 10zm-11 14c-5.5 0-9 2.8-10.5 8.3 2.1-1.4 4.5-1.9 7.2-1.4 1.5.3 2.6 1.4 3.8 2.6 2 2 4.7 4.5 10.5 4.5 5.5 0 9-2.8 10.5-8.3-2.1 1.4-4.5 1.9-7.2 1.4-1.5-.3-2.6-1.4-3.8-2.6-2-2-4.7-4.5-10.5-4.5z"/>
                  </svg>

                  <!-- JavaScript Icon -->
                  <svg v-else-if="matchesTech(skill, 'javascript') || matchesTech(skill, 'js')" viewBox="0 0 64 64" class="w-5 h-5">
                    <rect width="64" height="64" rx="8" fill="#F7DF1E"/>
                    <path d="M22 47c1.5 2.5 3.5 4 7 4 3.5 0 6-2 6-6.5V26h6v18.5c0 8-5 11.5-12 11.5-6.5 0-10.5-3.5-12.5-8.5l5.5-3.5zm25-1c2 3.5 5.5 5 9.5 5 4 0 7-2 7-5 0-3-2-4.5-6-6l-2-1c-6-2.5-9-5.5-9-10.5 0-5.5 4.5-9.5 11.5-9.5 5.5 0 9.5 2 12 6.5l-5 3.5c-1.5-2.5-3.5-3.5-7-3.5-3.5 0-5.5 1.5-5.5 4 0 2.5 2 3.5 5 4.5l2 1c7 3 10 6 10 11.5 0 6.5-5 10.5-13 10.5-7.5 0-12-4-14-9.5l7-4z" fill="#000000"/>
                  </svg>

                  <!-- HTML5 Icon -->
                  <svg v-else-if="matchesTech(skill, 'html')" viewBox="0 0 512 512" class="w-5 h-5">
                    <path fill="#E34F26" d="M71,460 L30,0 481,0 440,460 255,512"/>
                    <path fill="#EF652A" d="M256,472 L405,431 440,37 256,37"/>
                    <path fill="#EBEBEB" d="M256,208 L181,208 176,150 256,150 256,94 114,94 129,264 256,264 z M256,386 L256,329 196,313 192,272 136,272 143,353 z"/>
                    <path fill="#FFFFFF" d="M255,208 L331,208 324,287 255,306 255,363 339,340 348,241 255,241 z M255,94 L397,94 392,150 255,150 z"/>
                  </svg>

                  <!-- CSS3 Icon -->
                  <svg v-else-if="matchesTech(skill, 'css')" viewBox="0 0 512 512" class="w-5 h-5">
                    <path fill="#1572B6" d="M71,460 L30,0 481,0 440,460 255,512"/>
                    <path fill="#33A9DC" d="M256,472 L405,431 440,37 256,37"/>
                    <path fill="#EBEBEB" d="M256,208 L181,208 176,150 256,150 256,94 114,94 129,264 256,264 z M256,386 L256,329 196,313 192,272 136,272 143,353 z"/>
                    <path fill="#FFFFFF" d="M255,208 L331,208 324,287 255,306 255,363 339,340 348,241 255,241 z M255,94 L397,94 392,150 255,150 z"/>
                  </svg>

                  <!-- Node.js Icon -->
                  <svg v-else-if="matchesTech(skill, 'node')" viewBox="0 0 32 32" class="w-5 h-5">
                    <path fill="#83CD29" d="M16 2.5L3.8 9.5v14l12.2 7 12.2-7v-14L16 2.5zm0 3.3l9.3 5.4v10.8L16 27.4l-9.3-5.4V11.2L16 5.8z"/>
                  </svg>

                  <!-- Express Icon -->
                  <svg v-else-if="matchesTech(skill, 'express')" viewBox="0 0 24 24" class="w-5 h-5">
                    <circle cx="12" cy="12" r="10" fill="none" stroke="#22c55e" stroke-width="2"/>
                    <text x="12" y="16" fill="#86efac" font-size="10" font-weight="bold" text-anchor="middle">EX</text>
                  </svg>

                  <!-- Laravel Icon -->
                  <svg v-else-if="matchesTech(skill, 'laravel')" viewBox="0 0 512 512" class="w-5 h-5">
                    <path fill="#FF2D20" d="M433.8 114.7L270.6 20.5c-8.9-5.1-19.8-5.1-28.7 0L78.7 114.7c-9 5.2-14.5 14.8-14.5 25.2v188.4c0 10.4 5.6 20 14.5 25.2l163.2 94.2c4.4 2.6 9.4 3.9 14.3 3.9 5 0 9.9-1.3 14.3-3.9l163.2-94.2c9-5.2 14.5-14.8 14.5-25.2V139.9c.1-10.4-5.5-20-14.4-25.2zm-177.3 365l-149-86V222l149 86v171.7zm15.7-198.8l-149-86 149-86 149 86-149 86zm149 112.8l-149 86V222l149-86v171.7z"/>
                  </svg>

                  <!-- PostgreSQL / SQL Icon -->
                  <svg v-else-if="matchesTech(skill, 'postgres') || matchesTech(skill, 'sql')" viewBox="0 0 64 64" class="w-5 h-5">
                    <path fill="#336791" d="M32 4C16.5 4 4 16.5 4 32s12.5 28 28 28 28-12.5 28-28S47.5 4 32 4zm11 37.5c-1 3.5-3 5.5-6.5 6.5-6 1.5-13.5-.5-16.5-5.5-1.5-2.5-2-6-2-9.5 0-8 4.5-15 12-17.5 7.5-2.5 16 1.5 18 9 1 3.5.5 8-1 12.5-1 2.5-2.5 4-4 4.5z"/>
                    <path fill="#ffffff" d="M30 25c-4 0-7 3-7 7s3 7 7 7 7-3 7-7-3-7-7-7z"/>
                  </svg>

                  <!-- Figma Icon -->
                  <svg v-else-if="matchesTech(skill, 'figma')" viewBox="0 0 38 57" class="w-5 h-5">
                    <path fill="#1abcfe" d="M19 28.5a9.5 9.5 0 1 1 19 0 9.5 9.5 0 0 1-19 0z"/>
                    <path fill="#0acf83" d="M0 47.5A9.5 9.5 0 0 1 9.5 38H19v9.5a9.5 9.5 0 1 1-19 0z"/>
                    <path fill="#ff7262" d="M19 0v19h9.5a9.5 9.5 0 1 0 0-19H19z"/>
                    <path fill="#f24e1e" d="M0 9.5A9.5 9.5 0 0 0 9.5 19H19V0H9.5A9.5 9.5 0 0 0 0 9.5z"/>
                    <path fill="#a259ff" d="M0 28.5A9.5 9.5 0 0 0 9.5 38H19V19H9.5A9.5 9.5 0 0 0 0 28.5z"/>
                  </svg>

                  <!-- Git Icon -->
                  <svg v-else-if="matchesTech(skill, 'git')" viewBox="0 0 64 64" class="w-5 h-5">
                    <path fill="#F05032" d="M60.8 28.5L35.5 3.2c-1.6-1.6-4.2-1.6-5.8 0l-5.8 5.8 7.3 7.3c1.7-.6 3.8-.1 5.1 1.2 1.3 1.3 1.8 3.3 1.2 5.1l7.1 7.1c1.7-.6 3.8-.1 5.1 1.2 1.9 1.9 1.9 5.1 0 7-1.9 1.9-5.1 1.9-7 0-1.4-1.4-1.8-3.4-1.2-5.2l-6.6-6.6v17.2c.5.3 1 .6 1.4 1.1 1.9 1.9 1.9 5.1 0 7-1.9 1.9-5.1 1.9-7 0-1.9-1.9-1.9-5.1 0-7 .6-.6 1.3-1 2.1-1.3v-17.5c-.8-.3-1.5-.7-2.1-1.3-1.4-1.4-1.8-3.4-1.2-5.2l-7.2-7.2L3.2 28.5c-1.6 1.6-1.6 4.2 0 5.8l25.3 25.3c1.6 1.6 4.2 1.6 5.8 0l26.5-25.3c1.6-1.6 1.6-4.2 0-5.8z"/>
                  </svg>

                  <!-- Three.js Icon -->
                  <svg v-else-if="matchesTech(skill, 'three') || matchesTech(skill, 'webgl')" viewBox="0 0 24 24" class="w-5 h-5">
                    <polygon points="12,2 22,8.5 22,17 12,22 2,17 2,8.5" fill="none" stroke="#ffd700" stroke-width="2"/>
                    <line x1="12" y1="2" x2="12" y2="22" stroke="#ffd700" stroke-width="1.5"/>
                    <line x1="2" y1="8.5" x2="22" y2="17" stroke="#ffd700" stroke-width="1.5"/>
                    <line x1="22" y1="8.5" x2="2" y2="17" stroke="#ffd700" stroke-width="1.5"/>
                  </svg>

              <!-- Stylized 2-Letter Code Fallback -->
              <span v-else class="font-mono text-xs font-bold text-amber-400">
                {{ skill.code || (skill.name || 'SK').slice(0, 2).toUpperCase() }}
              </span>
            </div>
          </div>

          <!-- Skill Name & Meta Row -->
          <div class="min-w-0 flex-1 pr-3">
            <h4 class="truncate text-xs sm:text-[13px] font-bold text-white group-hover:text-amber-300 transition-colors">
              {{ getLocalized(skill, 'name', skill.name) }}
            </h4>
            <div class="mt-0.5 flex items-center justify-between">
              <span class="truncate font-mono text-[10px] text-slate-400">
                {{ getLocalized(skill, 'category', skill.category || 'Core Tech') }}
              </span>
              <span class="font-mono text-[10px] font-semibold text-cyan-400">
                {{ getProficiencyPercent(skill.level || skill.proficiency) }}%
              </span>
            </div>
          </div>
        </div>

        <!-- Flush 2px Glowing Progress Line on Bottom Border -->
        <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-white/[0.04]">
          <div 
            class="h-full bg-gradient-to-r from-amber-500 via-orange-500 to-cyan-400 transition-all duration-700"
            :style="{ width: `${getProficiencyPercent(skill.level || skill.proficiency)}%` }"
          ></div>
        </div>
      </div>
    </TransitionGroup>

      <!-- ============================================================== -->
      <!-- DUAL-DIRECTION 3D INFINITE MARQUEE (Below Cyber-Capsules Grid) -->
      <!-- ============================================================== -->
      <div 
        class="skills-marquee-stage relative mt-10 pt-8 border-t border-white/[0.06] overflow-hidden select-none space-y-3.5"
        style="perspective: 1000px;"
      >
        <!-- Row 1: Moves Left (28s linear infinite) — Amber Accented 3D Pills -->
        <div class="marquee-row-wrapper overflow-hidden py-1">
          <div class="marquee-track marquee-left flex items-center gap-3.5 w-max">
            <div
              v-for="(skill, mIdx) in marqueeSkills"
              :key="`m1-${skill.id || skill.name}-${mIdx}`"
              class="marquee-3d-pill group/mpill flex items-center gap-2.5 px-4 py-2 rounded-full bg-[#0b0e17]/90 border border-white/[0.08] hover:border-amber-500/50 shadow-[0_8px_20px_rgba(0,0,0,0.45)] transition-all duration-300 hover:scale-105 cursor-pointer"
            >
              <!-- Glowing Amber Indicator Dot -->
              <span class="w-2 h-2 rounded-full bg-amber-400 shadow-[0_0_10px_#f59e0b] shrink-0"></span>

              <!-- Mini Skill Icon / Code Badge -->
              <span class="px-1.5 py-0.5 rounded-md bg-white/[0.05] border border-white/[0.08] font-mono text-[10px] font-bold text-amber-300">
                {{ (skill.name || 'SK').slice(0, 2).toUpperCase() }}
              </span>

              <!-- Skill Name -->
              <span class="text-xs font-bold text-white tracking-wide whitespace-nowrap group-hover/mpill:text-amber-300 transition-colors">
                {{ getLocalized(skill, 'name', skill.name) }}
              </span>

              <!-- Level% Chip -->
              <span class="px-2 py-0.5 rounded-full bg-amber-500/15 border border-amber-500/30 font-mono text-[10px] font-bold text-amber-300">
                {{ getProficiencyPercent(skill.level || skill.proficiency) }}%
              </span>
            </div>
          </div>
        </div>

        <!-- Row 2: Moves Right / Reverse (32s linear infinite) — Cyan Accented Cyber Pills -->
        <div class="marquee-row-wrapper overflow-hidden py-1">
          <div class="marquee-track marquee-right flex items-center gap-3.5 w-max">
            <div
              v-for="(skill, mIdx) in marqueeSkills"
              :key="`m2-${skill.id || skill.name}-${mIdx}`"
              class="marquee-3d-pill-reverse group/mpill2 flex items-center gap-2.5 px-4 py-2 rounded-full bg-[#090d16]/90 border border-[#00f2fe]/20 hover:border-[#00f2fe]/60 shadow-[0_8px_20px_rgba(0,0,0,0.45)] transition-all duration-300 hover:scale-105 cursor-pointer"
            >
              <!-- Category Tag -->
              <span class="font-mono text-[10px] text-[#00f2fe] tracking-wider uppercase whitespace-nowrap">
                // {{ getLocalized(skill, 'category', skill.category || 'CORE TECH') }}
              </span>

              <!-- Skill Name -->
              <span class="text-xs font-bold text-slate-200 whitespace-nowrap group-hover/mpill2:text-white transition-colors">
                {{ getLocalized(skill, 'name', skill.name) }}
              </span>

              <!-- [60FPS READY] Telemetry Badge -->
              <span class="px-2 py-0.5 rounded-md bg-[#00f2fe]/10 border border-[#00f2fe]/30 font-mono text-[9px] font-semibold text-[#00f2fe] whitespace-nowrap">
                [60FPS READY]
              </span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { skills as defaultSkills } from '../data/portfolioData'
import { lang, isRtl, t, getLocalized } from '@/services/siteLanguage'

const props = defineProps({
  skills: {
    type: Array,
    default: () => []
  },
  isLoading: {
    type: Boolean,
    default: false
  }
})

// Dynamic skills from Database with rich fallback
const allSkills = computed(() => {
  return Array.isArray(props.skills) && props.skills.length > 0 ? props.skills : defaultSkills
})

// 4x repeated array for 100% seamless infinite dual-direction marquee
const marqueeSkills = computed(() => {
  const list = allSkills.value.length > 0 ? allSkills.value : defaultSkills
  return [...list, ...list, ...list, ...list]
})

const activeCategory = ref('All')

const categories = computed(() => {
  const set = new Set(['All'])
  allSkills.value.forEach(s => {
    if (s.category) set.add(s.category)
  })
  return Array.from(set)
})

const filteredSkills = computed(() => {
  const current = (activeCategory.value || '').toLowerCase()
  if (current === 'all' || !current) return allSkills.value
  return allSkills.value.filter(s => (s.category || '').toLowerCase() === current)
})

const getProficiencyPercent = (level) => {
  if (typeof level === 'number') return Math.min(100, Math.max(0, level))
  const parsed = parseInt(level, 10)
  if (!isNaN(parsed) && parsed > 0) return Math.min(100, Math.max(0, parsed))
  const l = (level || '').toString().toLowerCase()
  if (l === 'master') return 98
  if (l === 'expert') return 94
  if (l === 'advanced') return 88
  if (l === 'proficient') return 82
  return 92
}

// Reactive cache to record image load errors and trigger vector fallback
const failedImages = reactive({})

const isImageUrl = (val) => {
  if (!val || typeof val !== 'string') return false
  return (
    val.startsWith('http://') || 
    val.startsWith('https://') || 
    val.startsWith('data:') || 
    val.startsWith('blob:') || 
    val.startsWith('/') || 
    /\.(png|jpg|jpeg|svg|webp|gif|ico)(\?.*)?$/i.test(val)
  )
}

const matchesTech = (skill, keyword) => {
  if (!skill) return false
  const icon = (skill.icon || '').toLowerCase()
  const name = (skill.name || '').toLowerCase()
  return icon === keyword || icon.includes(keyword) || name.includes(keyword)
}

// 3D Extruded Isometric Tilt & Spotlight Handlers
const skillTransforms = reactive({})

const handleSkillMouseMove = (e, name) => {
  const card = e.currentTarget
  const rect = card.getBoundingClientRect()
  const x = e.clientX - rect.left
  const y = e.clientY - rect.top

  const centerX = rect.width / 2
  const centerY = rect.height / 2

  const maxTilt = 9
  const rotX = ((y - centerY) / centerY) * -maxTilt
  const rotY = ((x - centerX) / centerX) * maxTilt

  skillTransforms[name] = {
    tiltStyle: {
      transform: `perspective(800px) rotateX(${rotX.toFixed(2)}deg) rotateY(${rotY.toFixed(2)}deg) translate3d(0, -4px, 8px)`,
      boxShadow: '0 22px 45px -12px rgba(0,0,0,0.85)',
      transition: 'transform 80ms ease-out',
      willChange: 'transform'
    },
    spotlightStyle: {
      opacity: 1,
      background: `radial-gradient(280px circle at ${x}px ${y}px, rgba(255, 85, 0, 0.16), transparent 75%)`
    }
  }
}

const handleSkillMouseLeave = (name) => {
  skillTransforms[name] = {
    tiltStyle: {
      transform: 'perspective(800px) rotateX(0deg) rotateY(0deg) translate3d(0, 0, 0)',
      boxShadow: 'none',
      transition: 'transform 450ms cubic-bezier(0.16, 1, 0.3, 1)'
    },
    spotlightStyle: {
      opacity: 0
    }
  }
}
</script>

<style scoped>
.skill-capsule {
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
}
.skill-flip-move,
.skill-flip-enter-active,
.skill-flip-leave-active {
  transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.skill-flip-enter-from,
.skill-flip-leave-to {
  opacity: 0;
  transform: translateY(12px) scale(0.96);
}
.skill-flip-leave-active {
  position: absolute;
}

/* Dual-Direction 3D Infinite Marquee */
.skills-marquee-stage {
  mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
  -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
}

.marquee-track {
  transform-style: preserve-3d;
  will-change: transform;
}

.marquee-left {
  animation: marqueeScrollLeft 28s linear infinite;
}

.marquee-right {
  animation: marqueeScrollRight 32s linear infinite;
}

.skills-marquee-stage:hover .marquee-left,
.skills-marquee-stage:hover .marquee-right {
  animation-play-state: paused;
}

.marquee-3d-pill {
  transform: rotateX(8deg) rotateY(-4deg);
  backdrop-filter: blur(12px);
}

.marquee-3d-pill-reverse {
  transform: rotateX(-6deg) rotateY(4deg);
  backdrop-filter: blur(12px);
}

@keyframes marqueeScrollLeft {
  0% {
    transform: translate3d(0, 0, 0);
  }
  100% {
    transform: translate3d(-50%, 0, 0);
  }
}

@keyframes marqueeScrollRight {
  0% {
    transform: translate3d(-50%, 0, 0);
  }
  100% {
    transform: translate3d(0, 0, 0);
  }
}
</style>
