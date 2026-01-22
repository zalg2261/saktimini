<template>
  <AppLayout>
    <div class="profile-container" :class="{
      'admin-pusat-bg': authStore.isAdminPusat(),
      'admin-prodi-bg': authStore.isAdminProdi(),
      'dosen-bg': authStore.isDosen(),
      'mahasiswa-bg': authStore.isMahasiswa()
    }">
      <h1 class="page-title">Profil Saya</h1>
      
      <div class="card">
        <div class="profile-info">
          <div class="info-row">
            <span class="info-label">Nama:</span>
            <span class="info-value">{{ authStore.user?.name }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ authStore.user?.email }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Role:</span>
            <span class="info-value">{{ getRoleName(authStore.user?.role) }}</span>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useAuthStore } from '../stores/auth'
import AppLayout from '../components/AppLayout.vue'

const authStore = useAuthStore()

function getRoleName(role?: string) {
  const roles: Record<string, string> = {
    admin_prodi: 'Admin Prodi',
    admin_pusat: 'Admin Pusat',
    dosen: 'Dosen',
    mahasiswa: 'Mahasiswa',
  }
  return roles[role || ''] || role
}
</script>

<style scoped>
.profile-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.profile-container.admin-pusat-bg {
  background-image: url('/images/2.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.profile-container.admin-pusat-bg::before {
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

.profile-container.admin-pusat-bg > * {
  position: relative;
  z-index: 1;
}

.profile-container.admin-prodi-bg {
  background-image: url('/images/5.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.profile-container.admin-prodi-bg::before {
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

.profile-container.admin-prodi-bg > * {
  position: relative;
  z-index: 1;
}

.profile-container.dosen-bg {
  background-image: url('/images/4.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.profile-container.dosen-bg::before {
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

.profile-container.dosen-bg > * {
  position: relative;
  z-index: 1;
}

.profile-container.mahasiswa-bg {
  background-image: url('/images/3.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.profile-container.mahasiswa-bg::before {
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

.profile-container.mahasiswa-bg > * {
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

.profile-info {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-lg);
}

.info-row {
  display: flex;
  padding: var(--spacing-md) 0;
  border-bottom: 1px solid var(--color-border);
}

.info-row:last-child {
  border-bottom: none;
}

.info-label {
  font-weight: 600;
  color: var(--color-text-secondary);
  min-width: 120px;
}

.info-value {
  color: var(--color-text-primary);
}
</style>
