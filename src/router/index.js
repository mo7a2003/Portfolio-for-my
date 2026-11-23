import { createRouter, createWebHistory } from 'vue-router'
import PortfolioView from '../views/PortfolioView.vue'
import AdminLogin from '../views/AdminLogin.vue'
import AdminLayout from '../views/admin/AdminLayout.vue'
import Overview from '../views/admin/Overview.vue'
import Projects from '../views/admin/Projects.vue'
import Skills from '../views/admin/Skills.vue'
import Certificates from '../views/admin/Certificates.vue'
import SocialLinks from '../views/admin/SocialLinks.vue'
import ContactMessages from '../views/admin/ContactMessages.vue'
import ProfileSettings from '../views/admin/ProfileSettings.vue'
import AboutSettings from '../views/admin/AboutSettings.vue'

const routes = [
  {
    path: '/',
    name: 'Portfolio',
    component: PortfolioView,
    meta: { title: 'Mohammed Alhwaiti | Senior Frontend Engineer & UI/UX Architect' }
  },
  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: AdminLogin,
    meta: { title: 'Admin Login | Mohammed Alhwaiti' }
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        redirect: '/admin/overview'
      },
      {
        path: 'dashboard',
        redirect: '/admin/overview'
      },
      {
        path: 'overview',
        name: 'AdminOverview',
        component: Overview,
        meta: { title: 'Overview & Telemetry | Alhwaiti Admin V22' }
      },
      {
        path: 'projects',
        name: 'AdminProjects',
        component: Projects,
        meta: { title: 'Projects Management | Alhwaiti Admin V22' }
      },
      {
        path: 'skills',
        name: 'AdminSkills',
        component: Skills,
        meta: { title: 'Skills & Capabilities | Alhwaiti Admin V22' }
      },
      {
        path: 'certificates',
        name: 'AdminCertificates',
        component: Certificates,
        meta: { title: 'Certificates & Awards | Alhwaiti Admin V22' }
      },
      {
        path: 'articles',
        name: 'AdminArticles',
        component: () => import('../views/admin/Articles.vue'),
        meta: { title: 'Articles & Insights | Alhwaiti Admin V22' }
      },
      {
        path: 'about',
        name: 'AdminAbout',
        component: AboutSettings,
        meta: { title: 'About & Experience Studio | Alhwaiti Admin V22' }
      },
      {
        path: 'social-links',
        name: 'AdminSocialLinks',
        component: SocialLinks,
        meta: { title: 'Social Connectivity | Alhwaiti Admin V22' }
      },
      {
        path: 'messages',
        name: 'AdminMessages',
        component: ContactMessages,
        meta: { title: 'Inquiries Center | Alhwaiti Admin V22' }
      },
      {
        path: 'profile',
        name: 'AdminProfile',
        component: ProfileSettings,
        meta: { title: 'Profile Settings | Alhwaiti Admin V22' }
      }
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }
    if (to.hash) {
      return { el: to.hash, behavior: 'smooth' }
    }
    return { top: 0 }
  }
})

// Global Navigation Guard for Admin Routes Protection
router.beforeEach((to, from, next) => {
  // Update document title if specified in route meta
  if (to.meta && to.meta.title) {
    document.title = to.meta.title
  }

  const token = localStorage.getItem('auth_token')
  const isAdminRoute = to.path.startsWith('/admin')
  const isLoginPage = to.path === '/admin/login'

  // Check if target is a protected admin route or requiresAuth
  if ((isAdminRoute && !isLoginPage) || to.matched.some(record => record.meta && record.meta.requiresAuth)) {
    if (!token) {
      // Automatically redirect unauthenticated visitor to /admin/login with redirect query param
      next({
        path: '/admin/login',
        query: { redirect: to.fullPath }
      })
      return
    }
  }

  // If user is already authenticated and attempts to visit the login page, redirect to overview
  if (isLoginPage && token) {
    next('/admin/overview')
    return
  }

  next()
})

export default router
