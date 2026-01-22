<template>
  <AppLayout>
    <div class="dosen-detail-container admin-pusat-bg">
      <div class="page-header">
        <h1 class="page-title">Detail Dosen</h1>
        <div class="header-actions">
          <router-link :to="`/dosen/${dosen?.id}/edit`" class="btn btn-primary">
            Edit
          </router-link>
          <router-link to="/dosen" class="btn btn-secondary">
            ← Kembali
          </router-link>
        </div>
      </div>

      <div v-if="loading" class="loading">
        Memuat data...
      </div>

      <div v-else-if="dosen" class="detail-card card">
        <div class="detail-section">
          <h2>Informasi Pribadi</h2>
          <div class="detail-grid">
            <div class="detail-item">
              <label>NIDN</label>
              <span>{{ dosen.nidn }}</span>
            </div>
            <div class="detail-item">
              <label>Nama</label>
              <span>{{ dosen.nama }}</span>
            </div>
            <div class="detail-item">
              <label>Email</label>
              <span>{{ dosen.email }}</span>
            </div>
            <div class="detail-item">
              <label>Prodi</label>
              <span>{{ dosen.prodi }}</span>
            </div>
            <div class="detail-item">
              <label>Jabatan</label>
              <span>{{ dosen.jabatan || '-' }}</span>
            </div>
          </div>
        </div>

        <div v-if="dosen.mata_kuliah && dosen.mata_kuliah.length > 0" class="detail-section">
          <h2>Mata Kuliah yang Diampu</h2>
          <div class="table-card">
            <table class="table">
              <thead>
                <tr>
                  <th>Kode</th>
                  <th>Nama Mata Kuliah</th>
                  <th>SKS</th>
                  <th>Semester</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="mk in dosen.mata_kuliah" :key="mk.id">
                  <td>{{ mk.kode }}</td>
                  <td>{{ mk.nama }}</td>
                  <td>{{ mk.sks }}</td>
                  <td>{{ mk.semester }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import dosenService, { type Dosen } from '../../services/dosen'

const route = useRoute()
const router = useRouter()
const dosen = ref<Dosen | null>(null)
const loading = ref(false)

async function loadDosen() {
  loading.value = true
  try {
    const response = await dosenService.getById(Number(route.params.id))
    dosen.value = response.data
  } catch (error) {
    console.error('Error loading dosen:', error)
    router.push('/dosen')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadDosen()
})
</script>

<style scoped>
.dosen-detail-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.dosen-detail-container.admin-pusat-bg {
  background-image: url('/images/2.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.dosen-detail-container.admin-pusat-bg::before {
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

.dosen-detail-container.admin-pusat-bg > * {
  position: relative;
  z-index: 1;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-xl);
}

.header-actions {
  display: flex;
  gap: var(--spacing-md);
}

.loading {
  text-align: center;
  padding: var(--spacing-2xl);
}

.detail-card {
  max-width: 1000px;
}

.detail-section {
  margin-bottom: var(--spacing-xl);
}

.detail-section h2 {
  margin-bottom: var(--spacing-lg);
  color: var(--color-primary);
}

.detail-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: var(--spacing-lg);
}

.detail-item {
  display: flex;
  flex-direction: column;
}

.detail-item label {
  font-weight: 500;
  color: var(--color-text-secondary);
  margin-bottom: var(--spacing-xs);
  font-size: 0.875rem;
}

.detail-item span {
  font-size: 1rem;
  color: var(--color-text-primary);
}

.table-card {
  overflow-x: auto;
  margin-top: var(--spacing-md);
}
</style>
