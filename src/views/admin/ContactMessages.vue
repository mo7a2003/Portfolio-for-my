<template>
  <div class="space-y-6">
    
    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 border border-gold-500/30 text-xs font-mono text-gold-400 mb-2">
          <Mail class="w-3.5 h-3.5 text-gold-400" />
          <span>Admin\ContactMessageController Endpoints</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
          Client Inquiries & Messages
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 font-mono mt-1">
          Review incoming leads, 3D WebGL project requests and consultation inquiries
        </p>
      </div>

      <!-- Bulk Actions -->
      <div class="flex items-center gap-3">
        <button
          @click="markAllAsRead"
          :disabled="unreadCount === 0"
          class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900/80 hover:bg-slate-800 border border-slate-800 hover:border-gold-500/40 text-xs font-mono text-slate-300 hover:text-gold-300 transition-all cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
        >
          <CheckCheck class="w-4 h-4 text-gold-400" />
          <span>Mark All as Read</span>
        </button>
      </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="backdrop-blur-xl bg-slate-950/60 border border-slate-800 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
      
      <!-- Search Input -->
      <div class="relative w-full md:w-80">
        <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search by sender, email or subject..."
          class="w-full bg-slate-900/90 border border-slate-800 focus:border-gold-400 text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-2.5 text-xs font-mono outline-none transition-all"
        />
      </div>

      <!-- Status Filter Tabs -->
      <div class="flex items-center gap-2">
        <button
          v-for="filter in ['all', 'unread', 'read']"
          :key="filter"
          @click="statusFilter = filter"
          class="px-3.5 py-1.5 rounded-xl text-xs font-mono uppercase tracking-wider transition-all cursor-pointer"
          :class="statusFilter === filter 
            ? 'bg-gold-500/20 text-gold-300 border border-gold-500/40 shadow-[0_0_12px_rgba(212,175,55,0.25)]' 
            : 'bg-slate-900/60 text-slate-400 hover:text-slate-200 border border-slate-800'"
        >
          {{ filter }}
          <span v-if="filter === 'unread' && unreadCount > 0" class="ml-1 text-[10px] text-gold-400 font-bold">
            ({{ unreadCount }})
          </span>
        </button>
      </div>

    </div>

    <!-- Toast Alert -->
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
        class="p-4 rounded-xl bg-emerald-950/70 border border-emerald-500/40 text-emerald-200 text-xs font-mono flex items-center justify-between shadow-[0_0_20px_rgba(16,185,129,0.2)]"
      >
        <div class="flex items-center gap-2">
          <CheckCircle2 class="w-4 h-4 text-emerald-400 shrink-0" />
          <span>{{ toastMessage }}</span>
        </div>
        <button @click="toastMessage = ''" class="text-emerald-400 hover:text-white">
          <X class="w-3.5 h-3.5" />
        </button>
      </div>
    </transition>

    <!-- Inquiries List / Table -->
    <div class="backdrop-blur-2xl bg-slate-950/60 border border-slate-800 rounded-2xl p-4 sm:p-6 shadow-xl">
      <div class="space-y-3">
        
        <div
          v-for="msg in filteredMessages"
          :key="msg.id"
          @click="openMessageDetail(msg)"
          class="p-4 rounded-xl border transition-all duration-200 cursor-pointer flex flex-col md:flex-row md:items-center justify-between gap-4 group"
          :class="!msg.is_read 
            ? 'bg-slate-900/80 border-gold-500/40 shadow-[0_0_20px_rgba(212,175,55,0.12)]' 
            : 'bg-slate-950/40 border-slate-800/80 hover:bg-slate-900/40 hover:border-slate-700'"
        >
          <!-- Left: Status beacon & Sender Info -->
          <div class="flex items-start sm:items-center gap-3.5">
            <span 
              class="w-2.5 h-2.5 rounded-full mt-1 sm:mt-0 shrink-0"
              :class="!msg.is_read ? 'bg-gold-400 shadow-[0_0_8px_rgba(212,175,55,0.9)] animate-pulse' : 'bg-slate-600'"
              title="Read/Unread status"
            ></span>

            <div>
              <div class="flex items-center gap-2.5">
                <span class="font-bold text-sm text-white group-hover:text-gold-300 transition-colors">
                  {{ msg.name }}
                </span>
                <span class="text-xs font-mono text-slate-400">
                  &lt;{{ msg.email }}&gt;
                </span>
              </div>
              <div class="text-xs font-semibold text-slate-200 mt-0.5 line-clamp-1">
                {{ msg.subject }}
              </div>
              <div class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">
                {{ msg.message }}
              </div>
            </div>
          </div>

          <!-- Right: Date & Actions -->
          <div class="flex items-center justify-between md:justify-end gap-4 shrink-0 pt-2 md:pt-0 border-t md:border-0 border-slate-800/60" @click.stop>
            <div class="text-[11px] font-mono text-slate-400">
              {{ formatTime(msg.created_at) }}
            </div>

            <div class="flex items-center gap-1.5">
              <!-- Toggle Read Status -->
              <button
                @click="toggleReadStatus(msg)"
                class="p-2 rounded-lg bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-gold-500/30 text-slate-400 hover:text-gold-300 transition-colors cursor-pointer"
                :title="msg.is_read ? 'Mark as Unread' : 'Mark as Read'"
              >
                <MailCheck v-if="msg.is_read" class="w-4 h-4" />
                <Mail v-else class="w-4 h-4 text-gold-400" />
              </button>

              <!-- View Details -->
              <button
                @click="openMessageDetail(msg)"
                class="p-2 rounded-lg bg-gold-500/10 hover:bg-gold-500/20 border border-gold-500/30 text-gold-300 transition-colors cursor-pointer"
                title="View Full Message"
              >
                <Eye class="w-4 h-4" />
              </button>

              <!-- Delete Message -->
              <button
                @click="confirmDelete(msg)"
                class="p-2 rounded-lg bg-red-950/30 hover:bg-red-950/60 border border-red-500/20 text-red-400 hover:text-red-200 transition-colors cursor-pointer"
                title="Delete Message"
              >
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>

        </div>

      </div>

      <!-- Empty State -->
      <div 
        v-if="filteredMessages.length === 0" 
        class="text-center py-16"
      >
        <Inbox class="w-12 h-12 text-slate-600 mx-auto mb-3" />
        <h3 class="text-sm font-bold text-white">No inquiries found in this view</h3>
        <p class="text-xs text-slate-500 font-mono mt-1">Your inbox is clear or no messages match the search filter</p>
      </div>

    </div>

    <!-- ======================================================== -->
    <!-- Message Detail Glassmorphic Modal / Drawer -->
    <!-- ======================================================== -->
    <div 
      v-if="activeMessage" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
    >
      <div class="relative w-full max-w-2xl bg-[#030712] border border-gold-500/40 rounded-3xl p-6 sm:p-8 shadow-[0_0_60px_rgba(212,175,55,0.25)]">
        
        <!-- Header -->
        <div class="flex items-start justify-between pb-4 border-b border-slate-800 mb-6">
          <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-gold-500/10 border border-gold-500/30 text-[10px] font-mono text-gold-400 mb-2">
              <Mail class="w-3 h-3 text-gold-400" />
              <span>Inquiry #{{ activeMessage.id }}</span>
            </div>
            <h2 class="text-lg sm:text-xl font-bold text-white">
              {{ activeMessage.subject }}
            </h2>
          </div>
          <button @click="activeMessage = null" class="text-slate-400 hover:text-white p-1 rounded-lg cursor-pointer">
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Sender Meta Details -->
        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 space-y-2 text-xs font-mono mb-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 text-slate-300">
            <div>
              <strong class="text-white">From:</strong> {{ activeMessage.name }}
            </div>
            <div class="text-slate-500">
              {{ formatTime(activeMessage.created_at) }}
            </div>
          </div>
          <div class="text-slate-400">
            <strong class="text-slate-300">Email:</strong> 
            <a :href="`mailto:${activeMessage.email}`" class="text-gold-400 hover:text-gold-300 underline ml-1">
              {{ activeMessage.email }}
            </a>
          </div>
          <div v-if="activeMessage.phone" class="text-slate-400">
            <strong class="text-slate-300">Phone:</strong> {{ activeMessage.phone }}
          </div>
        </div>

        <!-- Message Body -->
        <div class="mb-8">
          <label class="block text-[11px] font-mono uppercase text-slate-500 mb-2">Message Body</label>
          <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800/80 text-sm text-slate-200 leading-relaxed whitespace-pre-line">
            {{ activeMessage.message }}
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-800">
          <div class="flex items-center gap-2">
            <button
              @click="toggleReadStatus(activeMessage)"
              class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-xs font-mono text-slate-300 cursor-pointer"
            >
              {{ activeMessage.is_read ? 'Mark as Unread' : 'Mark as Read' }}
            </button>
            <button
              @click="confirmDelete(activeMessage); activeMessage = null"
              class="px-3.5 py-2 rounded-xl bg-red-950/30 hover:bg-red-950/60 border border-red-500/20 text-xs font-mono text-red-400 cursor-pointer"
            >
              Delete
            </button>
          </div>

          <a
            :href="`mailto:${activeMessage.email}?subject=Re: ${encodeURIComponent(activeMessage.subject)}`"
            class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-gradient-to-r from-gold-400 to-amber-600 hover:from-gold-300 hover:to-amber-500 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(212,175,55,0.35)] cursor-pointer"
          >
            <Send class="w-3.5 h-3.5" />
            <span>Send Direct Reply</span>
          </a>
        </div>

      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div 
      v-if="messageToDelete" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
    >
      <div class="relative w-full max-w-md bg-[#030712] border border-red-500/40 rounded-2xl p-6 shadow-[0_0_40px_rgba(239,68,68,0.25)]">
        <h3 class="text-base font-bold text-white mb-2 flex items-center gap-2">
          <AlertCircle class="w-5 h-5 text-red-400" />
          <span>Confirm Message Deletion</span>
        </h3>
        <p class="text-xs text-slate-300 mb-6 font-sans">
          Delete message from <strong class="text-white">{{ messageToDelete.name }}</strong>?
        </p>
        <div class="flex items-center justify-end gap-3 font-mono text-xs">
          <button @click="messageToDelete = null" class="px-4 py-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white">
            Cancel
          </button>
          <button @click="executeDelete" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold">
            Yes, Delete Inquiry
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  Mail, 
  Search, 
  CheckCheck, 
  CheckCircle2, 
  MailCheck, 
  Eye, 
  Trash2, 
  X, 
  Send, 
  Inbox, 
  AlertCircle 
} from 'lucide-vue-next'
import { adminApi } from '../../services/adminApi'

const emit = defineEmits(['stats-updated'])

const messages = ref([])
const searchQuery = ref('')
const statusFilter = ref('all')
const toastMessage = ref('')
const isErrorToast = ref(false)

const activeMessage = ref(null)
const messageToDelete = ref(null)

const loadMessages = async () => {
  try {
    messages.value = await adminApi.getMessages()
  } catch (err) {
    toastMessage.value = adminApi.formatApiError(err, 'Failed to load inquiries.')
    isErrorToast.value = true
  }
}

onMounted(() => {
  loadMessages()
})

const unreadCount = computed(() => {
  return messages.value.filter(m => !m.is_read).length
})

const filteredMessages = computed(() => {
  const query = (searchQuery.value || '').toLowerCase()
  return (messages.value || []).filter(m => {
    const matchesSearch = 
      (m.name || '').toLowerCase().includes(query) ||
      (m.email || '').toLowerCase().includes(query) ||
      (m.subject || '').toLowerCase().includes(query) ||
      (m.message || '').toLowerCase().includes(query)

    let matchesStatus = true
    if (statusFilter.value === 'unread') matchesStatus = !m.is_read
    if (statusFilter.value === 'read') matchesStatus = m.is_read

    return matchesSearch && matchesStatus
  })
})

const formatTime = (isoString) => {
  if (!isoString) return ''
  const date = new Date(isoString)
  return date.toLocaleString('en-US', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const openMessageDetail = async (msg) => {
  activeMessage.value = msg
  if (!msg.is_read) {
    try {
      await adminApi.markMessageAsRead(msg.id, true)
      msg.is_read = true
      emit('stats-updated')
    } catch (err) {
      toastMessage.value = adminApi.formatApiError(err, 'Failed to update read status.')
      isErrorToast.value = true
      setTimeout(() => { toastMessage.value = '' }, 4000)
    }
  }
}

const toggleReadStatus = async (msg) => {
  const newStatus = !msg.is_read
  try {
    await adminApi.markMessageAsRead(msg.id, newStatus)
    msg.is_read = newStatus
    isErrorToast.value = false
    emit('stats-updated')
  } catch (err) {
    toastMessage.value = adminApi.formatApiError(err, 'Failed to update message status.')
    isErrorToast.value = true
    setTimeout(() => { toastMessage.value = '' }, 4000)
  }
}

const markAllAsRead = async () => {
  try {
    await adminApi.markAllMessagesAsRead(messages.value)
    messages.value.forEach(m => m.is_read = true)
    toastMessage.value = 'All messages marked as read.'
    isErrorToast.value = false
    emit('stats-updated')
  } catch (err) {
    toastMessage.value = adminApi.formatApiError(err, 'Failed to mark all messages as read.')
    isErrorToast.value = true
  } finally {
    setTimeout(() => { toastMessage.value = '' }, 4000)
  }
}

const confirmDelete = (msg) => {
  messageToDelete.value = msg
}

const executeDelete = async () => {
  if (!messageToDelete.value) return
  try {
    await adminApi.deleteMessage(messageToDelete.value.id)
    toastMessage.value = `Message from "${messageToDelete.value.name}" deleted.`
    isErrorToast.value = false
    messageToDelete.value = null
    await loadMessages()
    emit('stats-updated')
  } catch (err) {
    toastMessage.value = adminApi.formatApiError(err, 'Failed to delete inquiry.')
    isErrorToast.value = true
    messageToDelete.value = null
  } finally {
    setTimeout(() => { toastMessage.value = '' }, 4000)
  }
}
</script>

