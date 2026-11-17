import apiClient, { API_BASE_URL, BACKEND_ORIGIN } from './apiClient'
import { personalInfo, projects as defaultProjects, skills as defaultSkills, defaultCertificates, defaultSocialLinks } from '../data/portfolioData'
import { profileState, setProfileState, resolveProfileAvatar, resolveProfilePortrait } from './profileState'

export function resolveAssetUrl(url) {
  if (!url || typeof url !== 'string') return ''
  let trimmed = url.trim()
  if (!trimmed) return ''

  // Rewrite any lingering localhost / 127.0.0.1 backend URLs to the production Render origin
  if (/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?\//i.test(trimmed)) {
    trimmed = trimmed.replace(/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/i, BACKEND_ORIGIN)
  }

  if (trimmed.startsWith('http://') || trimmed.startsWith('https://') || trimmed.startsWith('data:') || trimmed.startsWith('blob:')) {
    return trimmed
  }
  if (trimmed.startsWith('/storage/') || trimmed.startsWith('storage/') || trimmed.startsWith('public/')) {
    const normalizedPath = trimmed.replace(/^public\//, 'storage/')
    const clean = normalizedPath.startsWith('/') ? normalizedPath : `/${normalizedPath}`
    return `${BACKEND_ORIGIN}${clean}`
  }
  return trimmed
}

/**
 * Helper to convert Laravel Collection objects (e.g. {"0": {...}, "1": {...}}) into arrays
 */
function coerceToArray(val) {
  if (!val) return null
  if (Array.isArray(val)) return val
  if (typeof val === 'object') {
    const keys = Object.keys(val)
    if (keys.length > 0 && keys.every(k => !isNaN(Number(k)))) {
      return Object.values(val)
    }
  }
  return null
}

/**
 * Universal array extractor to handle all Laravel API response structures:
 * - Direct array: [ {...} ]
 * - Non-consecutive Eloquent Collection object: { "0": {...}, "2": {...} }
 * - Resource Collection: { data: [ {...} ] }
 * - Paginated Collection: { data: [ {...} ], current_page: 1 }
 * - Keyed object: { projects: [ {...} ] } or { data: { projects: [ {...} ] } }
 */
export function extractArray(res, preferredKey = null) {
  if (!res) return []

  const direct = coerceToArray(res)
  if (direct) return direct

  if (preferredKey) {
    const keyAliases = {
      projects: ['featured_projects', 'featuredProjects', 'projects', 'portfolio_projects', 'portfolioProjects'],
      skills: ['skills', 'skills_list', 'skill_list', 'skills_by_category', 'technologies'],
      certificates: ['certificates', 'awards', 'certifications', 'honors'],
      social_links: ['social_links', 'socialLinks', 'links', 'socials'],
      articles: ['articles', 'posts', 'blogs']
    }

    const checkKeys = keyAliases[preferredKey] || [preferredKey]

    for (const k of checkKeys) {
      const match = coerceToArray(res[k]) || 
                    coerceToArray(res[k]?.data) || 
                    coerceToArray(res.data?.[k]) || 
                    coerceToArray(res.data?.[k]?.data)
      if (match && match.length > 0) return match
    }

    // Check res.data directly if it's an array
    const fromData = coerceToArray(res.data)
    if (fromData) return fromData

    return []
  }

  const fromData = coerceToArray(res.data)
  if (fromData) return fromData

  if (res.data) {
    const fromNestedData = coerceToArray(res.data.data)
    if (fromNestedData) return fromNestedData
  }

  for (const key of ['featured_projects', 'featuredProjects', 'projects', 'skills', 'social_links', 'socialLinks', 'articles', 'items', 'results']) {
    const candidate = coerceToArray(res[key]) || coerceToArray(res.data?.[key])
    if (candidate) return candidate
  }

  return []
}

export function normalizeSkill(s) {
  if (!s || typeof s !== 'object') return null
  const rawIcon = s.icon || s.image || s.icon_url || s.image_url || s.logo || ''
  const resolvedIcon = resolveAssetUrl(rawIcon)

  return {
    ...s,
    id: s.id || s._id || `skill_${s.name}`,
    name: s.name || '',
    category: s.category || 'General',
    level: s.level || s.proficiency || 'Expert',
    proficiency: s.level || s.proficiency || 'Expert',
    color: s.color || '#ff5500',
    description: s.description || '',
    icon: resolvedIcon || rawIcon,
    image: resolvedIcon || rawIcon,
    rawIcon: rawIcon
  }
}

/**
 * Normalize a project model from the database to ensure all public templates
 * have consistent properties.
 */
export function normalizeProject(p) {
  if (!p || typeof p !== 'object') return null

  // 1. Image URL resolution
  const rawImage = p.image || p.thumbnail_url || p.thumbnailUrl || p.thumbnail || p.preview_image || p.previewImage || p.image_url || p.imageUrl || ''
  const resolvedImage = resolveAssetUrl(rawImage)

  // 2. Tech stack normalization (array of strings)
  let tech = p.tech || p.technologies || p.tech_stack || []
  if (typeof tech === 'string') {
    try {
      tech = JSON.parse(tech)
    } catch {
      tech = tech.split(',').map(s => s.trim()).filter(Boolean)
    }
  }
  if (!Array.isArray(tech)) tech = []

  // 3. Metrics normalization
  let metrics = p.metrics || []
  if (typeof metrics === 'string') {
    try {
      metrics = JSON.parse(metrics)
    } catch {
      metrics = []
    }
  }
  if (!Array.isArray(metrics)) metrics = []

  // 4. Features list normalization
  let features = p.features || []
  if (typeof features === 'string') {
    try {
      features = JSON.parse(features)
    } catch {
      features = []
    }
  }
  if (!Array.isArray(features)) features = []

  const id = p.id || p.slug || p._id || `proj_${Date.now()}`

  return {
    ...p,
    id: id,
    title: p.title || p.name || 'Untitled Showcase',
    subtitle: p.subtitle || p.pitch || p.tagline || '',
    tagline: p.tagline || p.subtitle || p.pitch || '',
    description: p.description || p.full_description || p.summary || '',
    category: p.category || 'Web Application',
    year: p.year ? p.year.toString() : new Date().getFullYear().toString(),
    status: p.status || (p.is_active !== false && p.isActive !== false ? 'active' : 'completed'),
    isActive: p.isActive !== false && p.is_active !== false && p.status !== 'draft',
    is_active: p.isActive !== false && p.is_active !== false && p.status !== 'draft',
    image: resolvedImage || '/projects/wasla-preview.svg',
    thumbnail: resolvedImage || '/projects/wasla-preview.svg',
    thumbnail_url: resolvedImage || '/projects/wasla-preview.svg',
    previewImage: resolvedImage || '/projects/wasla-preview.svg',
    preview_image: resolvedImage || '/projects/wasla-preview.svg',
    liveUrl: p.liveUrl || p.live_url || p.demo_url || p.url || '',
    live_url: p.liveUrl || p.live_url || p.demo_url || p.url || '',
    githubUrl: p.githubUrl || p.github_url || p.repo_url || '',
    github_url: p.githubUrl || p.github_url || p.repo_url || '',
    accentColor: p.accentColor || p.accent_color || '#ff5500',
    accent_color: p.accentColor || p.accent_color || '#ff5500',
    badge: p.badge || 'Featured Showcase',
    badgeColor: p.badgeColor || p.badge_color || 'border-amber-500/40 text-amber-300 bg-amber-500/10',
    badge_color: p.badgeColor || p.badge_color || 'border-amber-500/40 text-amber-300 bg-amber-500/10',
    tech: tech,
    metrics: metrics,
    features: features
  }
}

export const portfolioService = {
  /**
   * Fetch all curated public projects:
   * Matches Laravel routes/api.php: GET /api/projects
   */
  async getProjects() {
    try {
      const res = await apiClient.get('/projects')
      let list = extractArray(res, 'projects')

      // Fallback: If public endpoint returned empty and admin is signed in, check /admin/projects
      if (!list.length && localStorage.getItem('auth_token')) {
        try {
          const adminRes = await apiClient.get('/admin/projects')
          list = extractArray(adminRes, 'projects')
        } catch (adminErr) {
          console.warn('[portfolioService.getProjects] Admin fallback note:', adminErr.message)
        }
      }

      const normalized = list.map(normalizeProject).filter(Boolean)
      if (normalized.length) {
        try {
          localStorage.setItem('cached_portfolio_projects', JSON.stringify(normalized))
        } catch {}
      }
      return normalized
    } catch (err) {
      console.error('[portfolioService.getProjects] Failed to fetch /projects:', err.message)
      try {
        const cached = localStorage.getItem('cached_portfolio_projects')
        if (cached) return JSON.parse(cached)
      } catch {}
      return defaultProjects.map(normalizeProject)
    }
  },

  async fetchProjects() {
    return this.getProjects()
  },

  /**
   * Fetch comprehensive public portfolio payload:
   * Matches Laravel routes/api.php:
   * 1. GET /api/portfolio (PortfolioController@show)
   * 2. GET /api/projects (ProjectController@index) if projects not embedded in /portfolio
   * Avoids firing redundant requests that could block single-threaded `php artisan serve`.
   */
  async getPortfolio() {
    try {
      // 1. Primary request to GET /api/portfolio
      const pData = await apiClient.get('/portfolio')
      const unwrappedPortfolio = pData?.data && !Array.isArray(pData.data) ? pData.data : (pData || {})

      // 2. Extract Projects from /portfolio or fetch from GET /api/projects
      let rawProjects = extractArray(
        unwrappedPortfolio.featured_projects || 
        unwrappedPortfolio.featuredProjects || 
        unwrappedPortfolio.projects || 
        unwrappedPortfolio, 
        'projects'
      )
      if (!rawProjects.length) {
        try {
          const prData = await apiClient.get('/projects')
          rawProjects = extractArray(prData, 'projects')
        } catch (projErr) {
          console.warn('[portfolioService.getPortfolio] Secondary /projects fetch note:', projErr.message)
        }
      }

      // If still empty and admin token exists, check GET /api/admin/projects
      if (!rawProjects.length && localStorage.getItem('auth_token')) {
        try {
          const adminRes = await apiClient.get('/admin/projects')
          rawProjects = extractArray(adminRes, 'projects')
        } catch {}
      }

      const normalizedProjects = rawProjects.map(normalizeProject).filter(Boolean)
      if (normalizedProjects.length) {
        try {
          localStorage.setItem('cached_portfolio_projects', JSON.stringify(normalizedProjects))
        } catch {}
      }

      // 3. Extract Skills from /portfolio (or GET /api/admin/skills if authenticated)
      let rawSkills = extractArray(unwrappedPortfolio.skills || unwrappedPortfolio, 'skills')
      if (!rawSkills.length && localStorage.getItem('auth_token')) {
        try {
          const adminSkills = await apiClient.get('/admin/skills')
          rawSkills = extractArray(adminSkills, 'skills')
        } catch {}
      }

      const normalizedSkills = rawSkills.map(normalizeSkill).filter(Boolean)
      if (normalizedSkills.length) {
        try {
          localStorage.setItem('cached_portfolio_skills', JSON.stringify(normalizedSkills))
        } catch {}
      }

      // 4. Extract Social Links (from API -> localStorage -> defaultSocialLinks)
      let rawSocial = extractArray(unwrappedPortfolio.social_links || unwrappedPortfolio.socialLinks, 'social_links')
      if (!rawSocial.length) {
        try {
          const cachedSoc = localStorage.getItem('cached_portfolio_social_links')
          if (cachedSoc) rawSocial = JSON.parse(cachedSoc)
        } catch {}
      } else {
        try {
          localStorage.setItem('cached_portfolio_social_links', JSON.stringify(rawSocial))
        } catch {}
      }

      // 4B. Extract Certificates & Awards (from API -> localStorage -> defaultCertificates)
      let rawCertificates = extractArray(
        unwrappedPortfolio.certificates || unwrappedPortfolio.awards || unwrappedPortfolio,
        'certificates'
      )
      if (!rawCertificates.length) {
        try {
          const cachedCerts = localStorage.getItem('cached_portfolio_certificates')
          if (cachedCerts) rawCertificates = JSON.parse(cachedCerts)
        } catch {}
      } else {
        try {
          localStorage.setItem('cached_portfolio_certificates', JSON.stringify(rawCertificates))
        } catch {}
      }

      // 5. Merge Profile with cached admin updates & global reactive profileState
      const apiProfile = unwrappedPortfolio.profile || unwrappedPortfolio.personal_info || {}
      let cachedProfile = {}
      try {
        const cached = localStorage.getItem('cached_portfolio_profile')
        if (cached) cachedProfile = JSON.parse(cached)
      } catch {}

      let mergedProfile = {
        ...personalInfo,
        ...cachedProfile,
        ...(typeof apiProfile === 'object' ? apiProfile : {})
      }

      // Preserve custom uploaded portrait/avatar and user-edited milestones/philosophy from admin cache if public endpoint omitted them
      if (Array.isArray(cachedProfile?.milestones) && cachedProfile.milestones.length) {
        const apiMs = Array.isArray(apiProfile?.milestones) ? apiProfile.milestones : (Array.isArray(apiProfile?.timeline) ? apiProfile.timeline : null)
        if (!apiMs || !apiMs.length) {
          mergedProfile.milestones = cachedProfile.milestones
          mergedProfile.timeline = cachedProfile.milestones
        } else {
          mergedProfile.milestones = apiMs.map((m, idx) => {
            const c = cachedProfile.milestones.find((cm) => (cm.id && m.id && String(cm.id) === String(m.id)) || (cm.title && m.title && cm.title === m.title)) || cachedProfile.milestones[idx] || {}
            const periodVal = m.period || m.year || m.date || m.time_frame || c.period || c.year || ''
            return {
              ...c,
              ...m,
              period: periodVal,
              year: periodVal,
              date: periodVal,
              company: m.company || m.organization || m.subtitle || c.company || ''
            }
          })
          mergedProfile.timeline = mergedProfile.milestones
        }
      }
      const defaultAssets = ['/mohammed-portrait.png', '/developer-portrait.jpg', '/mohammed-avatar.jpg']
      const apiPortrait = apiProfile?.portrait_url || apiProfile?.portrait_image || apiProfile?.portrait
      const cachedPortrait = cachedProfile?.portrait_url || cachedProfile?.portrait_image || cachedProfile?.portrait
      if ((!apiPortrait || defaultAssets.includes(apiPortrait)) && cachedPortrait && !defaultAssets.includes(cachedPortrait)) {
        mergedProfile.portrait = cachedPortrait
        mergedProfile.portrait_image = cachedPortrait
        mergedProfile.portrait_url = cachedPortrait
      }

      const apiAvatar = apiProfile?.avatar_url || apiProfile?.avatar
      const cachedAvatar = cachedProfile?.avatar_url || cachedProfile?.avatar
      if ((!apiAvatar || defaultAssets.includes(apiAvatar)) && cachedAvatar && !defaultAssets.includes(cachedAvatar)) {
        mergedProfile.avatar = cachedAvatar
        mergedProfile.avatar_url = cachedAvatar
      }

      // Merge active CV info if present
      try {
        const cachedCv = localStorage.getItem('alhwaiti_active_cv')
        if (cachedCv) {
          const parsedCv = JSON.parse(cachedCv)
          mergedProfile = {
            ...(mergedProfile || {}),
            cv: parsedCv.url || mergedProfile?.cv,
            cv_url: parsedCv.url || mergedProfile?.cv_url,
            cv_name: parsedCv.filename || mergedProfile?.cv_name,
            cv_size: parsedCv.size || mergedProfile?.cv_size,
            cv_updated_at: parsedCv.updatedAt || mergedProfile?.cv_updated_at
          }
        }
      } catch {}

      // Sync global reactive profileState
      const syncedProfile = setProfileState(mergedProfile, { broadcast: false })

      return {
        profile: { ...syncedProfile },
        projects: normalizedProjects,
        skills: normalizedSkills,
        certificates: rawCertificates.length ? rawCertificates : defaultCertificates,
        socialLinks: rawSocial.length ? rawSocial : defaultSocialLinks,
        stats: Array.isArray(unwrappedPortfolio.stats)
          ? unwrappedPortfolio.stats
          : (mergedProfile?.stats || personalInfo.stats || [])
      }
    } catch (err) {
      console.error('[portfolioService.getPortfolio] Error fetching /portfolio:', err.message)

      // Recover from localStorage cache or portfolioData defaults so UI never hangs empty
      let cachedProjects = []
      let cachedSkills = []
      let cachedCertificates = []
      let cachedSocialLinks = []
      let cachedProfile = personalInfo

      try {
        const cp = localStorage.getItem('cached_portfolio_projects')
        if (cp) cachedProjects = JSON.parse(cp)
      } catch {}

      try {
        const cs = localStorage.getItem('cached_portfolio_skills')
        if (cs) cachedSkills = JSON.parse(cs)
      } catch {}

      try {
        const cc = localStorage.getItem('cached_portfolio_certificates')
        if (cc) cachedCertificates = JSON.parse(cc)
      } catch {}

      try {
        const csoc = localStorage.getItem('cached_portfolio_social_links')
        if (csoc) cachedSocialLinks = JSON.parse(csoc)
      } catch {}

      try {
        const cprof = localStorage.getItem('cached_portfolio_profile')
        if (cprof) cachedProfile = { ...personalInfo, ...JSON.parse(cprof) }
      } catch {}

      return {
        profile: cachedProfile,
        projects: cachedProjects.length ? cachedProjects : defaultProjects.map(normalizeProject),
        skills: cachedSkills.length ? cachedSkills : defaultSkills.map(normalizeSkill),
        certificates: cachedCertificates.length ? cachedCertificates : defaultCertificates,
        socialLinks: cachedSocialLinks.length ? cachedSocialLinks : defaultSocialLinks,
        stats: cachedProfile.stats || personalInfo.stats || []
      }
    }
  },

  /**
   * Fetch single project by ID or slug:
   * Matches Laravel routes/api.php: GET /api/projects/{slugOrId}
   */
  async getProject(slugOrId) {
    try {
      const res = await apiClient.get(`/projects/${slugOrId}`)
      const raw = res?.data || res
      return normalizeProject(raw)
    } catch (err) {
      console.error(`[portfolioService.getProject] Error loading /projects/${slugOrId}:`, err.message)
      return null
    }
  },

  /**
   * Submit client inquiry via public contact form:
   * Matches Laravel routes/api.php: POST /api/contact
   */
  async submitContactForm(payload) {
    return await apiClient.post('/contact', payload)
  },

  /**
   * Public CV Download Action:
   * 1. Calls GET /api/cv/download (with responseType: 'blob')
   * 2. Fallback to direct URL or storage path if needed
   */
  async downloadCv(fallbackUrl = null) {
    let cachedCv = null
    try {
      const stored = localStorage.getItem('alhwaiti_active_cv')
      if (stored) cachedCv = JSON.parse(stored)
    } catch {}

    const targetUrl = fallbackUrl || cachedCv?.url || ''

    try {
      const res = await apiClient.get('/cv/download', {
        responseType: 'blob'
      })

      const blob = new Blob([res.data || res], {
        type: res.headers?.['content-type'] || 'application/pdf'
      })

      let filename = cachedCv?.filename || 'Mohammed_Alhwaiti_CV.pdf'
      const disposition = res.headers?.['content-disposition']
      if (disposition && disposition.includes('filename=')) {
        const matches = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/)
        if (matches && matches[1]) {
          filename = matches[1].replace(/['"]/g, '')
        }
      }

      const blobUrl = window.URL.createObjectURL(blob)
      const downloadAnchor = document.createElement('a')
      downloadAnchor.href = blobUrl
      downloadAnchor.setAttribute('download', filename)
      document.body.appendChild(downloadAnchor)
      downloadAnchor.click()
      document.body.removeChild(downloadAnchor)
      window.URL.revokeObjectURL(blobUrl)

      return { success: true, method: 'blob', filename }
    } catch (err) {
      console.warn('[portfolioService.downloadCv] Using direct asset fallback:', err.message)
      const resolved = resolveAssetUrl(targetUrl) || `${BACKEND_ORIGIN}/storage/cv/Mohammed_Alhwaiti_CV.pdf`

      const directAnchor = document.createElement('a')
      directAnchor.href = resolved
      directAnchor.target = '_blank'
      directAnchor.setAttribute('download', cachedCv?.filename || 'Mohammed_Alhwaiti_CV.pdf')
      document.body.appendChild(directAnchor)
      directAnchor.click()
      document.body.removeChild(directAnchor)

      return { success: true, method: 'direct', url: resolved }
    }
  }
}

export default portfolioService
