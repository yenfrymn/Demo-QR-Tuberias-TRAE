import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  { path: '/', name: 'home', component: () => import('../views/HomeView.vue') },
  { path: '/health', name: 'health', component: () => import('../views/HealthView.vue') },
  
  // Admin Pipeline Routes
  { path: '/admin/pipelines', name: 'admin-pipelines', component: () => import('../views/admin/AdminPipelinesList.vue') },
  { path: '/admin/pipelines/new', name: 'admin-pipelines-new', component: () => import('../views/admin/AdminPipelinesNew.vue') },
  { path: '/admin/pipelines/:id/edit', name: 'admin-pipelines-edit', component: () => import('../views/admin/AdminPipelinesEdit.vue') },
  
  // Admin Company Routes
  { path: '/admin/companies', name: 'admin-companies', component: () => import('../views/admin/AdminCompaniesList.vue') },
  { path: '/admin/companies/new', name: 'admin-companies-new', component: () => import('../views/admin/AdminCompaniesNew.vue') },
  { path: '/admin/companies/:id/edit', name: 'admin-companies-edit', component: () => import('../views/admin/AdminCompaniesEdit.vue') },
  
  // Admin Certification Routes
  { path: '/admin/certifications', name: 'admin-certifications', component: () => import('../views/admin/AdminCertificationsList.vue') },
  { path: '/admin/certifications/new', name: 'admin-certifications-new', component: () => import('../views/admin/AdminCertificationsNew.vue') },
  
  // Admin Blueprint Routes
  { path: '/admin/blueprints', name: 'admin-blueprints', component: () => import('../views/admin/AdminBlueprintsList.vue') },
  { path: '/admin/blueprints/new', name: 'admin-blueprints-new', component: () => import('../views/admin/AdminBlueprintsNew.vue') },
  
  // Admin License Routes
  { path: '/admin/licenses', name: 'admin-licenses', component: () => import('../views/admin/AdminLicensesList.vue') },
  { path: '/admin/licenses/new', name: 'admin-licenses-new', component: () => import('../views/admin/AdminLicensesNew.vue') },
  
  { path: '/login', name: 'login', component: () => import('../views/LoginView.vue') },
  { path: '/scan', name: 'scan', component: () => import('../views/ScanView.vue') },
  { path: '/pipelines/:id', name: 'pipeline-detail', component: () => import('../views/PipelineDetailView.vue') },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {
  const auth = useAuthStore()
  const adminRoutes = to.path.startsWith('/admin')
  if (adminRoutes && !['admin','editor'].includes(auth.role)) {
    return { name: 'login' }
  }
})

export default router