<template>
  <div class="app-layout">
    <!-- Sidebar -->
    <aside class="sidebar" :class="{ collapsed: sidebarCollapsed }">
      <div class="sidebar-header">
        <h2 class="sidebar-logo">SAKTI</h2>
        <button @click="toggleSidebar" class="sidebar-toggle" v-if="!sidebarCollapsed">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 6L16 6M4 10L16 10M4 14L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>
      
      <nav class="sidebar-nav">
        <router-link to="/" class="nav-item" active-class="active">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 10L9 4L17 4V16L9 16L3 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span>Beranda</span>
        </router-link>

        <!-- Admin Prodi & Admin Pusat -->
        <router-link v-if="authStore.isAdminProdi() || authStore.isAdminPusat()" to="/mahasiswa" class="nav-item" active-class="active">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 10C12.7614 10 15 7.76142 15 5C15 2.23858 12.7614 0 10 0C7.23858 0 5 2.23858 5 5C5 7.76142 7.23858 10 10 10Z" stroke="currentColor" stroke-width="2"/>
            <path d="M10 12C5.58172 12 2 14.6863 2 18V20H18V18C18 14.6863 14.4183 12 10 12Z" stroke="currentColor" stroke-width="2"/>
          </svg>
          <span>Mahasiswa</span>
        </router-link>

        <!-- Admin Pusat -->
        <router-link v-if="authStore.isAdminPusat()" to="/dosen" class="nav-item" active-class="active">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 10C12.7614 10 15 7.76142 15 5C15 2.23858 12.7614 0 10 0C7.23858 0 5 2.23858 5 5C5 7.76142 7.23858 10 10 10Z" stroke="currentColor" stroke-width="2"/>
            <path d="M10 12C5.58172 12 2 14.6863 2 18V20H18V18C18 14.6863 14.4183 12 10 12Z" stroke="currentColor" stroke-width="2"/>
          </svg>
          <span>Dosen</span>
        </router-link>

        <!-- Mahasiswa -->
        <template v-if="authStore.isMahasiswa() || authStore.isAdminPusat()">
          <router-link to="/krs" class="nav-item" active-class="active">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2 4H18V16H2V4Z" stroke="currentColor" stroke-width="2"/>
              <path d="M2 8H18" stroke="currentColor" stroke-width="2"/>
            </svg>
            <span>KRS</span>
          </router-link>
          <router-link to="/khs" class="nav-item" active-class="active">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2 4H18V16H2V4Z" stroke="currentColor" stroke-width="2"/>
              <path d="M6 8H14" stroke="currentColor" stroke-width="2"/>
              <path d="M6 12H14" stroke="currentColor" stroke-width="2"/>
            </svg>
            <span>KHS</span>
          </router-link>
          <router-link to="/presensi" class="nav-item" active-class="active">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2"/>
              <path d="M10 6V10L13 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Presensi</span>
          </router-link>
        </template>

        <!-- Dosen -->
        <template v-if="authStore.isDosen() || authStore.isAdminPusat()">
          <router-link to="/penilaian" class="nav-item" active-class="active">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 2L12.09 7.26L18 8.27L14 12.14L14.91 18.02L10 15.77L5.09 18.02L6 12.14L2 8.27L7.91 7.26L10 2Z" stroke="currentColor" stroke-width="2"/>
            </svg>
            <span>Penilaian</span>
          </router-link>
          <router-link to="/materi" class="nav-item" active-class="active">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M4 4H16V16H4V4Z" stroke="currentColor" stroke-width="2"/>
              <path d="M4 8H16" stroke="currentColor" stroke-width="2"/>
              <path d="M8 4V16" stroke="currentColor" stroke-width="2"/>
            </svg>
            <span>Materi</span>
          </router-link>
          <router-link to="/absensi" class="nav-item" active-class="active">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M10 14V18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M2 10H6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M14 10H18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <circle cx="10" cy="10" r="4" stroke="currentColor" stroke-width="2"/>
            </svg>
            <span>Absensi</span>
          </router-link>
        </template>

        <!-- Admin Pusat -->
        <template v-if="authStore.isAdminPusat()">
          <router-link to="/pddikti" class="nav-item" active-class="active">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 2L3 7V17H17V7L10 2Z" stroke="currentColor" stroke-width="2"/>
              <path d="M10 10L7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M13 7L10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>PDDIKTI</span>
          </router-link>
          <router-link to="/ukt" class="nav-item" active-class="active">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2 4H18V16H2V4Z" stroke="currentColor" stroke-width="2"/>
              <path d="M2 8H18" stroke="currentColor" stroke-width="2"/>
              <path d="M6 4V16" stroke="currentColor" stroke-width="2"/>
            </svg>
            <span>Payment UKT</span>
          </router-link>
          <router-link to="/sso" class="nav-item" active-class="active">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2"/>
              <path d="M10 6V10L13 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>SSO</span>
          </router-link>
        </template>

        <router-link to="/profile" class="nav-item" active-class="active">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
            <path d="M5 18C5 14.6863 7.23858 12 10 12C12.7614 12 15 14.6863 15 18" stroke="currentColor" stroke-width="2"/>
          </svg>
          <span>Profil</span>
        </router-link>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-wrapper">
      <!-- Top Header -->
      <header class="top-header">
        <div class="header-left">
          <button @click="toggleSidebar" class="menu-toggle" v-if="sidebarCollapsed">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M3 12H21M3 6H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </button>
          <div class="user-info">
            <span class="user-name">{{ authStore.user?.name }}</span>
            <span class="user-role">{{ getRoleLabel(authStore.user?.role) }}</span>
          </div>
        </div>
        <div class="header-right">
          <button @click="handleLogout" class="btn-logout">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M7 17L2 12M2 12L7 7M2 12H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Keluar</span>
          </button>
        </div>
      </header>

      <!-- Page Content -->
      <main class="app-main">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const sidebarCollapsed = ref(false)

function toggleSidebar() {
  sidebarCollapsed.value = !sidebarCollapsed.value
}

function getRoleLabel(role?: string) {
  const labels: Record<string, string> = {
    admin_prodi: 'Admin Prodi',
    admin_pusat: 'Admin Pusat',
    dosen: 'Dosen',
    mahasiswa: 'Mahasiswa',
  }
  return labels[role || ''] || role
}

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.app-layout {
  display: flex;
  min-height: 100vh;
  background-color: #f5f5f5;
}

/* Sidebar */
.sidebar {
  width: 260px;
  background: var(--color-white);
  border-right: 1px solid #e5e5e5;
  display: flex;
  flex-direction: column;
  transition: width 0.3s ease;
  position: fixed;
  height: 100vh;
  z-index: 100;
}

.sidebar.collapsed {
  width: 80px;
}

.sidebar-header {
  padding: var(--spacing-lg);
  border-bottom: 1px solid #e5e5e5;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.sidebar-logo {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--color-primary);
  margin: 0;
}

.sidebar-toggle {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--color-text-muted);
  padding: var(--spacing-xs);
  display: flex;
  align-items: center;
  justify-content: center;
}

.sidebar-toggle:hover {
  color: var(--color-primary);
}

.sidebar-nav {
  flex: 1;
  padding: var(--spacing-md) 0;
  overflow-y: auto;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: var(--spacing-md);
  padding: var(--spacing-md) var(--spacing-lg);
  color: var(--color-text-primary);
  text-decoration: none;
  transition: all 0.2s;
  border-left: 3px solid transparent;
}

.nav-item:hover {
  background-color: #f9f9f9;
  color: var(--color-primary);
}

.nav-item.active {
  background-color: #f0f7ff;
  color: var(--color-primary);
  border-left-color: var(--color-primary);
  font-weight: 600;
}

.nav-item svg {
  flex-shrink: 0;
}

.sidebar.collapsed .nav-item span {
  display: none;
}

.sidebar.collapsed .sidebar-logo {
  font-size: 1.2rem;
}

/* Main Wrapper */
.main-wrapper {
  flex: 1;
  margin-left: 260px;
  display: flex;
  flex-direction: column;
  transition: margin-left 0.3s ease;
}

.sidebar.collapsed ~ .main-wrapper {
  margin-left: 80px;
}

/* Top Header */
.top-header {
  background: var(--color-white);
  border-bottom: 1px solid #e5e5e5;
  padding: var(--spacing-md) var(--spacing-xl);
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 10;
}

.header-left {
  display: flex;
  align-items: center;
  gap: var(--spacing-lg);
}

.menu-toggle {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--color-text-muted);
  padding: var(--spacing-xs);
  display: flex;
  align-items: center;
  justify-content: center;
}

.menu-toggle:hover {
  color: var(--color-primary);
}

.user-info {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 600;
  color: var(--color-text-primary);
  font-size: 0.95rem;
}

.user-role {
  font-size: 0.875rem;
  color: var(--color-text-muted);
}

.header-right {
  display: flex;
  align-items: center;
  gap: var(--spacing-md);
}

.btn-logout {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  padding: var(--spacing-sm) var(--spacing-md);
  background: none;
  border: 1px solid #e5e5e5;
  border-radius: var(--radius-md);
  color: var(--color-text-primary);
  cursor: pointer;
  font-size: 0.875rem;
  transition: all 0.2s;
}

.btn-logout:hover {
  background-color: #f9f9f9;
  border-color: var(--color-primary);
  color: var(--color-primary);
}

/* Main Content */
.app-main {
  flex: 1;
  padding: var(--spacing-xl);
  overflow-y: auto;
}

/* Responsive */
@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }
  
  .sidebar.collapsed {
    transform: translateX(0);
  }
  
  .main-wrapper {
    margin-left: 0;
  }
  
  .sidebar.collapsed ~ .main-wrapper {
    margin-left: 0;
  }
}
</style>
