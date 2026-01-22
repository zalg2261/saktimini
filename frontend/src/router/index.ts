import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { requiresGuest: true },
    },
    {
      path: '/',
      name: 'home',
      component: () => import('../views/HomeView.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('../views/ProfileView.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/mahasiswa',
      name: 'mahasiswa',
      component: () => import('../views/MahasiswaListView.vue'),
      meta: { requiresAuth: true, requiresRole: 'admin_prodi' },
    },
    {
      path: '/mahasiswa/create',
      name: 'mahasiswa-create',
      component: () => import('../views/MahasiswaFormView.vue'),
      meta: { requiresAuth: true, requiresRole: 'admin_prodi' },
    },
    {
      path: '/mahasiswa/:id',
      name: 'mahasiswa-detail',
      component: () => import('../views/MahasiswaDetailView.vue'),
      meta: { requiresAuth: true, requiresRole: 'admin_prodi' },
    },
    {
      path: '/mahasiswa/:id/edit',
      name: 'mahasiswa-edit',
      component: () => import('../views/MahasiswaFormView.vue'),
      meta: { requiresAuth: true, requiresRole: 'admin_prodi' },
    },
    {
      path: '/dosen',
      name: 'dosen',
      component: () => import('../views/AdminPusat/DosenListView.vue'),
      meta: { requiresAuth: true, requiresRole: 'admin_pusat' },
    },
    {
      path: '/dosen/create',
      name: 'dosen-create',
      component: () => import('../views/AdminPusat/DosenFormView.vue'),
      meta: { requiresAuth: true, requiresRole: 'admin_pusat' },
    },
    {
      path: '/dosen/:id',
      name: 'dosen-detail',
      component: () => import('../views/AdminPusat/DosenDetailView.vue'),
      meta: { requiresAuth: true, requiresRole: 'admin_pusat' },
    },
    {
      path: '/dosen/:id/edit',
      name: 'dosen-edit',
      component: () => import('../views/AdminPusat/DosenFormView.vue'),
      meta: { requiresAuth: true, requiresRole: 'admin_pusat' },
    },
    {
      path: '/krs',
      name: 'krs',
      component: () => import('../views/Mahasiswa/KrsView.vue'),
      meta: { requiresAuth: true, requiresRole: 'mahasiswa' },
    },
    {
      path: '/khs',
      name: 'khs',
      component: () => import('../views/Mahasiswa/KhsView.vue'),
      meta: { requiresAuth: true, requiresRole: 'mahasiswa' },
    },
    {
      path: '/presensi',
      name: 'presensi',
      component: () => import('../views/Mahasiswa/PresensiView.vue'),
      meta: { requiresAuth: true, requiresRole: 'mahasiswa' },
    },
    {
      path: '/penilaian',
      name: 'penilaian',
      component: () => import('../views/Dosen/PenilaianView.vue'),
      meta: { requiresAuth: true, requiresRole: 'dosen' },
    },
    {
      path: '/materi',
      name: 'materi',
      component: () => import('../views/Dosen/MateriView.vue'),
      meta: { requiresAuth: true, requiresRole: 'dosen' },
    },
    {
      path: '/absensi',
      name: 'absensi',
      component: () => import('../views/Dosen/AbsensiView.vue'),
      meta: { requiresAuth: true, requiresRole: 'dosen' },
    },
    {
      path: '/pddikti',
      name: 'pddikti',
      component: () => import('../views/AdminPusat/PddiktiView.vue'),
      meta: { requiresAuth: true, requiresRole: 'admin_pusat' },
    },
    {
      path: '/ukt',
      name: 'ukt',
      component: () => import('../views/AdminPusat/UktView.vue'),
      meta: { requiresAuth: true, requiresRole: 'admin_pusat' },
    },
    {
      path: '/sso',
      name: 'sso',
      component: () => import('../views/AdminPusat/SsoView.vue'),
      meta: { requiresAuth: true, requiresRole: 'admin_pusat' },
    },
  ],
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  if (!authStore.isAuthenticated && to.meta.requiresAuth) {
    const isAuth = await authStore.checkAuth()
    if (!isAuth) {
      next({ name: 'login', query: { redirect: to.fullPath } })
      return
    }
  }

  if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next({ name: 'home' })
    return
  }

  if (to.meta.requiresRole) {
    if (!authStore.isAuthenticated) {
      const isAuth = await authStore.checkAuth()
      if (!isAuth) {
        next({ name: 'login', query: { redirect: to.fullPath } })
        return
      }
    }

    const requiredRole = to.meta.requiresRole as string
    
    if (authStore.isAdminPusat()) {
      next()
      return
    }

    if (requiredRole === 'admin_prodi' && !authStore.isAdminProdi()) {
      next({ name: 'home' })
      return
    }
    
    if (requiredRole === 'dosen' && !authStore.isDosen()) {
      next({ name: 'home' })
      return
    }
    
    if (requiredRole === 'mahasiswa' && !authStore.isMahasiswa()) {
      next({ name: 'home' })
      return
    }
    
    if (requiredRole === 'admin_pusat' && !authStore.isAdminPusat()) {
      next({ name: 'home' })
      return
    }
  }

  next()
})

export default router
