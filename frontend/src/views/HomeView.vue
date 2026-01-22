<template>
  <AppLayout>
    <div class="home-container" :class="{ 
      'admin-pusat-bg': authStore.isAdminPusat(),
      'admin-prodi-bg': authStore.isAdminProdi(),
      'dosen-bg': authStore.isDosen(),
      'mahasiswa-bg': authStore.isMahasiswa()
    }">
      <h1 class="page-title">Dashboard</h1>
      
      <div class="welcome-card card">
        <h3>Selamat Datang, {{ authStore.user?.name }}!</h3>
        <p>Role: <strong>{{ getRoleName(authStore.user?.role) }}</strong></p>
      </div>

      <div class="stats-grid">
        <div v-if="authStore.isAdminProdi()" class="stat-card card">
          <h4>Mahasiswa</h4>
          <p class="stat-value">{{ stats.totalMahasiswa || '-' }}</p>
          <router-link to="/mahasiswa" class="stat-link">Lihat Detail →</router-link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import AppLayout from '../components/AppLayout.vue'
import mahasiswaService from '../services/mahasiswa'

const authStore = useAuthStore()
const stats = ref({
  totalMahasiswa: null as number | null,
})

function getRoleName(role?: string) {
  const roles: Record<string, string> = {
    admin_prodi: 'Admin Prodi',
    admin_pusat: 'Admin Pusat',
    dosen: 'Dosen',
    mahasiswa: 'Mahasiswa',
  }
  return roles[role || ''] || role
}

onMounted(async () => {
  if (authStore.isAdminProdi()) {
    try {
      const response = await mahasiswaService.getList({ per_page: 1 })
      stats.value.totalMahasiswa = response.meta.total
    } catch (error) {
      console.error('Error fetching stats:', error)
    }
  }
})
</script>

<style scoped>
.home-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.home-container.admin-pusat-bg {
  background-image: url('/images/2.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.home-container.admin-pusat-bg::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.85);
  z-index: 0;
  pointer-events: none;
}

.home-container.admin-pusat-bg > * {
  position: relative;
  z-index: 1;
}

.home-container.admin-prodi-bg {
  background-image: url('/images/5.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.home-container.admin-prodi-bg::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.87);
  z-index: 0;
  pointer-events: none;
}

.home-container.admin-prodi-bg > * {
  position: relative;
  z-index: 1;
}

.home-container.dosen-bg {
  background-image: url('/images/4.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.home-container.dosen-bg::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.9);
  z-index: 0;
  pointer-events: none;
}

.home-container.dosen-bg > * {
  position: relative;
  z-index: 1;
}

.home-container.mahasiswa-bg {
  background-image: url('/images/3.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.home-container.mahasiswa-bg::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.88);
  z-index: 0;
  pointer-events: none;
}

.home-container.mahasiswa-bg > * {
  position: relative;
  z-index: 1;
}

.page-title {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  font-weight: 600;
  color: var(--color-primary);
  margin-bottom: var(--spacing-xl);
}

.welcome-card {
  margin-bottom: var(--spacing-xl);
}

.welcome-card h3 {
  font-size: 1.5rem;
  margin-bottom: var(--spacing-sm);
  color: var(--color-primary);
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: var(--spacing-lg);
}

.stat-card h4 {
  font-size: 1rem;
  color: var(--color-text-secondary);
  margin-bottom: var(--spacing-md);
}

.stat-value {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--color-primary);
  margin-bottom: var(--spacing-md);
}

.stat-link {
  color: var(--color-secondary);
  text-decoration: none;
  font-weight: 500;
}

.stat-link:hover {
  text-decoration: underline;
}
</style>
