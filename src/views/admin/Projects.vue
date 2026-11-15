<template>
  <div class="space-y-6">
    
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 border border-gold-500/30 text-xs font-mono text-gold-400 mb-2">
          <FolderGit2 class="w-3.5 h-3.5" />
          <span>Admin\ProjectController Endpoints</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
          Projects Management Studio
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 font-mono mt-1">
          Full CRUD operations for 3D experiences, fintech platforms & web systems
        </p>
      </div>

      <!-- Create / Toggle Project Button -->
      <button
        v-if="!isModalOpen"
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs transition-all shadow-[0_0_22px_rgba(212,175,55,0.4)] cursor-pointer self-start sm:self-auto"
      >
        <Plus class="w-4 h-4 text-black stroke-[3]" />
        <span>Add New Project</span>
      </button>
      <button
        v-else
        @click="closeModal"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 hover:border-gold-500/40 text-slate-300 hover:text-white text-xs font-mono transition-all cursor-pointer self-start sm:self-auto"
      >
        <X class="w-4 h-4" />
        <span>Back to Projects List</span>
      </button>
    </div>

    <!-- Main Content Area: Either Studio Form OR Projects List -->
    
    <!-- ======================================================== -->
    <!-- Add / Edit Project Card Studio Container                -->
    <!-- ======================================================== -->
    <div 
      v-if="isModalOpen" 
      class="max-w-4xl mx-auto w-full my-2"
    >
      <div class="p-6 sm:p-8 md:p-10 rounded-3xl bg-slate-950/80 border border-amber-500/30 backdrop-blur-2xl shadow-[0_0_60px_rgba(212,175,55,0.15)] space-y-8">
        
        <!-- Header with Back & Cancel Button -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-800">
          <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400 shadow-[0_0_20px_rgba(212,175,55,0.2)]">
              <FolderGit2 class="w-6 h-6" />
            </div>
            <div>
              <div class="flex items-center gap-2">
                <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                  {{ editingProject ? `Edit Project: ${form.title}` : 'Add New Showcase Project' }}
                </h2>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-gold-500/10 text-gold-400 border border-gold-500/30">
                  {{ editingProject ? 'PUT/PATCH' : 'POST' }}
                </span>
              </div>
              <p class="text-xs text-slate-400 font-mono mt-1">
                Admin\ProjectController@{{ editingProject ? 'update' : 'store' }} // Multipart Form Sync
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

        <!-- Form Body -->
        <form @submit.prevent="saveProject" class="space-y-6">
          
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
          
          <!-- Section 1: Basic Information -->
          <div class="space-y-4">
            <h3 class="text-xs font-mono uppercase tracking-wider text-gold-400/90 font-bold flex items-center gap-2">
              <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
              <span>1. Basic Project Identity</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Project Title -->
              <div>
                <label class="block text-xs font-mono text-slate-300 mb-2">Project Title *</label>
                <input
                  v-model="form.title"
                  type="text"
                  required
                  placeholder="e.g. WASLA 3D"
                  class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 outline-none transition-all font-mono"
                />
              </div>

              <!-- Subtitle -->
              <div>
                <label class="block text-xs font-mono text-slate-300 mb-2">Subtitle / Tagline</label>
                <input
                  v-model="form.subtitle"
                  type="text"
                  placeholder="e.g. Multi-service platform with 3D WebGL UI/UX"
                  class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 outline-none transition-all font-mono"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Category -->
              <div>
                <label class="block text-xs font-mono text-slate-300 mb-2">Category *</label>
                <input
                  v-model="form.category"
                  type="text"
                  required
                  placeholder="e.g. 3D Experience / Fintech"
                  class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 outline-none transition-all font-mono"
                />
              </div>

              <!-- Year -->
              <div>
                <label class="block text-xs font-mono text-slate-300 mb-2">Year</label>
                <input
                  v-model="form.year"
                  type="text"
                  placeholder="2024"
                  class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 outline-none transition-all font-mono"
                />
              </div>

              <!-- Accent Color -->
              <div>
                <label class="block text-xs font-mono text-slate-300 mb-2">Accent Color Hex</label>
                <div class="flex items-center gap-2.5">
                  <input
                    v-model="form.accentColor"
                    type="text"
                    placeholder="#d4af37"
                    class="flex-1 bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white font-mono outline-none transition-all"
                  />
                  <input 
                    v-model="form.accentColor" 
                    type="color" 
                    class="w-11 h-11 rounded-xl cursor-pointer bg-slate-900 border border-slate-800 p-1" 
                  />
                </div>
              </div>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-2">Comprehensive Project Description *</label>
              <textarea
                v-model="form.description"
                rows="4"
                required
                placeholder="Describe architectural challenges, 3D WebGL implementation, client outcomes..."
                class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl p-4 text-xs text-white placeholder-slate-500 outline-none transition-all leading-relaxed"
              ></textarea>
            </div>
          </div>

          <!-- Section 2: Tech Stack & Badges -->
          <div class="space-y-4 pt-4 border-t border-slate-800/80">
            <h3 class="text-xs font-mono uppercase tracking-wider text-gold-400/90 font-bold flex items-center gap-2">
              <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
              <span>2. Tech Stack & Recognition</span>
            </h3>

            <!-- Tech Stack -->
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-2">Technologies Used (comma separated)</label>
              <input
                v-model="techStackInput"
                type="text"
                placeholder="Vue 3, Three.js, Tailwind CSS, Laravel, WebSockets"
                class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white font-mono outline-none transition-all"
              />
              <!-- Dynamic preview tags -->
              <div v-if="parsedTechTags.length > 0" class="flex flex-wrap gap-2 mt-2.5">
                <span 
                  v-for="tag in parsedTechTags" 
                  :key="tag" 
                  class="px-2.5 py-1 rounded-lg bg-gold-500/10 border border-gold-500/30 text-gold-300 text-[11px] font-mono"
                >
                  {{ tag }}
                </span>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Project Lifecycle Status (Required by Laravel API) -->
              <div>
                <label class="block text-xs font-mono text-slate-300 mb-2">Project Status *</label>
                <select
                  v-model="form.status"
                  required
                  class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white font-mono outline-none transition-all cursor-pointer"
                >
                  <option value="active">Active (Ongoing / Live)</option>
                  <option value="completed">Completed (Delivered)</option>
                  <option value="featured">Featured (Top Showcase)</option>
                  <option value="draft">Draft (Private)</option>
                </select>
              </div>

              <!-- Showcase Ribbon / Badge -->
              <div>
                <label class="block text-xs font-mono text-slate-300 mb-2">Showcase Ribbon / Badge</label>
                <input
                  v-model="form.badge"
                  type="text"
                  placeholder="e.g. 🏆 1st Place Award Winner"
                  class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white font-mono outline-none transition-all"
                />
              </div>

              <!-- Publish status toggle -->
              <div class="flex items-center">
                <label class="flex items-center gap-3 p-3.5 rounded-xl bg-slate-900/60 border border-slate-800 hover:border-gold-500/40 w-full cursor-pointer select-none transition-colors">
                  <input
                    v-model="form.isActive"
                    type="checkbox"
                    class="rounded bg-slate-950 border-slate-700 text-gold-500 focus:ring-gold-500/20 h-5 w-5 accent-gold-500 cursor-pointer"
                  />
                  <div>
                    <span class="text-xs font-bold text-white block">Publish in Showcase</span>
                    <span class="text-[11px] font-mono text-slate-400">Public Home Page</span>
                  </div>
                </label>
              </div>
            </div>
          </div>

          <!-- Section 3: Media & URLs -->
          <div class="space-y-4 pt-4 border-t border-slate-800/80">
            <h3 class="text-xs font-mono uppercase tracking-wider text-gold-400/90 font-bold flex items-center gap-2">
              <span class="w-1.5 h-1.5 rounded-full bg-gold-400"></span>
              <span>3. Media Asset & Deployment Links</span>
            </h3>

            <!-- Project Thumbnail / Image Upload Zone -->
            <div class="space-y-3">
              <label class="block text-xs font-mono text-slate-300">Project Thumbnail / 3D Asset Preview *</label>
              
              <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 space-y-4">
                <!-- Preview box if image exists -->
                <div v-if="imagePreview || form.image" class="flex items-center gap-4 p-3 rounded-xl bg-slate-950/80 border border-gold-500/30">
                  <div class="relative w-20 h-20 rounded-xl overflow-hidden border border-gold-500/40 bg-slate-900 shrink-0 shadow-[0_0_20px_rgba(212,175,55,0.25)]">
                    <img 
                      :src="imagePreview || form.image" 
                      alt="Preview" 
                      class="w-full h-full object-cover" 
                      @error="(e) => e.target.style.display = 'none'"
                    />
                    <button 
                      type="button" 
                      @click="removeSelectedImage" 
                      class="absolute top-1 right-1 p-1 rounded-full bg-red-600/90 text-white hover:bg-red-500 transition-colors cursor-pointer"
                      title="Remove image"
                    >
                      <X class="w-3 h-3" />
                    </button>
                  </div>
                  <div class="text-xs font-mono text-slate-300 space-y-1">
                    <span class="text-gold-400 font-bold block">{{ selectedFile ? selectedFile.name : (form.title || 'Selected Project Image') }}</span>
                    <span class="text-slate-400 text-[11px] block">{{ selectedFile ? `${(selectedFile.size / 1024).toFixed(1)} KB` : 'Attached to project' }}</span>
                    <span class="text-emerald-400 text-[11px]">✓ Ready for backend sync</span>
                  </div>
                </div>

                <!-- File upload input -->
                <div class="space-y-2">
                  <input 
                    type="file" 
                    @change="handleFileUpload" 
                    accept="image/*"
                    class="w-full px-4 py-3 rounded-xl bg-white/[0.03] border border-white/10 text-gray-300 text-xs file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500/20 file:text-amber-300 hover:file:bg-amber-500/30 transition-all cursor-pointer font-mono"
                  />
                  <div class="flex items-center gap-2">
                    <span class="text-[11px] font-mono text-slate-500">Or asset path:</span>
                    <input
                      v-model="form.image"
                      type="text"
                      placeholder="/projects/wasla-3d.png"
                      class="flex-1 bg-slate-950/80 border border-slate-800/80 focus:border-gold-400 rounded-xl px-3 py-1.5 text-xs text-slate-300 font-mono outline-none"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- External URLs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-xs font-mono text-slate-300 mb-2">Live Demo URL</label>
                <input
                  v-model="form.liveUrl"
                  type="url"
                  placeholder="https://wasla.demo.dev"
                  class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white font-mono outline-none transition-all"
                />
              </div>
              <div>
                <label class="block text-xs font-mono text-slate-300 mb-2">GitHub Repository URL</label>
                <input
                  v-model="form.githubUrl"
                  type="url"
                  placeholder="https://github.com/mohammed-alhwaiti/wasla"
                  class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 focus:ring-1 focus:ring-gold-400 rounded-xl px-4 py-3 text-xs text-white font-mono outline-none transition-all"
                />
              </div>
            </div>
          </div>

          <!-- Bottom Actions -->
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-slate-800">
            <button
              type="button"
              @click="closeModal"
              :disabled="isSubmitting"
              class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 hover:text-white text-xs font-mono transition-all cursor-pointer disabled:opacity-50"
            >
              Cancel & Discard
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs tracking-wide shadow-[0_0_30px_rgba(212,175,55,0.4)] transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="isSubmitting" class="w-4 h-4 rounded-full border-2 border-slate-950 border-t-transparent animate-spin"></span>
              <span>{{ isSubmitting ? (editingProject ? 'Saving Changes...' : 'Uploading & Creating...') : (editingProject ? 'Save Project Changes' : 'Create & Publish Project') }}</span>
            </button>
          </div>

        </form>

      </div>
    </div>

    <!-- ======================================================== -->
    <!-- Projects List & Filter View (Shown when not editing)     -->
    <!-- ======================================================== -->
    <div v-else class="space-y-6">

      <!-- Search & Filter Controls Bar -->
      <div class="backdrop-blur-xl bg-slate-950/60 border border-slate-800 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Search Input -->
        <div class="relative w-full md:w-80">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by title, tech or category..."
            class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-2.5 text-xs font-mono outline-none transition-all"
          />
        </div>

        <!-- Category Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 custom-scrollbar">
          <button
            v-for="cat in categories"
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

    <!-- Projects Grid Display -->
    <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div 
        v-for="n in 4" 
        :key="n"
        class="rounded-2xl p-6 bg-slate-950/60 border border-slate-800 animate-pulse space-y-4"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-800/80"></div>
            <div class="space-y-1.5">
              <div class="w-32 h-4 bg-slate-800/80 rounded"></div>
              <div class="w-20 h-3 bg-slate-800/50 rounded"></div>
            </div>
          </div>
          <div class="w-16 h-6 bg-slate-800/60 rounded-full"></div>
        </div>
        <div class="w-full h-16 bg-slate-900/60 rounded-xl"></div>
        <div class="flex gap-2">
          <div class="w-16 h-5 bg-slate-800/60 rounded-md"></div>
          <div class="w-16 h-5 bg-slate-800/60 rounded-md"></div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div 
      v-else-if="filteredProjects.length === 0" 
      class="py-16 px-6 rounded-2xl bg-slate-950/60 border border-slate-800/80 text-center max-w-xl mx-auto backdrop-blur-xl shadow-xl"
    >
      <div class="w-14 h-14 rounded-2xl bg-gold-500/10 border border-gold-500/30 text-gold-400 flex items-center justify-center mx-auto mb-4 shadow-[0_0_20px_rgba(212,175,55,0.2)]">
        <FolderGit2 class="w-7 h-7" />
      </div>
      <h3 class="text-lg font-bold text-white mb-1.5">
        {{ selectedCategory === 'All' ? 'No Projects in Database' : `No Projects in "${selectedCategory}"` }}
      </h3>
      <p class="text-xs text-slate-400 font-mono mb-6 leading-relaxed">
        Your portfolio showcases zero mock projects. Create your first project using the button below.
      </p>
      <button 
        @click="openCreateModal" 
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(212,175,55,0.35)] cursor-pointer"
      >
        <Plus class="w-4 h-4 stroke-[3]" />
        <span>Add First Project</span>
      </button>
    </div>

    <!-- Active Projects Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div
        v-for="project in filteredProjects"
        :key="project.id"
        class="group relative backdrop-blur-2xl bg-slate-950/60 border border-slate-800 hover:border-gold-500/50 rounded-2xl p-6 transition-all duration-300 hover:shadow-[0_0_35px_rgba(212,175,55,0.18)] flex flex-col justify-between"
      >
        <div>
          <!-- Card Header (Accent swatch, title, year, badge) -->
          <div class="flex items-start justify-between gap-4 mb-3">
            <div class="flex items-center gap-3">
              <div 
                class="w-10 h-10 rounded-xl flex items-center justify-center font-mono font-bold text-sm uppercase shrink-0 border"
                :style="{
                  backgroundColor: `${project.accentColor || '#d4af37'}15`,
                  borderColor: `${project.accentColor || '#d4af37'}40`,
                  color: project.accentColor || '#d4af37'
                }"
              >
                {{ project.title.slice(0, 2) }}
              </div>
              <div>
                <h3 class="text-base sm:text-lg font-bold text-white group-hover:text-gold-300 transition-colors">
                  {{ project.title }}
                </h3>
                <p class="text-xs text-slate-400 font-mono">
                  {{ project.category }} • {{ project.year }}
                </p>
              </div>
            </div>

            <!-- Status Pill -->
            <button
              @click="toggleStatus(project)"
              class="px-2.5 py-1 rounded-full text-[10px] font-mono border transition-all cursor-pointer shrink-0"
              :class="project.isActive !== false 
                ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-300' 
                : 'bg-slate-900 border-slate-700 text-slate-500'"
              title="Click to toggle showcase visibility"
            >
              {{ project.isActive !== false ? '● Live' : '○ Draft' }}
            </button>
          </div>

          <!-- Badge pill if any -->
          <div v-if="project.badge" class="mb-3">
            <span 
              class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-mono border"
              :class="project.badgeColor || 'border-gold-500/30 text-gold-400 bg-gold-500/10'"
            >
              {{ project.badge }}
            </span>
          </div>

          <!-- Description -->
          <p class="text-xs text-slate-300 leading-relaxed line-clamp-3 mb-4">
            {{ project.description }}
          </p>

          <!-- Tech stack tags -->
          <div class="flex flex-wrap gap-1.5 mb-5">
            <span
              v-for="(tech, i) in project.tech || []"
              :key="i"
              class="px-2 py-0.5 rounded-md bg-slate-900/90 border border-slate-800 text-slate-300 text-[10px] font-mono"
            >
              {{ tech }}
            </span>
          </div>
        </div>

        <!-- Card Footer Actions -->
        <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between gap-3">
          
          <!-- External Links -->
          <div class="flex items-center gap-2">
            <a
              v-if="project.liveUrl"
              :href="project.liveUrl"
              target="_blank"
              rel="noopener noreferrer"
              class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-gold-500/40 text-slate-400 hover:text-gold-300 transition-colors"
              title="View Live Demo"
            >
              <ExternalLink class="w-3.5 h-3.5" />
            </a>
            <a
              v-if="project.githubUrl"
              :href="project.githubUrl"
              target="_blank"
              rel="noopener noreferrer"
              class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-gold-500/40 text-slate-400 hover:text-gold-300 transition-colors"
              title="View Source Code"
            >
              <Code2 class="w-3.5 h-3.5" />
            </a>
          </div>

          <!-- Edit & Delete Buttons -->
          <div class="flex items-center gap-2">
            <button
              @click="openEditModal(project)"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gold-500/10 hover:bg-gold-500/20 border border-gold-500/30 text-gold-300 text-xs font-mono transition-colors cursor-pointer"
            >
              <Edit3 class="w-3.5 h-3.5" />
              <span>Edit</span>
            </button>
            <button
              @click="confirmDelete(project)"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-950/30 hover:bg-red-950/60 border border-red-500/30 text-red-400 hover:text-red-200 text-xs font-mono transition-colors cursor-pointer"
            >
              <Trash2 class="w-3.5 h-3.5" />
              <span>Delete</span>
            </button>
          </div>

        </div>

      </div>
    </div>

    </div> <!-- End of v-else Projects List Container -->

    <!-- Delete Confirmation Modal -->
    <div 
      v-if="projectToDelete" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
    >
      <div class="relative w-full max-w-md bg-[#030712] border border-red-500/40 rounded-2xl p-6 shadow-[0_0_40px_rgba(239,68,68,0.25)]">
        <h3 class="text-base font-bold text-white mb-2 flex items-center gap-2">
          <AlertCircle class="w-5 h-5 text-red-400" />
          <span>Confirm Project Deletion</span>
        </h3>
        <p class="text-xs text-slate-300 mb-6 font-sans">
          Are you sure you want to delete <strong class="text-white">{{ projectToDelete.title }}</strong>? This action will remove it from the database and public portfolio showcase.
        </p>
        <div class="flex items-center justify-end gap-3 font-mono text-xs">
          <button 
            @click="projectToDelete = null"
            class="px-4 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white"
          >
            Cancel
          </button>
          <button 
            @click="executeDelete"
            class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold shadow-[0_0_15px_rgba(239,68,68,0.4)]"
          >
            Yes, Delete Project
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  FolderGit2, 
  Plus, 
  Search, 
  ExternalLink, 
  Code2, 
  Edit3, 
  Trash2, 
  X, 
  CheckCircle2, 
  AlertCircle 
} from 'lucide-vue-next'
import { adminApi } from '../../services/adminApi'
import { resolveAssetUrl } from '../../services/portfolioService'

const emit = defineEmits(['stats-updated'])

const projects = ref([])
const searchQuery = ref('')
const selectedCategory = ref('All')
const toastMessage = ref('')
const isErrorToast = ref(false)
const isLoading = ref(true)

const isModalOpen = ref(false)
const isSubmitting = ref(false)
const modalError = ref('')
const editingProject = ref(null)
const projectToDelete = ref(null)

const selectedFile = ref(null)
const imagePreview = ref('')

const techStackInput = ref('')
const form = ref({
  title: '',
  subtitle: '',
  category: '',
  year: '2024',
  status: 'active',
  accentColor: '#d4af37',
  description: '',
  image: '',
  liveUrl: '',
  githubUrl: '',
  badge: '',
  badgeColor: '',
  isActive: true
})

const categories = ['All', '3D Experience', 'Fintech', 'Artificial Intelligence', 'Luxury E-Commerce']

const notifySync = () => {
  localStorage.setItem('portfolio_sync_trigger', Date.now().toString())
  window.dispatchEvent(new CustomEvent('portfolio-updated'))
}

const loadProjects = async () => {
  isLoading.value = true
  try {
    projects.value = await adminApi.getProjects()
  } catch (err) {
    console.warn('Failed to load projects from DB', err)
    projects.value = []
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  loadProjects()
})

const filteredProjects = computed(() => {
  return projects.value.filter(p => {
    const titleMatch = (p.title || '').toLowerCase().includes(searchQuery.value.toLowerCase())
    const catMatch = (p.category || '').toLowerCase().includes(searchQuery.value.toLowerCase())
    const techMatch = (p.tech || []).some(t => t.toLowerCase().includes(searchQuery.value.toLowerCase()))
    const matchesSearch = titleMatch || catMatch || techMatch
    
    const matchesCategory = 
      selectedCategory.value === 'All' || 
      (p.category || '').toLowerCase().includes(selectedCategory.value.toLowerCase())

    return matchesSearch && matchesCategory
  })
})

const parsedTechTags = computed(() => {
  return techStackInput.value
    .split(',')
    .map(t => t.trim())
    .filter(Boolean)
})

const handleFileUpload = (event) => {
  const file = event.target.files?.[0]
  if (file) {
    selectedFile.value = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

const removeSelectedImage = () => {
  selectedFile.value = null
  imagePreview.value = ''
  form.value.image = ''
}

const openCreateModal = () => {
  editingProject.value = null
  selectedFile.value = null
  imagePreview.value = ''
  modalError.value = ''
  techStackInput.value = 'Vue 3, Three.js, Tailwind CSS'
  form.value = {
    title: '',
    subtitle: '',
    category: 'Web Application / 3D Experience',
    year: new Date().getFullYear().toString(),
    status: 'active',
    accentColor: '#d4af37',
    description: '',
    image: '',
    liveUrl: '',
    githubUrl: '',
    badge: 'Featured Ecosystem',
    badgeColor: 'border-gold-500/40 text-gold-400 bg-gold-500/10',
    isActive: true
  }
  isModalOpen.value = true
}

const openEditModal = (project) => {
  editingProject.value = project
  selectedFile.value = null
  modalError.value = ''
  imagePreview.value = project.image || project.preview_image || project.previewImage || ''
  techStackInput.value = (project.tech || []).join(', ')
  form.value = {
    ...project,
    status: project.status || (project.isActive !== false ? 'active' : 'completed'),
    image: project.image || project.preview_image || project.previewImage || '',
    isActive: project.isActive !== false && project.is_active !== false
  }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  editingProject.value = null
  selectedFile.value = null
  imagePreview.value = ''
  modalError.value = ''
  isSubmitting.value = false
}

const saveProject = async () => {
  isSubmitting.value = true
  modalError.value = ''

  const techArray = techStackInput.value
    .split(',')
    .map(t => t.trim())
    .filter(Boolean)

  try {
    // Dispatch to centralized saveProject helper
    const savedProject = await adminApi.saveProject({
      ...form.value,
      tech: techArray,
      tech_stack: techStackInput.value
    }, selectedFile.value, editingProject.value?.id)

    // Re-populate reactive form state with returned database resource
    if (savedProject && typeof savedProject === 'object') {
      form.value = {
        ...form.value,
        ...savedProject,
        id: savedProject.id || form.value.id,
        title: savedProject.title || form.value.title,
        subtitle: savedProject.subtitle || form.value.subtitle,
        category: savedProject.category || form.value.category,
        year: savedProject.year ? savedProject.year.toString() : form.value.year,
        status: savedProject.status || form.value.status || 'active',
        accentColor: savedProject.accent_color || savedProject.accentColor || form.value.accentColor,
        description: savedProject.description || form.value.description,
        image: savedProject.image || savedProject.thumbnail || savedProject.thumbnail_url || form.value.image,
        liveUrl: savedProject.live_url || savedProject.liveUrl || form.value.liveUrl,
        githubUrl: savedProject.github_url || savedProject.githubUrl || form.value.githubUrl,
        badge: savedProject.badge || form.value.badge,
        isActive: savedProject.is_active !== undefined ? Boolean(savedProject.is_active) : form.value.isActive
      }
      imagePreview.value = form.value.image
      selectedFile.value = null
    }

    toastMessage.value = editingProject.value
      ? `Project "${form.value.title}" successfully updated.`
      : `Project "${form.value.title}" successfully created.`
    isErrorToast.value = false

    notifySync()
    await loadProjects()
    emit('stats-updated')
    closeModal()
  } catch (err) {
    const detailedMsg = adminApi.formatApiError(err, 'Failed to save project.')
    modalError.value = detailedMsg
    toastMessage.value = detailedMsg
    isErrorToast.value = true
  } finally {
    isSubmitting.value = false
    setTimeout(() => { toastMessage.value = '' }, 5000)
  }
}

const toggleStatus = async (project) => {
  const current = project.is_active !== undefined ? project.is_active : project.isActive !== false
  const newStatus = !current
  const newStatusStr = newStatus ? 'active' : 'draft'
  try {
    await adminApi.updateProject(project.id, { 
      is_active: newStatus ? 1 : 0, 
      isActive: newStatus,
      status: project.status || newStatusStr
    })
    project.isActive = newStatus
    project.is_active = newStatus
    project.status = project.status || newStatusStr
    toastMessage.value = `Project "${project.title}" marked as ${newStatus ? 'Live' : 'Draft'}.`
    isErrorToast.value = false
    notifySync()
    emit('stats-updated')
  } catch (err) {
    console.error('Toggle status error:', err)
    toastMessage.value = adminApi.formatApiError(err, 'Failed to update project status.')
    isErrorToast.value = true
  } finally {
    setTimeout(() => { toastMessage.value = '' }, 4000)
  }
}

const confirmDelete = (project) => {
  projectToDelete.value = project
}

const executeDelete = async () => {
  if (!projectToDelete.value) return
  try {
    await adminApi.deleteProject(projectToDelete.value.id)
    toastMessage.value = `Project "${projectToDelete.value.title}" deleted.`
    isErrorToast.value = false
    projectToDelete.value = null
    notifySync()
    await loadProjects()
    emit('stats-updated')
  } catch (err) {
    toastMessage.value = adminApi.formatApiError(err, 'Failed to delete project.')
    isErrorToast.value = true
    projectToDelete.value = null
  } finally {
    setTimeout(() => { toastMessage.value = '' }, 4000)
  }
}
</script>
