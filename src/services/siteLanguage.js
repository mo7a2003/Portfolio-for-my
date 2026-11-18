import { ref, computed } from 'vue'

export const lang = ref(localStorage.getItem('portfolio_lang') || 'en')

export const isRtl = computed(() => lang.value === 'ar')

export const toggleLang = () => {
  lang.value = lang.value === 'en' ? 'ar' : 'en'
  localStorage.setItem('portfolio_lang', lang.value)
  if (typeof document !== 'undefined') {
    document.documentElement.dir = isRtl.value ? 'rtl' : 'ltr'
    document.documentElement.lang = lang.value
  }
}

export const setLang = (newLang) => {
  if (newLang === 'ar' || newLang === 'en') {
    lang.value = newLang
    localStorage.setItem('portfolio_lang', lang.value)
    if (typeof document !== 'undefined') {
      document.documentElement.dir = isRtl.value ? 'rtl' : 'ltr'
      document.documentElement.lang = lang.value
    }
  }
}

/**
 * Universal dynamic extractor for bilingual fields from backend API
 * e.g. getLocalized(project, 'title') -> project.title_ar in AR mode, project.title in EN mode
 */
export function getLocalized(item, field = 'title', fallback = '') {
  if (!item || typeof item !== 'object') return fallback
  if (lang.value === 'ar') {
    const val = item[`${field}_ar`] ?? item[`${field}Ar`] ?? item[field]
    return val !== undefined && val !== null && String(val).trim() !== '' ? val : fallback
  }
  const val = item[field] ?? item[`${field}_en`] ?? item[`${field}En`]
  return val !== undefined && val !== null && String(val).trim() !== '' ? val : fallback
}

const dictionary = {
  en: {
    // Nav
    navHome: 'Home',
    navAbout: 'About',
    navSkills: 'Skills',
    navProjects: 'Projects',
    navCertificates: 'Certificates',
    navBlog: 'Articles',
    navContact: 'Contact',
    navConnect: "Let's Connect",
    switchLanguage: 'عربي',
    langCode: 'AR',
    langLabel: 'عربي',

    // Hero
    gazaLocation: 'GAZA, PS',
    availableFor: 'AVAILABLE FOR ELITE 3D & FRONTEND ARCHITECTURE',
    titleDefault: 'WEB DESIGNER & FRONT-END ARCHITECT',
    exploreWorks: 'Explore Selected Works',
    downloadCv: 'Download CV',
    preparingCv: 'Preparing CV...',
    downloadedCv: 'Downloaded ✓',
    channels: 'CHANNELS:',
    featuredWork: 'FEATURED WORK',
    featuredCase: 'FEATURED CASE',
    threeDArchitect: '3D ARCHITECT',
    viewProject: 'View Project',
    scrollToExplore: 'SCROLL TO EXPLORE',

    // Hero Stats
    statExperience: 'Years Experience',
    statProjects: 'Major Projects',
    statAwards: 'Awards Won',
    statClients: 'Happy Clients',

    // Projects Section
    projectsTitle: 'Selected',
    projectsTitleHighlight: 'Projects',
    projectsSubtitle: '// CURATED 3D & FRONT-END CASE STUDIES',
    xrayActive: '● X-RAY BLUEPRINT: ACTIVE',
    autoFlipRunning: '● 3D AUTO-FLIP: RUNNING',
    autoFlipPaused: '● 3D AUTO-FLIP: PAUSED',
    inspectCase: 'Inspect Case →',
    catAll: 'All',

    // Skills Section
    skillsTitle: 'Skills &',
    skillsTitleHighlight: 'Technologies',
    skillsSubtitle: '// 3D SPATIAL SKILLS MATRIX',

    // Certificates Section
    certTitle: 'Certifications &',
    certTitleHighlight: 'Honors Vault',
    certSubtitle: '// VERIFIED ACADEMIC & TECHNICAL CREDENTIALS',
    certVerified: '● CRYPTOGRAPHICALLY VERIFIED',

    // About Section
    aboutTitle: 'About',
    aboutSubtitle: '// THE ARCHITECT BEHIND THE CODE',
    careerTrajectory: 'Career Trajectory & Milestones',

    // Contact Section
    contactBadge: '// INITIATE TRANSMISSION',
    contactTitle: "Let's Build Something",
    contactTitleHighlight: 'Monumental',
    nameLabel: 'Your Name *',
    emailLabel: 'Email Address *',
    subjectLabel: 'Subject / Project Scope *',
    messageLabel: 'Message Transmission *',
    sendMessage: 'Send Message 🚀',
    sendingMessage: 'Transmitting...',
    messageSent: 'Transmission sent! Mohammed will reply shortly.',

    // Common
    liveDemo: 'Live Demo ↗',
    sourceCode: 'Source Code',
    backToTop: 'Back to top ↑',
    allRightsReserved: 'All rights reserved.',
    builtWith: 'Engineered with Vue 3, Tailwind & Three.js'
  },
  ar: {
    // Nav
    navHome: 'الرئيسية',
    navAbout: 'من أنا',
    navSkills: 'المهارات',
    navProjects: 'المشاريع',
    navCertificates: 'الشهادات',
    navBlog: 'المقالات',
    navContact: 'اتصل بي',
    navConnect: 'تواصل معي',
    switchLanguage: 'English',
    langCode: 'EN',
    langLabel: 'English',

    // Hero
    gazaLocation: 'غزة، فلسطين',
    availableFor: 'متاح للمشاريع وتطوير الواجهات ثلاثية الأبعاد المتقدمة',
    titleDefault: 'مصمم مواقع ومهندس واجهات أمامية 3D',
    exploreWorks: 'استكشف الأعمال المختارة',
    downloadCv: 'تحميل السيرة الذاتية',
    preparingCv: 'جاري تجهيز السيرة...',
    downloadedCv: 'تم التحميل ✓',
    channels: 'قنوات التواصل:',
    featuredWork: 'أحدث عمل مميز',
    featuredCase: 'مشروع مميز',
    threeDArchitect: 'مهندس واجهات ثلاثية الأبعاد',
    viewProject: 'عرض المشروع',
    scrollToExplore: 'مرر للاستكشاف',

    // Hero Stats
    statExperience: 'سنوات الخبرة',
    statProjects: 'مشاريع كبرى',
    statAwards: 'جوائز وتكريمات',
    statClients: 'عملاء راضون',

    // Projects Section
    projectsTitle: 'الأعمال',
    projectsTitleHighlight: 'المختارة',
    projectsSubtitle: '// دراسات حالة تفاعلية وتطبيقات ويب 3D',
    xrayActive: '● مخطط الأشعة X-RAY: نشط',
    autoFlipRunning: '● التدوير التلقائي: يعمل',
    autoFlipPaused: '● التدوير التلقائي: متوقف',
    inspectCase: 'معاينة المشروع ←',
    catAll: 'الكل',

    // Skills Section
    skillsTitle: 'المهارات و',
    skillsTitleHighlight: 'التقنيات',
    skillsSubtitle: '// المصفوفة البرمجية ثلاثية الأبعاد',

    // Certificates Section
    certTitle: 'الشهادات و',
    certTitleHighlight: 'التكريمات المعتمدة',
    certSubtitle: '// اعتمادات أكاديمية وتقنية موثقة',
    certVerified: '● توثيق رقمي معتمد',

    // About Section
    aboutTitle: 'عن',
    aboutSubtitle: '// المهندس خلف الأكواد البرمجية',
    careerTrajectory: 'المسار المهني والمحطات البارزة',

    // Contact Section
    contactBadge: '// بدء الاتصال المباشر',
    contactTitle: 'دعنا نبني معاً مشروعاً',
    contactTitleHighlight: 'استثنائياً',
    nameLabel: 'الاسم الكامل *',
    emailLabel: 'البريد الإلكتروني *',
    subjectLabel: 'موضوع الرسالة / نطاق المشروع *',
    messageLabel: 'نص الرسالة *',
    sendMessage: 'إرسال الرسالة 🚀',
    sendingMessage: 'جاري الإرسال...',
    messageSent: 'تم إرسال الرسالة بنجاح! سيتم الرد عليك قريباً.',

    // Common
    liveDemo: 'المعاينة الحية ↗',
    sourceCode: 'الكود المصدري',
    backToTop: 'العودة للأعلى ↑',
    allRightsReserved: 'جميع الحقوق محفوظة.',
    builtWith: 'تمت الهندسة باستخدام Vue 3 و Tailwind و Three.js'
  }
}

export const t = computed(() => dictionary[lang.value] || dictionary.en)
