<template>
  <AppLayout>
    <div class="pddikti-container admin-pusat-bg">
      <h1 class="page-title">Integrasi PDDIKTI</h1>

      <div class="info-card card">
        <h2>Sinkronisasi Data</h2>
        <p>Sinkronisasi data mahasiswa dan dosen ke sistem PDDIKTI.</p>
        
        <div class="actions">
          <button @click="syncAllMahasiswa" class="btn btn-primary">
            Sinkronisasi Semua Mahasiswa
          </button>
          <button @click="checkStatus" class="btn btn-secondary">
            Cek Status
          </button>
        </div>
      </div>

      <div v-if="syncStatus" class="status-card card">
        <h3>Status Terakhir</h3>
        <p>Terakhir sinkron: {{ formatDate(syncStatus.last_sync) }}</p>
        <p>Status: {{ syncStatus.status }}</p>
        <p>Total tersinkron: {{ syncStatus.total_synced }}</p>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import pddiktiService from '../../services/pddikti'

const syncStatus = ref<any>(null)

function formatDate(date: string) {
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

async function syncAllMahasiswa() {
  try {
    await pddiktiService.syncAllMahasiswa()
    alert('Sinkronisasi dimulai')
    checkStatus()
  } catch (error) {
    console.error('Error syncing:', error)
  }
}

async function checkStatus() {
  try {
    const response = await pddiktiService.getSyncStatus()
    syncStatus.value = response.data
  } catch (error) {
    console.error('Error checking status:', error)
  }
}

onMounted(() => {
  checkStatus()
})
</script>

<style scoped>
.pddikti-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.pddikti-container.admin-pusat-bg {
  background-image: url('/images/2.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.pddikti-container.admin-pusat-bg::before {
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

.pddikti-container.admin-pusat-bg > * {
  position: relative;
  z-index: 1;
}

.info-card {
  margin-bottom: var(--spacing-xl);
}

.actions {
  display: flex;
  gap: var(--spacing-md);
  margin-top: var(--spacing-lg);
}

.status-card {
  margin-top: var(--spacing-xl);
}
</style>
