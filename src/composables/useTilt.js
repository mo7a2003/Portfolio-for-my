import { ref } from 'vue'

export function useTilt(options = {}) {
  const {
    maxTilt = 12,
    perspective = 1000,
    scale = 1.02,
    speed = 400
  } = options

  const tiltStyle = ref({
    transform: `perspective(${perspective}px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`,
    transition: `transform ${speed}ms cubic-bezier(0.03, 0.98, 0.52, 0.99)`
  })

  const glareStyle = ref({
    opacity: 0,
    background: 'radial-gradient(circle at 50% 50%, rgba(212, 175, 55, 0.25) 0%, transparent 60%)'
  })

  const handleMouseMove = (e) => {
    const card = e.currentTarget
    const rect = card.getBoundingClientRect()
    const x = e.clientX - rect.left
    const y = e.clientY - rect.top

    const centerX = rect.width / 2
    const centerY = rect.height / 2

    const rotateX = ((y - centerY) / centerY) * -maxTilt
    const rotateY = ((x - centerX) / centerX) * maxTilt

    tiltStyle.value = {
      transform: `perspective(${perspective}px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) scale3d(${scale}, ${scale}, ${scale})`,
      transition: 'none'
    }

    glareStyle.value = {
      opacity: 1,
      background: `radial-gradient(circle at ${(x / rect.width) * 100}% ${(y / rect.height) * 100}%, rgba(212, 175, 55, 0.3) 0%, transparent 65%)`
    }
  }

  const handleMouseLeave = () => {
    tiltStyle.value = {
      transform: `perspective(${perspective}px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`,
      transition: `transform ${speed}ms cubic-bezier(0.03, 0.98, 0.52, 0.99)`
    }
    glareStyle.value = {
      opacity: 0,
      background: 'radial-gradient(circle at 50% 50%, rgba(212, 175, 55, 0.2) 0%, transparent 60%)'
    }
  }

  return {
    tiltStyle,
    glareStyle,
    handleMouseMove,
    handleMouseLeave
  }
}

