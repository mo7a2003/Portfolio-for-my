import { ref, computed } from 'vue'

export const lang = ref(localStorage.getItem('admin_lang') || 'en')
export const isSidebarCollapsed = ref(localStorage.getItem('admin_sidebar_collapsed') === 'true')

export const isRtl = computed(() => lang.value === 'ar')

export const toggleLang = () => {
  lang.value = lang.value === 'en' ? 'ar' : 'en'
  localStorage.setItem('admin_lang', lang.value)
}

export const toggleSidebar = () => {
  isSidebarCollapsed.value = !isSidebarCollapsed.value
  localStorage.setItem('admin_sidebar_collapsed', String(isSidebarCollapsed.value))
}

const dictionary = {
  en: {
    brandTitle: 'ALHWAITI CORE',
    sanctumActive: 'Laravel Sanctum Active',
    coreAdminHeader: 'Core Administration',
    collapseSidebar: 'Collapse Sidebar',
    expandSidebar: 'Expand Sidebar',
    adminBreadcrumb: 'Admin V22',
    sanctumStatus: 'Sanctum API: 200 OK',
    viewLiveSite: 'View Live Site',
    publicBadge: 'Public',
    disconnectSession: 'Disconnect Session',
    exitBadge: 'Exit',
    newBadge: 'New',

    // Navigation Items
    navOverview: 'Overview & Health',
    navProjects: 'Projects Suite',
    navSkills: 'Skills Matrix',
    navCertificates: 'Certificates & Awards',
    navArticles: 'Articles & Insights',
    navAbout: 'About & Experience',
    navSocial: 'Social & Contact Hub',
    navMessages: 'Inquiries Center',
    navProfile: 'Profile Settings',

    // Overview Header
    commandCenterBadge: 'Dashboard V22 // Command Center',
    overviewTitle: 'System Overview & Telemetry',
    overviewSubtitle: 'Real-time status for Mohammed Alhwaiti Portfolio Platform — 3D Neon-Eclipse & X-Ray Engine',
    newProjectBtn: '+ New Project',
    syncDataBtn: 'Sync Data',
    syncingBtn: 'Syncing...',

    // Microservices Matrix Banner
    matrixTitle: 'Full Stack Microservices Matrix',
    operationalBadge: 'Operational',
    matrixSubtitle: 'Laravel 11 Sanctum API Gateway • PostgreSQL / Supabase Engine • WebGL X-Ray Shader Pipeline',
    xrayHudLabel: 'X-RAY TELEMETRY // ACTIVE',
    apiLatency: 'API LATENCY',
    uptime: 'UPTIME',
    sanctumAuth: 'SANCTUM AUTH',
    encrypted: 'Encrypted',
    dbCluster: 'DB CLUSTER',
    connected: 'Connected',

    // 4 KPI Bento Cards
    kpiProjectsTitle: 'Showcase Projects',
    kpiProjectsSub: 'Live in Showcase',
    kpiManage: 'Manage →',
    kpiInquiriesTitle: 'Contact Inquiries',
    kpiInquiriesSub: 'Unread Pending',
    kpiInbox: 'Inbox →',
    kpiVisitsTitle: 'Total Page Visits',
    kpiVisitsSub: 'this month',
    kpiAnalytics: 'Analytics',
    kpiFpsTitle: 'WebGL & 3D Framerate',
    kpiFpsSub: 'Lenis + Three.js Sync',
    kpiSmooth: 'Smooth 60Hz',

    // Active Projects Cyber-Bento Table
    activeProjectsHeader: 'Active Showcase Projects (Quick Actions)',
    activeProjectsSub: 'Real-time toggles, X-Ray telemetry & quick access to live portfolio projects',
    openProjectsStudio: 'Open Full Projects Studio',
    colProject: 'PROJECT',
    colCategory: 'CATEGORY',
    colTechStack: 'TECH STACK',
    colStatus: 'STATUS',
    colQuickActions: 'QUICK ACTIONS',
    statusActive: '● Active',
    statusDraft: '○ Draft',
    editBtn: 'Edit',
    previewBtn: 'Preview ↗',
    noProjectsFound: 'No showcase projects found. Click "+ New Project" to add one.'
  },
  ar: {
    brandTitle: 'نواة الحويطي',
    sanctumActive: 'حماية Laravel Sanctum نشطة',
    coreAdminHeader: 'الإدارة الأساسية للنظام',
    collapseSidebar: 'طي القائمة الجانبية',
    expandSidebar: 'توسيع القائمة الجانبية',
    adminBreadcrumb: 'لوحة التحكم V22',
    sanctumStatus: 'واجهة Sanctum: 200 متصلة',
    viewLiveSite: 'عرض الموقع الحي',
    publicBadge: 'عام',
    disconnectSession: 'تسجيل الخروج',
    exitBadge: 'خروج',
    newBadge: 'جديد',

    // Navigation Items
    navOverview: 'نظرة عامة وصحة النظام',
    navProjects: 'إدارة المشاريع',
    navSkills: 'مصفوفة المهارات',
    navCertificates: 'الشهادات والجوائز',
    navArticles: 'إدارة المقالات والنشر',
    navAbout: 'نبذة والخبرات',
    navSocial: 'قنوات التواصل والسوشيال',
    navMessages: 'مركز الرسائل',
    navProfile: 'إعدادات الحساب',

    // Overview Header
    commandCenterBadge: 'لوحة التحكم V22 // مركز القيادة',
    overviewTitle: 'نظرة عامة ومؤشرات النظام الحية',
    overviewSubtitle: 'الحالة الفورية لمنصة أعمال محمد الحويطي — محرك الكسوف النيون والمسح الهندسي ثلاثي الأبعاد',
    newProjectBtn: '+ مشروع جديد',
    syncDataBtn: 'مزامنة البيانات',
    syncingBtn: 'جاري المزامنة...',

    // Microservices Matrix Banner
    matrixTitle: 'مصفوفة الخدمات المصغرة والربط الشامل',
    operationalBadge: 'يعمل بكفاءة',
    matrixSubtitle: 'بوابة Laravel 11 Sanctum • قاعدة بيانات PostgreSQL / Supabase • معالج رسوميات WebGL X-Ray',
    xrayHudLabel: 'مسح الأشعة السينية // نشط',
    apiLatency: 'زمن استجابة API',
    uptime: 'وقت التشغيل',
    sanctumAuth: 'توثيق SANCTUM',
    encrypted: 'مُشفّر بالكامل',
    dbCluster: 'عنقود البيانات',
    connected: 'متصل',

    // 4 KPI Bento Cards
    kpiProjectsTitle: 'مشاريع المعرض',
    kpiProjectsSub: 'منشور في المعرض',
    kpiManage: 'إدارة ←',
    kpiInquiriesTitle: 'استفسارات التواصل',
    kpiInquiriesSub: 'بانتظار القراءة',
    kpiInbox: 'الوارد ←',
    kpiVisitsTitle: 'إجمالي زيارات المنصة',
    kpiVisitsSub: 'هذا الشهر',
    kpiAnalytics: 'إحصائيات',
    kpiFpsTitle: 'معدل إطارات 3D WebGL',
    kpiFpsSub: 'مزامنة Lenis + Three.js',
    kpiSmooth: 'سلس 60Hz',

    // Active Projects Cyber-Bento Table
    activeProjectsHeader: 'المشاريع النشطة في المعرض (إجراءات سريعة)',
    activeProjectsSub: 'تبديل فوري للحالة ومعاينة حية لمشاريع البورتفوليو المربوطة بقاعدة البيانات',
    openProjectsStudio: 'فتح استوديو المشاريع الكامل',
    colProject: 'المشروع',
    colCategory: 'التصنيف',
    colTechStack: 'التقنيات المستخدمة',
    colStatus: 'الحالة',
    colQuickActions: 'إجراءات سريعة',
    statusActive: '● نشط',
    statusDraft: '○ مسودة',
    editBtn: 'تعديل',
    previewBtn: 'معاينة ↗',
    noProjectsFound: 'لا توجد مشاريع حالياً. اضغط على "+ مشروع جديد" لإضافة أول مشروع.'
  }
}

export const t = computed(() => dictionary[lang.value] || dictionary.en)
