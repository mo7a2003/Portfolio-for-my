<template>
  <div class="space-y-8 max-w-6xl">
    
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 border border-gold-500/30 text-xs font-mono text-gold-400 mb-2">
          <Briefcase class="w-3.5 h-3.5 text-gold-400" />
          <span>Admin\ProfileController & About Studio</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
          About & Career Trajectory Studio
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 font-mono mt-1">
          Full management for personal biography, core value pillars, metrics, and career milestones
        </p>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center gap-3 self-start sm:self-auto">
        <button
          @click="showPreviewModal = true"
          type="button"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 hover:border-gold-500/40 text-slate-300 hover:text-white font-mono text-xs transition-all cursor-pointer"
        >
          <Eye class="w-4 h-4 text-cyan-400" />
          <span>Live Preview</span>
        </button>

        <button
          @click="saveAll"
          :disabled="isSaving"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs transition-all shadow-[0_0_22px_rgba(212,175,55,0.4)] cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="isSaving" class="w-3.5 h-3.5 rounded-full border-2 border-slate-950 border-t-transparent animate-spin"></span>
          <Save v-else class="w-4 h-4 stroke-[2.5]" />
          <span>{{ isSaving ? 'Saving Changes...' : 'Save All Changes' }}</span>
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

    <!-- Studio Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-800/80 pb-3 overflow-x-auto custom-scrollbar font-mono text-xs">
      <button
        v-for="tab in tabs"
        :key="tab.id"
        @click="activeTab = tab.id"
        class="flex items-center gap-2 px-4 py-2 rounded-xl transition-all cursor-pointer shrink-0"
        :class="activeTab === tab.id 
          ? 'bg-gold-500/15 text-gold-300 border border-gold-500/40 shadow-[0_0_15px_rgba(212,175,55,0.2)] font-bold' 
          : 'text-slate-400 hover:text-white hover:bg-slate-900 border border-transparent'"
      >
        <component :is="tab.icon" class="w-3.5 h-3.5" />
        <span>{{ tab.label }}</span>
        <span 
          v-if="tab.count !== undefined" 
          class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-800 text-slate-300"
        >
          {{ tab.count }}
        </span>
      </button>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 1: Persona & Biography                               -->
    <!-- ======================================================== -->
    <div v-show="activeTab === 'persona'" class="space-y-6">
      <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
        
        <div class="flex items-center gap-3 pb-4 border-b border-slate-800/80">
          <div class="w-9 h-9 rounded-xl bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400">
            <User class="w-5 h-5 text-gold-400" />
          </div>
          <div>
            <h2 class="text-base sm:text-lg font-bold text-white">
              Persona & Core Biography
            </h2>
            <p class="text-xs text-slate-400 font-mono">
              Reflected on the Left Card of the About Section
            </p>
          </div>
        </div>

        <!-- Avatar Image Upload Row -->
        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 flex flex-col sm:flex-row items-center gap-6">
          <div class="relative shrink-0">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-gold-400 via-amber-200 to-cyan-400 p-[2px] shadow-[0_0_22px_rgba(212,175,55,0.4)]">
              <img 
                :src="avatarPreview || resolveAssetUrl(form.avatar) || form.avatar || '/developer-portrait.jpg'" 
                alt="Avatar" 
                class="w-full h-full object-cover rounded-[14px] bg-slate-900"
                @error="(e) => e.target.src = '/developer-portrait.jpg'"
              />
            </div>
          </div>
          <div class="flex-1 w-full space-y-2">
            <label class="block text-xs font-mono text-slate-300">About Avatar Photo (Image Upload)</label>
            <input 
              type="file" 
              @change="handleAvatarUpload" 
              accept="image/*"
              class="w-full px-4 py-2 rounded-xl bg-white/[0.03] border border-white/10 text-gray-300 text-xs file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500/20 file:text-amber-300 hover:file:bg-amber-500/30 cursor-pointer font-mono"
            />
            <div class="flex items-center gap-2 pt-1">
              <span class="text-[11px] font-mono text-slate-500">Or URL:</span>
              <input
                v-model="form.avatar"
                type="text"
                placeholder="/developer-portrait.jpg"
                class="flex-1 bg-slate-950 border border-slate-800 focus:border-gold-400 rounded-lg px-2.5 py-1 text-xs font-mono text-white outline-none"
              />
            </div>
          </div>
        </div>

        <!-- Name & Professional Title -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Full Name *</label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="MOHAMMED ALHWAITI"
              class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Professional Title *</label>
            <input
              v-model="form.title"
              type="text"
              required
              placeholder="Principal Full-Stack & 3D Web Graphics Architect"
              class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Location / Status</label>
            <input
              v-model="form.location"
              type="text"
              placeholder="Saudi Arabia / Remote"
              class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Timeline Header Range</label>
            <input
              v-model="form.timelineHeader"
              type="text"
              placeholder="2019 — Present"
              class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
            />
          </div>
        </div>

        <!-- Comprehensive Biography -->
        <div>
          <label class="block text-xs font-mono text-slate-300 mb-1.5">Comprehensive Biography Paragraph *</label>
          <textarea
            v-model="form.fullBio"
            rows="5"
            required
            placeholder="A passionate front-end developer and UI/UX designer blending cutting-edge WebGL 3D graphics..."
            class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white leading-relaxed outline-none"
          ></textarea>
        </div>

        <!-- Arabic Persona & Biography (AR) -->
        <div class="p-5 rounded-2xl bg-amber-500/5 border border-amber-500/20 space-y-4">
          <div class="flex items-center gap-2 text-xs font-mono text-amber-400 font-bold">
            <span>🌐 Arabic Persona & Bio (البيانات الشخصية بالعربية)</span>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">الاسم الكامل بالعربية</label>
              <input
                v-model="form.nameAr"
                type="text"
                dir="rtl"
                placeholder="محمد الحويطي"
                class="w-full bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">المسمى المهني بالعربية</label>
              <input
                v-model="form.titleAr"
                type="text"
                dir="rtl"
                placeholder="مهندس واجهات أمامية ومعماري رسوميات ثلاثية الأبعاد"
                class="w-full bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
              />
            </div>
          </div>
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">الموقع الجغرافي بالعربية</label>
            <input
              v-model="form.locationAr"
              type="text"
              dir="rtl"
              placeholder="المملكة العربية السعودية / عن بُعد"
              class="w-full bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">النبذة الشخصية الكاملة بالعربية</label>
            <textarea
              v-model="form.fullBioAr"
              rows="4"
              dir="rtl"
              placeholder="مطور واجهات أمامية ومصمم واجهات وتجربة مستخدم شغوف بدمج رسوميات WebGL 3D المتطورة..."
              class="w-full bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-xl px-3.5 py-2.5 text-xs text-white leading-relaxed outline-none"
            ></textarea>
          </div>
        </div>

      </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 2: Core Philosophy & Metrics Badges                  -->
    <!-- ======================================================== -->
    <div v-show="activeTab === 'philosophy'" class="space-y-6">
      
      <!-- Core Philosophy Pillars -->
      <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800/80">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400">
              <Sparkles class="w-5 h-5 text-cyan-400" />
            </div>
            <div>
              <h2 class="text-base sm:text-lg font-bold text-white">
                Core Philosophy & Pillars
              </h2>
              <p class="text-xs text-slate-400 font-mono">
                The 3 numerical value pillars displayed under the biography (01, 02, 03)
              </p>
            </div>
          </div>

          <button
            @click="addPhilosophyPoint"
            type="button"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gold-500/15 hover:bg-gold-500/25 border border-gold-500/30 text-gold-300 font-mono text-xs cursor-pointer"
          >
            <Plus class="w-3.5 h-3.5" />
            <span>Add Pillar</span>
          </button>
        </div>

        <div class="space-y-4">
          <div
            v-for="(point, idx) in form.philosophy"
            :key="point.id || idx"
            class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 flex flex-col md:flex-row items-start md:items-center gap-4 justify-between"
          >
            <div class="flex items-center gap-3 w-full md:w-auto">
              <input
                v-model="point.number"
                type="text"
                placeholder="01."
                class="w-14 bg-slate-950 border border-slate-800 focus:border-gold-400 rounded-lg px-2 py-1.5 text-xs font-mono font-bold text-gold-400 text-center outline-none"
              />
              <input
                v-model="point.title"
                type="text"
                placeholder="Pillar Title (e.g. Performance First)"
                class="w-full md:w-56 bg-slate-950 border border-slate-800 focus:border-gold-400 rounded-lg px-3 py-1.5 text-xs text-white font-bold outline-none"
              />
            </div>

            <div class="flex-1 w-full">
              <input
                v-model="point.description"
                type="text"
                placeholder="Pillar explanation / impact statement..."
                class="w-full bg-slate-950 border border-slate-800 focus:border-gold-400 rounded-lg px-3 py-1.5 text-xs text-slate-300 outline-none"
              />
            </div>

            <div class="flex items-center gap-2 self-end md:self-auto shrink-0">
              <input 
                v-model="point.color" 
                type="color" 
                class="w-7 h-7 rounded-lg cursor-pointer bg-transparent border-0" 
                title="Pillar Accent Color"
              />
              <button
                @click="removePhilosophyPoint(idx)"
                type="button"
                class="p-1.5 rounded-lg bg-red-950/40 hover:bg-red-950/80 border border-red-500/20 text-red-400 hover:text-red-200 cursor-pointer"
                title="Remove pillar"
              >
                <Trash2 class="w-3.5 h-3.5" />
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Metrics Badges (60 FPS, 100% Precision) -->
      <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800/80">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400">
              <Activity class="w-5 h-5 text-gold-400" />
            </div>
            <div>
              <h2 class="text-base sm:text-lg font-bold text-white">
                Bottom Quick Metrics Badges
              </h2>
              <p class="text-xs text-slate-400 font-mono">
                The 2 big visual metric tiles below the left card (e.g. 60 FPS, 100%)
              </p>
            </div>
          </div>

          <button
            @click="addMetric"
            type="button"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gold-500/15 hover:bg-gold-500/25 border border-gold-500/30 text-gold-300 font-mono text-xs cursor-pointer"
          >
            <Plus class="w-3.5 h-3.5" />
            <span>Add Metric</span>
          </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div
            v-for="(metric, idx) in form.metrics"
            :key="metric.id || idx"
            class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800 space-y-3 relative group"
          >
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-mono text-slate-400 uppercase">Metric #{{ idx + 1 }}</span>
              <button
                @click="removeMetric(idx)"
                type="button"
                class="text-red-400 hover:text-white p-1"
                title="Remove metric"
              >
                <X class="w-3.5 h-3.5" />
              </button>
            </div>

            <div class="flex items-center gap-3">
              <div class="flex-1">
                <label class="block text-[10px] font-mono text-slate-400 mb-1">Value / Target *</label>
                <input
                  v-model="metric.value"
                  type="text"
                  placeholder="60 FPS"
                  class="w-full bg-slate-950 border border-slate-800 focus:border-gold-400 rounded-xl px-3 py-1.5 text-base font-bold font-mono text-gold-400 outline-none"
                />
              </div>
              <div>
                <label class="block text-[10px] font-mono text-slate-400 mb-1">Color</label>
                <input
                  v-model="metric.color"
                  type="color"
                  class="w-9 h-9 rounded-xl cursor-pointer bg-transparent border-0"
                />
              </div>
            </div>

            <div>
              <label class="block text-[10px] font-mono text-slate-400 mb-1">Subtext Label *</label>
              <input
                v-model="metric.label"
                type="text"
                placeholder="WebGL Smoothness Target"
                class="w-full bg-slate-950 border border-slate-800 focus:border-gold-400 rounded-xl px-3 py-1.5 text-xs text-white outline-none"
              />
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- ======================================================== -->
    <!-- TAB 3: Career Trajectory Timeline (Milestones CRUD)      -->
    <!-- ======================================================== -->
    <div v-show="activeTab === 'timeline'" class="space-y-6">
      
      <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800/80">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400">
              <Milestone class="w-5 h-5 text-gold-400" />
            </div>
            <div>
              <h2 class="text-base sm:text-lg font-bold text-white">
                Career Trajectory & Milestones Timeline
              </h2>
              <p class="text-xs text-slate-400 font-mono">
                Right card of the About Section — interactive chronologically ordered milestones
              </p>
            </div>
          </div>

          <button
            @click="openAddMilestoneModal"
            type="button"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs shadow-[0_0_15px_rgba(212,175,55,0.3)] cursor-pointer self-start sm:self-auto"
          >
            <Plus class="w-4 h-4 stroke-[3]" />
            <span>Add Milestone</span>
          </button>
        </div>

        <!-- Milestones Timeline List -->
        <div class="space-y-4">
          <div
            v-for="(m, idx) in form.milestones"
            :key="m.id || idx"
            class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-gold-500/40 transition-all flex flex-col md:flex-row items-start md:items-center justify-between gap-4 group"
          >
            <!-- Indicator & Title info -->
            <div class="flex items-start gap-3.5 flex-1">
              <span 
                class="w-3.5 h-3.5 rounded-full shrink-0 mt-1 shadow-[0_0_10px]"
                :style="{ backgroundColor: m.color || '#d4af37', boxShadow: `0 0 10px ${m.color || '#d4af37'}` }"
              ></span>

              <div class="space-y-1 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                  <h4 class="text-sm font-bold text-white group-hover:text-gold-300 transition-colors">
                    {{ m.title }}
                  </h4>
                  <span 
                    class="px-2 py-0.5 rounded-full text-[10px] font-mono border"
                    :style="{ 
                      backgroundColor: `${m.color || '#d4af37'}15`, 
                      borderColor: `${m.color || '#d4af37'}40`,
                      color: m.color || '#d4af37' 
                    }"
                  >
                    {{ m.period || m.year || m.date || m.time_frame }}
                  </span>
                  <span v-if="m.badge" class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-cyan-500/10 text-cyan-300 border border-cyan-500/30">
                    {{ m.badge }}
                  </span>
                </div>

                <p v-if="m.company" class="text-xs font-mono text-cyan-300">
                  {{ m.company }}
                </p>

                <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed">
                  {{ m.description }}
                </p>
              </div>
            </div>

            <!-- Actions (Move Up, Move Down, Edit, Delete) -->
            <div class="flex items-center gap-1.5 self-end md:self-auto shrink-0">
              <button
                @click="moveMilestoneUp(idx)"
                :disabled="idx === 0"
                type="button"
                class="p-1.5 rounded-lg bg-slate-950 border border-slate-800 text-slate-400 hover:text-white disabled:opacity-30 cursor-pointer"
                title="Move Up"
              >
                <ArrowUp class="w-3.5 h-3.5" />
              </button>
              <button
                @click="moveMilestoneDown(idx)"
                :disabled="idx === form.milestones.length - 1"
                type="button"
                class="p-1.5 rounded-lg bg-slate-950 border border-slate-800 text-slate-400 hover:text-white disabled:opacity-30 cursor-pointer"
                title="Move Down"
              >
                <ArrowDown class="w-3.5 h-3.5" />
              </button>
              <button
                @click="openEditMilestoneModal(m, idx)"
                type="button"
                class="p-1.5 rounded-lg bg-gold-500/10 hover:bg-gold-500/20 border border-gold-500/30 text-gold-300 cursor-pointer"
                title="Edit Milestone"
              >
                <Edit3 class="w-3.5 h-3.5" />
              </button>
              <button
                @click="removeMilestone(idx)"
                type="button"
                class="p-1.5 rounded-lg bg-red-950/30 hover:bg-red-950/60 border border-red-500/20 text-red-400 hover:text-red-200 cursor-pointer"
                title="Delete Milestone"
              >
                <Trash2 class="w-3.5 h-3.5" />
              </button>
            </div>
          </div>
        </div>

      </div>

    </div>

    <!-- ======================================================== -->
    <!-- Add / Edit Milestone Modal                               -->
    <!-- ======================================================== -->
    <div
      v-if="isMilestoneModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
    >
      <div class="relative w-full max-w-lg bg-[#030712] border border-gold-500/40 rounded-3xl p-6 sm:p-8 shadow-[0_0_50px_rgba(212,175,55,0.25)] space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400">
              <Milestone class="w-4 h-4" />
            </div>
            <div>
              <h3 class="text-base font-bold text-white">
                {{ editingMilestoneIndex !== null ? 'Edit Milestone' : 'Add Milestone to Timeline' }}
              </h3>
              <p class="text-xs text-slate-400 font-mono">
                Career Trajectory & Milestones entry
              </p>
            </div>
          </div>
          <button @click="isMilestoneModalOpen = false" class="text-slate-400 hover:text-white p-1">
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="saveMilestoneModal" class="space-y-4">
          
          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Milestone Title *</label>
            <input
              v-model="milestoneForm.title"
              type="text"
              required
              placeholder="e.g. Senior Front-End Architect & 3D Web Specialist"
              class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>

          <!-- Arabic Milestone Translation (AR) -->
          <div class="p-3.5 rounded-2xl bg-amber-500/5 border border-amber-500/20 space-y-3">
            <div class="flex items-center gap-2 text-xs font-mono text-amber-400 font-bold">
              <span>🌐 Arabic Milestone (ترجمة المحطة بالعربية)</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block text-[11px] font-mono text-slate-300 mb-1">عنوان المحطة بالعربية</label>
                <input
                  v-model="milestoneForm.title_ar"
                  type="text"
                  dir="rtl"
                  placeholder="مثال: التخرج من الكلية الجامعية (دفعة 2026)"
                  class="w-full bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-xl px-3 py-2 text-xs text-white outline-none"
                />
              </div>
              <div>
                <label class="block text-[11px] font-mono text-slate-300 mb-1">المؤسسة / الجهة بالعربية</label>
                <input
                  v-model="milestoneForm.company_ar"
                  type="text"
                  dir="rtl"
                  placeholder="مثال: الكلية الجامعية للعلوم التطبيقية بغزة"
                  class="w-full bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-xl px-3 py-2 text-xs text-white outline-none"
                />
              </div>
            </div>
            <div>
              <label class="block text-[11px] font-mono text-slate-300 mb-1">الوصف التفصيلي بالعربية</label>
              <textarea
                v-model="milestoneForm.description_ar"
                rows="2"
                dir="rtl"
                placeholder="التخرج بدرجة دبلوم مهني في تصميم وتطوير مواقع الويب..."
                class="w-full bg-slate-900 border border-slate-800 focus:border-amber-400 rounded-xl px-3 py-2 text-xs text-white leading-relaxed outline-none resize-none"
              ></textarea>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Period / Year *</label>
              <input
                v-model="milestoneForm.period"
                type="text"
                required
                placeholder="e.g. 2026 or 2023 — Present"
                class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white font-mono outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-mono text-slate-300 mb-1.5">Accent Color</label>
              <div class="flex items-center gap-2">
                <input
                  v-model="milestoneForm.color"
                  type="text"
                  placeholder="#d4af37"
                  class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3 py-2 text-xs font-mono text-white outline-none"
                />
                <input
                  v-model="milestoneForm.color"
                  type="color"
                  class="w-8 h-8 rounded-lg cursor-pointer bg-transparent border-0"
                />
              </div>
            </div>
          </div>

          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Organization / Subtitle</label>
            <input
              v-model="milestoneForm.company"
              type="text"
              placeholder="e.g. Freelance & Enterprise Collaborations"
              class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Ribbon / Award Tag (Optional)</label>
            <input
              v-model="milestoneForm.badge"
              type="text"
              placeholder="e.g. 🏆 1st Place Award"
              class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-mono text-slate-300 mb-1.5">Comprehensive Description *</label>
            <textarea
              v-model="milestoneForm.description"
              rows="3"
              required
              placeholder="Leading the architecture of high-performance Vue 3 web applications with custom Three.js WebGL spatial dashboards..."
              class="w-full bg-slate-900 border border-slate-800 focus:border-gold-400 rounded-xl px-3.5 py-2.5 text-xs text-white leading-relaxed outline-none"
            ></textarea>
          </div>

          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
            <button
              type="button"
              @click="isMilestoneModalOpen = false"
              class="px-4 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white text-xs font-mono cursor-pointer"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-5 py-2 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs shadow-[0_0_15px_rgba(212,175,55,0.35)] cursor-pointer"
            >
              {{ editingMilestoneIndex !== null ? 'Save Changes' : 'Add to Timeline' }}
            </button>
          </div>

        </form>

      </div>
    </div>

    <!-- ======================================================== -->
    <!-- Full Live Section Preview Modal                          -->
    <!-- ======================================================== -->
    <div
      v-if="showPreviewModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-xl overflow-y-auto"
    >
      <div class="relative w-full max-w-5xl bg-[#030712] border border-gold-500/40 rounded-3xl p-6 sm:p-10 shadow-[0_0_60px_rgba(212,175,55,0.3)] my-8">
        
        <div class="flex items-center justify-between pb-6 border-b border-slate-800 mb-8">
          <div class="flex items-center gap-3">
            <Eye class="w-5 h-5 text-gold-400" />
            <h3 class="text-lg font-bold text-white">Live About Section Preview</h3>
          </div>
          <button @click="showPreviewModal = false" class="text-slate-400 hover:text-white p-2">
            <X class="w-6 h-6" />
          </button>
        </div>

        <!-- Rendered Replica of the Public About Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          
          <!-- Left: Persona & Core Philosophy -->
          <div class="lg:col-span-5 space-y-6">
            <div class="p-8 rounded-3xl bg-gradient-to-b from-slate-900/90 via-obsidian-900/95 to-obsidian-950 border border-gold-500/30 backdrop-blur-2xl shadow-glass">
              
              <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-2xl p-0.5 bg-gradient-to-br from-gold-400 via-amber-200 to-cyan-400 shadow-gold-glow shrink-0">
                  <img 
                    :src="avatarPreview || form.avatar || '/developer-portrait.jpg'" 
                    alt="Avatar" 
                    class="w-full h-full object-cover rounded-2xl"
                  />
                </div>
                <div>
                  <h3 class="text-lg font-bold text-white tracking-wide">
                    {{ form.name || 'MOHAMMED ALHWAITI' }}
                  </h3>
                  <p class="text-xs font-mono text-gold-400">
                    {{ form.title || 'Principal Architect' }}
                  </p>
                  <p class="text-[11px] text-slate-400 font-mono mt-0.5">
                    📍 {{ form.location || 'Remote' }}
                  </p>
                </div>
              </div>

              <p class="text-sm text-slate-300 leading-relaxed mb-6">
                {{ form.fullBio }}
              </p>

              <!-- Philosophy Points -->
              <div class="space-y-3 pt-4 border-t border-white/10 font-mono text-xs">
                <div 
                  v-for="point in form.philosophy" 
                  :key="point.number" 
                  class="flex items-start gap-2.5"
                >
                  <span class="font-bold shrink-0" :style="{ color: point.color || '#d4af37' }">{{ point.number }}</span>
                  <span class="text-slate-300">
                    <strong class="text-white">{{ point.title }}:</strong> {{ point.description }}
                  </span>
                </div>
              </div>

            </div>

            <!-- Quick Metrics Badges -->
            <div class="grid grid-cols-2 gap-4">
              <div 
                v-for="metric in form.metrics" 
                :key="metric.label" 
                class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 text-center"
              >
                <div class="text-2xl font-black font-mono" :style="{ color: metric.color || '#d4af37' }">
                  {{ metric.value }}
                </div>
                <div class="text-[11px] text-slate-400 mt-1">{{ metric.label }}</div>
              </div>
            </div>
          </div>

          <!-- Right: Career Milestones -->
          <div class="lg:col-span-7">
            <div class="p-8 sm:p-10 rounded-3xl bg-gradient-to-b from-slate-900/90 via-obsidian-900/95 to-obsidian-950 border border-white/10 backdrop-blur-2xl shadow-glass">
              
              <h3 class="text-base font-bold text-white tracking-wider uppercase font-mono mb-8 flex items-center justify-between">
                <span>Career Trajectory & Milestones</span>
                <span class="text-xs text-gold-400 lowercase">{{ form.timelineHeader || '2019 — Present' }}</span>
              </h3>

              <div class="relative border-l border-gold-500/20 pl-6 sm:pl-8 space-y-8">
                
                <div 
                  v-for="m in form.milestones" 
                  :key="m.title" 
                  class="relative group"
                >
                  <div 
                    class="absolute -left-[31px] sm:-left-[39px] top-1.5 w-4 h-4 rounded-full bg-obsidian-950 border-2 shadow-gold-glow"
                    :style="{ borderColor: m.color || '#d4af37' }"
                  ></div>

                  <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                    <h4 class="text-base font-bold text-white">
                      {{ m.title }}
                    </h4>
                    <span 
                      class="px-2.5 py-0.5 rounded-full text-[10px] font-mono border"
                      :style="{ 
                        backgroundColor: `${m.color || '#d4af37'}15`, 
                        borderColor: `${m.color || '#d4af37'}40`,
                        color: m.color || '#d4af37' 
                      }"
                    >
                      {{ m.period || m.year || m.date || m.start_date }}
                    </span>
                  </div>

                  <p v-if="m.company" class="text-xs font-mono mb-2" :style="{ color: m.color || '#38bdf8' }">
                    {{ m.company }}
                  </p>

                  <p class="text-xs sm:text-sm text-slate-300/80 leading-relaxed">
                    {{ m.description }}
                  </p>
                </div>

              </div>

            </div>
          </div>

        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { 
  Briefcase, 
  User, 
  Sparkles, 
  Milestone, 
  Plus, 
  Trash2, 
  Edit3, 
  Save, 
  Eye, 
  X, 
  CheckCircle2, 
  AlertCircle, 
  Activity, 
  ArrowUp, 
  ArrowDown 
} from 'lucide-vue-next'
import { 
  personalInfo, 
  defaultMilestones, 
  defaultPhilosophy, 
  defaultAboutMetrics 
} from '../../data/portfolioData'
import { adminApi } from '../../services/adminApi'
import { resolveAssetUrl } from '../../services/portfolioService'

const emit = defineEmits(['stats-updated'])

const activeTab = ref('persona')
const isSaving = ref(false)
const toastMessage = ref('')
const isErrorToast = ref(false)
const showPreviewModal = ref(false)

const selectedAvatarFile = ref(null)
const avatarPreview = ref('')

const isMilestoneModalOpen = ref(false)
const editingMilestoneIndex = ref(null)
const milestoneForm = ref({
  title: '',
  title_ar: '',
  period: '',
  year: '',
  date: '',
  start_date: '',
  completion_date: '',
  company: '',
  company_ar: '',
  description: '',
  description_ar: '',
  color: '#d4af37',
  badge: '',
  badge_ar: ''
})

const tabs = [
  { id: 'persona', label: 'Persona & Bio', icon: User },
  { id: 'philosophy', label: 'Core Pillars & Metrics', icon: Sparkles },
  { id: 'timeline', label: 'Career Trajectory Timeline', icon: Milestone }
]

const form = ref({
  name: personalInfo.name,
  nameAr: personalInfo.nameAr,
  title: personalInfo.title,
  titleAr: '',
  subtitle: personalInfo.shortBio,
  subtitleAr: '',
  shortBio: personalInfo.shortBio,
  shortBioAr: '',
  location: personalInfo.location,
  locationAr: '',
  email: personalInfo.email,
  whatsapp: personalInfo.whatsapp,
  fullBio: personalInfo.fullBio,
  fullBioAr: '',
  avatar: personalInfo.avatar,
  portrait: personalInfo.portrait,
  timelineHeader: '2019 — Present',
  philosophy: JSON.parse(JSON.stringify(defaultPhilosophy)),
  metrics: JSON.parse(JSON.stringify(defaultAboutMetrics)),
  milestones: JSON.parse(JSON.stringify(defaultMilestones))
})

const notifySync = () => {
  localStorage.setItem('portfolio_sync_trigger', Date.now().toString())
  try {
    localStorage.setItem('cached_portfolio_profile', JSON.stringify({
      ...form.value,
      bio: form.value.fullBio,
      short_bio: form.value.shortBio,
      full_bio: form.value.fullBio,
      timeline_header: form.value.timelineHeader,
      about_metrics: form.value.metrics
    }))
  } catch (e) {}
  window.dispatchEvent(new CustomEvent('portfolio-updated'))
}

const loadAboutData = async () => {
  try {
    const res = await adminApi.getProfile()
    if (res && typeof res === 'object') {
      if (res.name) form.value.name = res.name
      if (res.nameAr || res.name_ar) form.value.nameAr = res.nameAr || res.name_ar
      if (res.title) form.value.title = res.title
      if (res.titleAr || res.title_ar) form.value.titleAr = res.titleAr || res.title_ar
      if (res.subtitle || res.tagline) form.value.subtitle = res.subtitle || res.tagline
      if (res.shortBio || res.short_bio) form.value.shortBio = res.shortBio || res.short_bio
      if (res.location) form.value.location = res.location
      if (res.locationAr || res.location_ar) form.value.locationAr = res.locationAr || res.location_ar
      if (res.email) form.value.email = res.email
      if (res.whatsapp) form.value.whatsapp = res.whatsapp
      if (res.fullBio || res.full_bio || res.bio) form.value.fullBio = res.fullBio || res.full_bio || res.bio
      if (res.fullBioAr || res.full_bio_ar || res.bio_ar) form.value.fullBioAr = res.fullBioAr || res.full_bio_ar || res.bio_ar
      if (res.avatar) form.value.avatar = res.avatar
      if (res.portrait) form.value.portrait = res.portrait
      if (res.timeline_header || res.timelineHeader) form.value.timelineHeader = res.timeline_header || res.timelineHeader
      
      if (Array.isArray(res.philosophy) && res.philosophy.length) {
        form.value.philosophy = res.philosophy
      }
      if (Array.isArray(res.metrics || res.about_metrics) && (res.metrics || res.about_metrics).length) {
        form.value.metrics = res.metrics || res.about_metrics
      }
      if (Array.isArray(res.milestones || res.timeline) && (res.milestones || res.timeline).length) {
        form.value.milestones = (res.milestones || res.timeline).map((m, idx) => ({
          ...m,
          id: m.id || `ms_${idx + 1}`,
          title: m.title || '',
          title_ar: m.title_ar || m.titleAr || '',
          titleAr: m.title_ar || m.titleAr || '',
          period: m.period || m.year || m.date || m.time_frame || m.duration || m.start_date || m.completion_date || '',
          year: m.year || m.period || m.date || m.time_frame || m.duration || m.start_date || m.completion_date || '',
          date: m.date || m.period || m.year || m.start_date || m.completion_date || '',
          start_date: m.start_date || m.startDate || m.period || m.year || '',
          completion_date: m.completion_date || m.completionDate || m.period || m.year || '',
          company: m.company || m.organization || m.subtitle || '',
          company_ar: m.company_ar || m.companyAr || '',
          description: m.description || '',
          description_ar: m.description_ar || m.descriptionAr || '',
          color: m.color || '#d4af37',
          badge: m.badge || '',
          badge_ar: m.badge_ar || m.badgeAr || ''
        }))
      }
    }
  } catch (err) {
    console.warn('Could not load profile from server, using existing schema:', err.message)
  }
}

onMounted(() => {
  loadAboutData()
})

const handleAvatarUpload = (e) => {
  const file = e.target.files?.[0]
  if (file) {
    selectedAvatarFile.value = file
    avatarPreview.value = URL.createObjectURL(file)
  }
}

// Philosophy points management
const addPhilosophyPoint = () => {
  const count = form.value.philosophy.length + 1
  const numStr = count < 10 ? `0${count}.` : `${count}.`
  form.value.philosophy.push({
    id: `p_${Date.now()}`,
    number: numStr,
    title: 'New Value Pillar',
    description: 'Describe core architectural practice...',
    color: '#00f0ff'
  })
}

const removePhilosophyPoint = (idx) => {
  form.value.philosophy.splice(idx, 1)
}

// Metrics badges management
const addMetric = () => {
  form.value.metrics.push({
    id: `m_${Date.now()}`,
    value: '99.9%',
    label: 'Uptime Reliability',
    color: '#10b981'
  })
}

const removeMetric = (idx) => {
  form.value.metrics.splice(idx, 1)
}

// Milestones management
const openAddMilestoneModal = () => {
  editingMilestoneIndex.value = null
  const defaultPeriod = new Date().getFullYear().toString() + ' — Present'
  milestoneForm.value = {
    title: '',
    title_ar: '',
    period: defaultPeriod,
    year: defaultPeriod,
    date: defaultPeriod,
    start_date: defaultPeriod,
    completion_date: defaultPeriod,
    company: '',
    company_ar: '',
    description: '',
    description_ar: '',
    color: '#d4af37',
    badge: '',
    badge_ar: ''
  }
  isMilestoneModalOpen.value = true
}

const openEditMilestoneModal = (milestone, idx) => {
  editingMilestoneIndex.value = idx
  const periodVal = milestone.period || milestone.year || milestone.date || milestone.time_frame || milestone.duration || milestone.start_date || milestone.completion_date || ''
  const companyVal = milestone.company || milestone.organization || milestone.subtitle || ''
  const companyArVal = milestone.company_ar || milestone.companyAr || ''
  milestoneForm.value = {
    ...milestone,
    title: milestone.title || '',
    title_ar: milestone.title_ar || milestone.titleAr || '',
    period: periodVal,
    year: milestone.year || periodVal,
    date: milestone.date || periodVal,
    start_date: milestone.start_date || milestone.startDate || periodVal,
    completion_date: milestone.completion_date || milestone.completionDate || periodVal,
    company: companyVal,
    company_ar: companyArVal,
    description: milestone.description || '',
    description_ar: milestone.description_ar || milestone.descriptionAr || '',
    color: milestone.color || '#d4af37',
    badge: milestone.badge || '',
    badge_ar: milestone.badge_ar || milestone.badgeAr || ''
  }
  isMilestoneModalOpen.value = true
}

const saveMilestoneModal = async () => {
  const periodVal = String(
    milestoneForm.value.period || 
    milestoneForm.value.year || 
    milestoneForm.value.date || 
    milestoneForm.value.start_date || 
    milestoneForm.value.completion_date || 
    ''
  ).trim()
  const companyVal = String(milestoneForm.value.company || milestoneForm.value.organization || milestoneForm.value.subtitle || '').trim()
  const companyArVal = String(milestoneForm.value.company_ar || milestoneForm.value.companyAr || '').trim()
  const titleVal = String(milestoneForm.value.title || '').trim()
  const titleArVal = String(milestoneForm.value.title_ar || milestoneForm.value.titleAr || '').trim()
  const descVal = String(milestoneForm.value.description || '').trim()
  const descArVal = String(milestoneForm.value.description_ar || milestoneForm.value.descriptionAr || '').trim()
  const badgeVal = String(milestoneForm.value.badge || '').trim()
  const badgeArVal = String(milestoneForm.value.badge_ar || milestoneForm.value.badgeAr || '').trim()

  const normalizedEntry = {
    ...milestoneForm.value,
    id: milestoneForm.value.id || `ms_${Date.now()}`,
    title: titleVal,
    title_ar: titleArVal,
    titleAr: titleArVal,
    period: periodVal,
    year: periodVal,
    date: periodVal,
    time_frame: periodVal,
    duration: periodVal,
    start_date: milestoneForm.value.start_date || periodVal,
    startDate: milestoneForm.value.start_date || periodVal,
    end_date: milestoneForm.value.end_date || '',
    endDate: milestoneForm.value.end_date || '',
    completion_date: milestoneForm.value.completion_date || periodVal,
    completionDate: milestoneForm.value.completion_date || periodVal,
    company: companyVal,
    company_ar: companyArVal,
    companyAr: companyArVal,
    organization: companyVal,
    subtitle: companyVal,
    description: descVal,
    description_ar: descArVal,
    descriptionAr: descArVal,
    color: milestoneForm.value.color || '#d4af37',
    badge: badgeVal || null,
    badge_ar: badgeArVal || null
  }

  if (editingMilestoneIndex.value !== null) {
    form.value.milestones.splice(editingMilestoneIndex.value, 1, normalizedEntry)
  } else {
    form.value.milestones.unshift(normalizedEntry)
  }

  isMilestoneModalOpen.value = false
  notifySync()
  await saveAll()
}

const removeMilestone = async (idx) => {
  form.value.milestones.splice(idx, 1)
  notifySync()
  await saveAll()
}

const moveMilestoneUp = async (idx) => {
  if (idx <= 0) return
  const item = form.value.milestones.splice(idx, 1)[0]
  form.value.milestones.splice(idx - 1, 0, item)
  notifySync()
  await saveAll()
}

const moveMilestoneDown = async (idx) => {
  if (idx >= form.value.milestones.length - 1) return
  const item = form.value.milestones.splice(idx, 1)[0]
  form.value.milestones.splice(idx + 1, 0, item)
  notifySync()
  await saveAll()
}

// Save All changes to backend API
const saveAll = async () => {
  isSaving.value = true
  toastMessage.value = ''
  isErrorToast.value = false

  try {
    let finalAvatarUrl = form.value.avatar

    // Pre-upload avatar image if file selected
    if (selectedAvatarFile.value) {
      try {
        const mediaRes = await adminApi.uploadMedia(selectedAvatarFile.value)
        if (mediaRes && (mediaRes.url || mediaRes.path)) {
          finalAvatarUrl = mediaRes.url || mediaRes.path
          form.value.avatar = finalAvatarUrl
        }
      } catch (mediaErr) {
        console.warn('Avatar pre-upload skipped:', mediaErr.message)
      }
    }

    const normalizedMilestones = (form.value.milestones || []).map((m, idx) => {
      const periodVal = String(m.period || m.year || m.date || m.time_frame || m.duration || m.start_date || m.completion_date || '').trim()
      const companyVal = String(m.company || m.organization || m.subtitle || '').trim()
      const companyArVal = String(m.company_ar || m.companyAr || '').trim()
      const titleVal = String(m.title || '').trim()
      const titleArVal = String(m.title_ar || m.titleAr || '').trim()
      const descVal = String(m.description || '').trim()
      const descArVal = String(m.description_ar || m.descriptionAr || '').trim()
      const badgeVal = String(m.badge || '').trim()
      const badgeArVal = String(m.badge_ar || m.badgeAr || '').trim()

      return {
        ...m,
        id: m.id || `ms_${idx + 1}`,
        title: titleVal,
        title_ar: titleArVal,
        titleAr: titleArVal,
        period: periodVal,
        year: periodVal,
        date: periodVal,
        time_frame: periodVal,
        duration: periodVal,
        start_date: m.start_date || m.startDate || periodVal,
        startDate: m.start_date || m.startDate || periodVal,
        end_date: m.end_date || m.endDate || '',
        endDate: m.end_date || m.endDate || '',
        completion_date: m.completion_date || m.completionDate || periodVal,
        completionDate: m.completion_date || m.completionDate || periodVal,
        company: companyVal,
        company_ar: companyArVal,
        companyAr: companyArVal,
        organization: companyVal,
        subtitle: companyVal,
        description: descVal,
        description_ar: descArVal,
        descriptionAr: descArVal,
        color: m.color || '#d4af37',
        badge: badgeVal || null,
        badge_ar: badgeArVal || null
      }
    })
    form.value.milestones = normalizedMilestones

    const payload = {
      name: form.value.name || personalInfo.name,
      nameAr: form.value.nameAr || personalInfo.nameAr,
      name_ar: form.value.nameAr || personalInfo.nameAr,
      title: form.value.title || personalInfo.title,
      title_ar: form.value.titleAr || '',
      titleAr: form.value.titleAr || '',
      subtitle: form.value.subtitle || form.value.shortBio || personalInfo.shortBio,
      shortBio: form.value.shortBio || personalInfo.shortBio,
      short_bio: form.value.shortBio || personalInfo.shortBio,
      location: form.value.location || personalInfo.location,
      location_ar: form.value.locationAr || '',
      locationAr: form.value.locationAr || '',
      email: form.value.email || personalInfo.email,
      whatsapp: form.value.whatsapp || null,
      fullBio: form.value.fullBio || personalInfo.fullBio,
      full_bio: form.value.fullBio || personalInfo.fullBio,
      full_bio_ar: form.value.fullBioAr || '',
      fullBioAr: form.value.fullBioAr || '',
      bio: form.value.fullBio || personalInfo.fullBio,
      bio_ar: form.value.fullBioAr || '',
      about: form.value.fullBio || personalInfo.fullBio,
      avatar: finalAvatarUrl || personalInfo.avatar,
      portrait: form.value.portrait || personalInfo.portrait,
      timelineHeader: form.value.timelineHeader || '2019 — Present',
      timeline_header: form.value.timelineHeader || '2019 — Present',
      philosophy: form.value.philosophy,
      metrics: form.value.metrics,
      about_metrics: form.value.metrics,
      milestones: normalizedMilestones,
      timeline: normalizedMilestones,
      career_milestones: normalizedMilestones
    }

    const res = await adminApi.updateProfile(payload)
    if (res) {
      const data = res.data || res
      if (typeof data === 'object') {
        if (data.name) form.value.name = data.name
        if (data.nameAr || data.name_ar) form.value.nameAr = data.nameAr || data.name_ar
        if (data.title) form.value.title = data.title
        if (data.titleAr || data.title_ar) form.value.titleAr = data.titleAr || data.title_ar
        if (data.location) form.value.location = data.location
        if (data.locationAr || data.location_ar) form.value.locationAr = data.locationAr || data.location_ar
        if (data.fullBio || data.full_bio) form.value.fullBio = data.fullBio || data.full_bio
        if (data.fullBioAr || data.full_bio_ar || data.bio_ar) form.value.fullBioAr = data.fullBioAr || data.full_bio_ar || data.bio_ar
        if (data.avatar) form.value.avatar = data.avatar
        if (data.portrait) form.value.portrait = data.portrait
        if (Array.isArray(data.philosophy)) form.value.philosophy = data.philosophy
        if (Array.isArray(data.metrics || data.about_metrics)) form.value.metrics = data.metrics || data.about_metrics
        if (Array.isArray(data.milestones || data.timeline)) {
          form.value.milestones = (data.milestones || data.timeline).map((m, idx) => ({
            ...m,
            id: m.id || `ms_${idx + 1}`,
            title: m.title || '',
            title_ar: m.title_ar || m.titleAr || '',
            titleAr: m.title_ar || m.titleAr || '',
            period: m.period || m.year || m.date || m.time_frame || m.duration || m.start_date || m.completion_date || '',
            year: m.year || m.period || m.date || m.time_frame || m.duration || m.start_date || m.completion_date || '',
            date: m.date || m.period || m.year || m.start_date || m.completion_date || '',
            start_date: m.start_date || m.startDate || m.period || m.year || '',
            completion_date: m.completion_date || m.completionDate || m.period || m.year || '',
            company: m.company || m.organization || m.subtitle || '',
            company_ar: m.company_ar || m.companyAr || '',
            description: m.description || '',
            description_ar: m.description_ar || m.descriptionAr || '',
            color: m.color || '#d4af37',
            badge: m.badge || '',
            badge_ar: m.badge_ar || m.badgeAr || ''
          }))
        }
      }
    }
    toastMessage.value = 'About section & Career Trajectory saved successfully.'
    isErrorToast.value = false

    notifySync()
    emit('stats-updated')
  } catch (err) {
    toastMessage.value = adminApi.formatApiError(err, 'Failed to save About section.')
    isErrorToast.value = true
  } finally {
    isSaving.value = false
    setTimeout(() => { toastMessage.value = '' }, 5000)
  }
}
</script>

