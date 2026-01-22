<template>
  <AppLayout>
    <div class="presensi-container mahasiswa-bg">
      <h1 class="page-title">Presensi</h1>

      <!-- Filters -->
      <div class="filters-card card">
        <div class="filters-grid">
          <div class="form-group">
            <label class="form-label">Mata Kuliah</label>
            <input
              v-model="filters.mata_kuliah"
              type="text"
              class="form-input"
              placeholder="Cari mata kuliah"
            />
          </div>
          <div class="form-group">
            <label class="form-label">Tanggal Dari</label>
            <input
              v-model="filters.tanggal_dari"
              type="date"
              class="form-input"
              @change="handleFilter"
            />
          </div>
          <div class="form-group">
            <label class="form-label">Tanggal Sampai</label>
            <input
              v-model="filters.tanggal_sampai"
              type="date"
              class="form-input"
              @change="handleFilter"
            />
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select" @change="handleFilter">
              <option value="">Semua</option>
              <option value="hadir">Hadir</option>
              <option value="izin">Izin</option>
              <option value="sakit">Sakit</option>
              <option value="alpha">Alpha</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Statistics -->
      <div v-if="pagination?.statistik" class="stats-card card">
        <div class="stats-grid">
          <div class="stat-item">
            <span class="stat-label">Total Presensi</span>
            <span class="stat-value">{{ pagination.statistik.total }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Hadir</span>
            <span class="stat-value">{{ pagination.statistik.hadir }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Persentase Hadir</span>
            <span class="stat-value">{{ pagination.statistik.persentase_hadir }}%</span>
          </div>
        </div>
      </div>

      <!-- Presensi List -->
      <div class="table-card card">
        <table class="table">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Mata Kuliah</th>
              <th>Jam</th>
              <th>Status</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="5" class="text-center">Memuat data...</td>
            </tr>
            <tr v-else-if="presensiList.length === 0">
              <td colspan="5" class="text-center">Tidak ada data presensi</td>
            </tr>
            <tr v-else v-for="presensi in presensiList" :key="presensi.id">
              <td>{{ formatDate(presensi.tanggal) }}</td>
              <td>{{ presensi.mata_kuliah?.nama }}</td>
              <td>{{ presensi.jam_mulai }} - {{ presensi.jam_selesai || '-' }}</td>
              <td>
                <span :class="getStatusBadgeClass(presensi.status)">
                  {{ getStatusLabel(presensi.status) }}
                </span>
              </td>
              <td>{{ presensi.keterangan || '-' }}</td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="pagination && pagination.last_page > 1" class="pagination">
          <button
            @click="goToPage(pagination.current_page - 1)"
            :disabled="pagination.current_page === 1"
            class="btn btn-secondary"
          >
            Sebelumnya
          </button>
          <span class="pagination-info">
            Halaman {{ pagination.current_page }} dari {{ pagination.last_page }}
          </span>
          <button
            @click="goToPage(pagination.current_page + 1)"
            :disabled="pagination.current_page === pagination.last_page"
            class="btn btn-secondary"
          >
            Selanjutnya
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import presensiService, { type Presensi } from '../../services/presensi'

const presensiList = ref<Presensi[]>([])
const pagination = ref<any>(null)
const loading = ref(false)

const filters = reactive({
  mata_kuliah_id: undefined as number | undefined,
  tanggal_dari: '',
  tanggal_sampai: '',
  status: '',
  page: 1,
  per_page: 15,
})

function formatDate(date: string) {
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

function getStatusLabel(status: string) {
  const labels: Record<string, string> = {
    hadir: 'Hadir',
    izin: 'Izin',
    sakit: 'Sakit',
    alpha: 'Alpha',
  }
  return labels[status] || status
}

function getStatusBadgeClass(status: string) {
  const classes: Record<string, string> = {
    hadir: 'badge badge-success',
    izin: 'badge badge-info',
    sakit: 'badge badge-warning',
    alpha: 'badge badge-danger',
  }
  return classes[status] || 'badge'
}

function handleFilter() {
  filters.page = 1
  loadPresensi()
}

function goToPage(page: number) {
  filters.page = page
  loadPresensi()
}

async function loadPresensi() {
  loading.value = true
  try {
    const response = await presensiService.getList(filters)
    presensiList.value = response.data
    pagination.value = response.meta
  } catch (error) {
    console.error('Error loading presensi:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadPresensi()
})
</script>

<style scoped>
.presensi-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.presensi-container.mahasiswa-bg {
  background-image: url('/images/3.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.presensi-container.mahasiswa-bg::before {
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

.presensi-container.mahasiswa-bg > * {
  position: relative;
  z-index: 1;
}

.filters-card {
  margin-bottom: var(--spacing-xl);
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: var(--spacing-md);
}

.stats-card {
  margin-bottom: var(--spacing-xl);
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: var(--spacing-lg);
}

.stat-item {
  text-align: center;
  padding: var(--spacing-lg);
  background: var(--color-surface);
  border-radius: var(--radius-md);
}

.stat-label {
  display: block;
  font-size: 0.875rem;
  color: var(--color-text-secondary);
  margin-bottom: var(--spacing-sm);
}

.stat-value {
  display: block;
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--color-primary);
}

.table-card {
  overflow-x: auto;
}

.text-center {
  text-align: center;
  padding: var(--spacing-xl) !important;
}
</style>
