import { reactive } from 'vue'
import { personalInfo } from '../data/portfolioData'
import { BACKEND_ORIGIN } from './apiClient'

export function resolveProfileAssetUrl(url) {
  if (!url || typeof url !== 'string') return ''
  let trimmed = url.trim()
  if (!trimmed) return ''

  // Rewrite any lingering localhost / 127.0.0.1 backend URLs to the production Render origin
  if (/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?\//i.test(trimmed)) {
    trimmed = trimmed.replace(/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/i, BACKEND_ORIGIN)
  }

  // Force HTTPS on all non-localhost URLs to prevent Mixed Content warnings
  if (trimmed.startsWith('http://') && !/^(http:\/\/(localhost|127\.0\.0\.1))/i.test(trimmed)) {
    trimmed = trimmed.replace(/^http:\/\//i, 'https://')
  }

  if (
    trimmed.startsWith('http://') ||
    trimmed.startsWith('https://') ||
    trimmed.startsWith('data:') ||
    trimmed.startsWith('blob:')
  ) {
    return trimmed
  }
  if (trimmed.startsWith('/storage/') || trimmed.startsWith('storage/') || trimmed.startsWith('public/')) {
    const normalizedPath = trimmed.replace(/^public\//, 'storage/')
    const clean = normalizedPath.startsWith('/') ? normalizedPath : `/${normalizedPath}`
    return `${BACKEND_ORIGIN}${clean}`
  }
  return trimmed
}

const DEFAULT_PORTRAITS = ['/mohammed-portrait.png', '/developer-portrait.jpg', '/mohammed-avatar.jpg']

/**
 * Resolve the primary Hero 3D portrait URL from a profile object
 */
export function resolveProfilePortrait(profile = {}) {
  const p = profile || {}
  const customCandidates = [
    p.portrait_url,
    p.portrait_image,
    p.portrait,
    p.hero_image,
    p.avatar_url,
    p.avatar,
    p.image
  ].filter(Boolean)

  // Prefer a non-default uploaded image first if available
  const uploaded = customCandidates.find((u) => !DEFAULT_PORTRAITS.includes(u))
  if (uploaded) {
    return resolveProfileAssetUrl(uploaded)
  }

  const firstValid = customCandidates[0]
  if (firstValid) {
    return resolveProfileAssetUrl(firstValid)
  }
  return ''
}

/**
 * Resolve the Navbar / About avatar URL from a profile object
 */
export function resolveProfileAvatar(profile = {}) {
  const p = profile || {}
  const customCandidates = [
    p.avatar_url,
    p.avatar,
    p.portrait_url,
    p.portrait_image,
    p.portrait,
    p.image
  ].filter(Boolean)

  // Prefer a non-default uploaded image first if available
  const uploaded = customCandidates.find((u) => !DEFAULT_PORTRAITS.includes(u))
  if (uploaded) {
    return resolveProfileAssetUrl(uploaded)
  }

  const firstValid = customCandidates[0]
  if (firstValid) {
    return resolveProfileAssetUrl(firstValid)
  }
  return ''
}

function loadInitialProfile() {
  let cached = {}
  try {
    const raw = localStorage.getItem('cached_portfolio_profile')
    if (raw) cached = JSON.parse(raw)
  } catch {}

  const merged = { ...personalInfo, ...cached }
  const portrait = resolveProfilePortrait(merged)
  const avatar = resolveProfileAvatar(merged)

  return {
    ...merged,
    portrait,
    portrait_image: portrait,
    portrait_url: portrait,
    avatar,
    avatar_url: avatar
  }
}

/**
 * Global reactive profile state shared across Admin Dashboard and Public Portfolio
 */
export const profileState = reactive(loadInitialProfile())

/**
 * Update global reactive profileState, persist to localStorage, and broadcast sync events
 */
export function setProfileState(newProfile = {}, options = { broadcast: true }) {
  if (!newProfile || typeof newProfile !== 'object') return profileState

  const merged = {
    ...personalInfo,
    ...profileState,
    ...newProfile
  }

  // If the incoming update explicitly provided a new portrait or avatar, prioritize it
  const explicitPortrait =
    newProfile.portrait_url ||
    newProfile.portrait_image ||
    newProfile.portrait ||
    null
  const explicitAvatar =
    newProfile.avatar_url ||
    newProfile.avatar ||
    null

  const resolvedPortrait = explicitPortrait
    ? resolveProfileAssetUrl(explicitPortrait)
    : resolveProfilePortrait(merged)

  const resolvedAvatar = explicitAvatar
    ? resolveProfileAssetUrl(explicitAvatar)
    : resolveProfileAvatar({ ...merged, portrait: resolvedPortrait })

  merged.portrait = resolvedPortrait || '/mohammed-portrait.png'
  merged.portrait_image = merged.portrait
  merged.portrait_url = merged.portrait
  merged.avatar = resolvedAvatar || merged.portrait || '/developer-portrait.jpg'
  merged.avatar_url = merged.avatar

  Object.assign(profileState, merged)

  try {
    localStorage.setItem('cached_portfolio_profile', JSON.stringify({ ...profileState }))
    if (options.broadcast) {
      localStorage.setItem('portfolio_sync_trigger', Date.now().toString())
      if (typeof window !== 'undefined') {
        window.dispatchEvent(new CustomEvent('portfolio-updated', { detail: { ...profileState } }))
      }
    }
  } catch {}

  return profileState
}

// Listen for cross-tab storage updates so Public Portfolio updates immediately when Admin saves in another tab
if (typeof window !== 'undefined') {
  window.addEventListener('storage', (event) => {
    if (event.key === 'cached_portfolio_profile' && event.newValue) {
      try {
        const parsed = JSON.parse(event.newValue)
        setProfileState(parsed, { broadcast: false })
      } catch {}
    }
  })
}
