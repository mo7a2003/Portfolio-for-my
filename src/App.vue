<template>
  <div class="min-h-screen bg-[#030712] text-slate-100 selection:bg-gold-500 selection:text-black relative">
    
    <!-- Global Interactive 3D Cyber Particle & Horizon Canvas -->
    <CyberBackgroundCanvas />

    <!-- Custom Cyber Ambient Cursor Light Follower -->
    <div 
      class="fixed w-96 h-96 rounded-full pointer-events-none transition-transform duration-150 ease-out z-0 opacity-20 blur-3xl hidden md:block"
      :style="{
        left: `${mousePos.x - 192}px`,
        top: `${mousePos.y - 192}px`,
        background: 'radial-gradient(circle, rgba(212,175,55,0.4) 0%, rgba(0,240,255,0.2) 50%, transparent 70%)'
      }"
    ></div>

    <!-- Router View for Public Portfolio & Admin Views -->
    <router-view />

  </div>
</template>

<script setup>
import { reactive, onMounted, onBeforeUnmount } from 'vue'
import CyberBackgroundCanvas from './components/CyberBackgroundCanvas.vue'

const mousePos = reactive({ x: -200, y: -200 })

const onMouseMove = (e) => {
  mousePos.x = e.clientX
  mousePos.y = e.clientY
}

onMounted(() => {
  window.addEventListener('mousemove', onMouseMove)
})

onBeforeUnmount(() => {
  window.removeEventListener('mousemove', onMouseMove)
})
</script>
