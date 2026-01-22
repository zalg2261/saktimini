<template>
  <AppLayout>
    <div class="mahasiswa-detail-container">
      <div class="page-header">
        <h1 class="page-title">Detail Mahasiswa</h1>
        <div class="header-actions">
          <router-link :to="`/mahasiswa/${id}/edit`" class="btn btn-primary">Edit</router-link>
          <button @click="handleDelete" class="btn btn-danger" :disabled="loading">
            Hapus
          </button>
        </div>
      </div>

      <div v-if="loading && !mahasiswa" class="card">
        <p>Memuat data...</p>
      </div>

      <div v-else-if="mahasiswa" class="card">
        <div class="detail-grid">
          <div class="detail-item">
            <span class="detail-label">NIM</span>
            <span class="detail-value">{{ mahasiswa.nim }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Nama</span>
            <span class="detail-value">{{ mahasiswa.nama }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Email</span>
            <span class="detail-value">{{ mahasiswa.email }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Program Studi</span>
            <span class="detail-value">{{ mahasiswa.prodi }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Angkatan</span>
            <span class="detail-value">{{ mahasiswa.angkatan }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Status</span>
            <span :class="getStatusBadgeClass(mahasiswa.status)">
              {{ getStatusLabel(mahasiswa.status) }}
            </span>
          </div>
        </div>
      </div>

      <div v-if="errorMessage" class="error-message card">
        {{ errorMessage }}
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import AppLayout from '../components/AppLayout.vue'
import mahasiswaService, { type Mahasiswa } from '../services/mahasiswa'

const router = useRouter()
const route = useRoute()

const id = parseInt(route.params.id as string)
const mahasiswa = ref<Mahasiswa | null>(null)
const loading = ref(false)
const errorMessage = ref('')

function getStatusLabel(status: string) {
  const labels: Record<string, string> = {
    aktif: 'Aktif',
    cuti: 'Cuti',
    lulus: 'Lulus',
    dropout: 'Dropout',
  }
  return labels[status] || status
}

function getStatusBadgeClass(status: string) {
  const classes: Record<string, string> = {
    aktif: 'badge badge-success',
    cuti: 'badge badge-warning',
    lulus: 'badge badge-info',
    dropout: 'badge badge-danger',
  }
  return classes[status] || 'badge'
}

async function loadMahasiswa() {
  loading.value = true
  errorMessage.value = ''
  try {
    const response = await mahasiswaService.getById(id)
    mahasiswa.value = response.data
  } catch (error: any) {
    errorMessage.value = error.response?.data?.message || 'Gagal memuat data mahasiswa'
  } finally {
    loading.value = false
  }
}

async function handleDelete() {
  if (!confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')) {
    return
  }

  loading.value = true
  try {
    await mahasiswaService.delete(id)
    router.push('/mahasiswa')
  } catch (error: any) {
    errorMessage.value = error.response?.data?.message || 'Gagal menghapus mahasiswa'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadMahasiswa()
})
</script>

<style scoped>
.mahasiswa-detail-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.mahasiswa-detail-container.admin-prodi-bg {
  background-image: url('/images/5.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.mahasiswa-detail-container.admin-prodi-bg::before {
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

.mahasiswa-detail-container.admin-prodi-bg > * {
  position: relative;
  z-index: 1;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-xl);
}

.page-title {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  font-weight: 600;
  color: var(--color-primary);
}

.header-actions {
  display: flex;
  gap: var(--spacing-md);
}

.detail-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: var(--spacing-lg);
}

.detail-item {
  display: flex;
  flex-direction: column;
  padding: var(--spacing-md) 0;
  border-bottom: 1px solid var(--color-border);
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-label {
  font-weight: 600;
  color: var(--color-text-secondary);
  margin-bottom: var(--spacing-xs);
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.detail-value {
  color: var(--color-text-primary);
  font-size: 1.125rem;
}

.error-message {
  background-color: #f8d7da;
  color: #721c24;
  padding: var(--spacing-md);
  border-radius: var(--radius-md);
}
</style>
