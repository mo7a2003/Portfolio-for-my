<template>
  <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden bg-[#030712]">
    <!-- Three.js Canvas -->
    <canvas ref="canvasRef" class="w-full h-full block opacity-75"></canvas>
    
    <!-- Seamless Global Ambient Lighting Mesh (Replaces disconnected section boxes) -->
    <div class="absolute inset-0 pointer-events-none ambient-mesh-glow"></div>

    <!-- Cinematic Vignette & Deep Backdrop Edge Blending -->
    <div class="absolute inset-0 bg-radial-vignette pointer-events-none"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import * as THREE from 'three'

const canvasRef = ref(null)

let scene, camera, renderer, animationFrameId
let particleSystem, gridMesh
let mouseX = 0, mouseY = 0
let targetMouseX = 0, targetMouseY = 0
let windowHalfX = window.innerWidth / 2
let windowHalfY = window.innerHeight / 2

const initThree = () => {
  if (!canvasRef.value) return

  try {
    const width = window.innerWidth
    const height = window.innerHeight

    scene = new THREE.Scene()
    scene.fog = new THREE.FogExp2(0x030712, 0.0018)

    camera = new THREE.PerspectiveCamera(60, width / height, 1, 3000)
    camera.position.z = 1000
    camera.position.y = 200

    renderer = new THREE.WebGLRenderer({
      canvas: canvasRef.value,
      alpha: true,
      antialias: true,
      powerPreference: 'high-performance'
    })
    renderer.setSize(width, height)
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5))

  // 1. Interactive Ambient Cyber Particle Field (Gold & Cyan)
  const particleCount = 700
  const geometry = new THREE.BufferGeometry()
  const positions = new Float32Array(particleCount * 3)
  const colors = new Float32Array(particleCount * 3)

  const colorGold = new THREE.Color(0xd4af37)
  const colorCyan = new THREE.Color(0x00f0ff)
  const colorAmber = new THREE.Color(0xf59e0b)

  for (let i = 0; i < particleCount; i++) {
    const i3 = i * 3
    positions[i3] = (Math.random() - 0.5) * 2600
    positions[i3 + 1] = (Math.random() - 0.5) * 1600 + 100
    positions[i3 + 2] = (Math.random() - 0.5) * 2200

    const chosenColor = i % 3 === 0 ? colorGold : (i % 3 === 1 ? colorCyan : colorAmber)
    colors[i3] = chosenColor.r
    colors[i3 + 1] = chosenColor.g
    colors[i3 + 2] = chosenColor.b
  }

  geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3))
  geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3))

  const material = new THREE.PointsMaterial({
    size: 3.5,
    vertexColors: true,
    transparent: true,
    opacity: 0.55,
    blending: THREE.AdditiveBlending,
    depthWrite: false
  })

  particleSystem = new THREE.Points(geometry, material)
  scene.add(particleSystem)

  // 2. Subtle Cyberpunk Digital Wave Grid (Flowing in bottom space)
  const gridSegmentsX = 40
  const gridSegmentsY = 40
  const planeGeo = new THREE.PlaneGeometry(2800, 2400, gridSegmentsX, gridSegmentsY)
  planeGeo.rotateX(-Math.PI / 2.3)
  planeGeo.translate(0, -320, -200)

  const wireMat = new THREE.MeshBasicMaterial({
    color: 0x00f0ff,
    wireframe: true,
    transparent: true,
    opacity: 0.045,
    blending: THREE.AdditiveBlending
  })

  gridMesh = new THREE.Mesh(planeGeo, wireMat)
  scene.add(gridMesh)

  // Window Resize & Mouse Listeners
  window.addEventListener('resize', onWindowResize, { passive: true })
  window.addEventListener('mousemove', onMouseMove, { passive: true })

  // Animation Loop
  let clock = new THREE.Clock()
  const animate = () => {
    animationFrameId = requestAnimationFrame(animate)
    const elapsedTime = clock.getElapsedTime()

    // Smooth Lerp Mouse Parallax
    mouseX += (targetMouseX - mouseX) * 0.04
    mouseY += (targetMouseY - mouseY) * 0.04

    camera.position.x = mouseX * 0.4
    camera.position.y = 200 + (-mouseY * 0.25)
    camera.lookAt(0, 0, 0)

    // Gentle Particle Drift
    if (particleSystem) {
      particleSystem.rotation.y = elapsedTime * 0.02
      particleSystem.rotation.x = Math.sin(elapsedTime * 0.015) * 0.03
    }

    // Dynamic Wave Undulation on Grid Vertices
    if (gridMesh) {
      const posAttr = gridMesh.geometry.attributes.position
      for (let i = 0; i < posAttr.count; i++) {
        const u = i % (gridSegmentsX + 1)
        const v = Math.floor(i / (gridSegmentsX + 1))
        const wave = Math.sin(elapsedTime * 0.8 + u * 0.25 + v * 0.15) * 12
        posAttr.setY(i, -320 + wave)
      }
      posAttr.needsUpdate = true
    }

    renderer.render(scene, camera)
  }

  animate()
  } catch (err) {
    console.warn('Three.js WebGL canvas initialization bypassed:', err)
  }
}

const onMouseMove = (event) => {
  targetMouseX = event.clientX - windowHalfX
  targetMouseY = event.clientY - windowHalfY
}

const onWindowResize = () => {
  if (!canvasRef.value || !renderer || !camera) return
  windowHalfX = window.innerWidth / 2
  windowHalfY = window.innerHeight / 2
  camera.aspect = window.innerWidth / window.innerHeight
  camera.updateProjectionMatrix()
  renderer.setSize(window.innerWidth, window.innerHeight)
}

onMounted(() => {
  initThree()
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', onWindowResize)
  window.removeEventListener('mousemove', onMouseMove)
  if (animationFrameId) cancelAnimationFrame(animationFrameId)
  if (renderer) renderer.dispose()
})
</script>

<style scoped>
.ambient-mesh-glow {
  background: 
    radial-gradient(ellipse 65% 50% at 15% 18%, rgba(212, 175, 55, 0.055) 0%, transparent 68%),
    radial-gradient(ellipse 55% 45% at 85% 42%, rgba(0, 240, 255, 0.045) 0%, transparent 68%),
    radial-gradient(ellipse 65% 50% at 20% 72%, rgba(59, 130, 246, 0.04) 0%, transparent 68%),
    radial-gradient(ellipse 60% 40% at 80% 92%, rgba(212, 175, 55, 0.045) 0%, transparent 68%);
}

.bg-radial-vignette {
  background: radial-gradient(circle at 50% 50%, transparent 45%, rgba(3, 7, 18, 0.88) 100%);
}
</style>

