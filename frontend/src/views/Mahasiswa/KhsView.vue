<template>
  <AppLayout>
    <div class="khs-container mahasiswa-bg">
      <h1 class="page-title">Kartu Hasil Studi (KHS)</h1>

      <!-- Filters -->
      <div class="filters-card card">
        <div class="filters-grid">
          <div class="form-group">
            <label class="form-label">Tahun Akademik</label>
            <input
              v-model="filters.tahun_akademik"
              type="text"
              class="form-input"
              placeholder="2024/2025"
              @input="handleFilter"
            />
          </div>
          <div class="form-group">
            <label class="form-label">Semester</label>
            <select v-model.number="filters.semester" class="form-select" @change="handleFilter">
              <option value="">Semua</option>
              <option :value="1">1</option>
              <option :value="2">2</option>
            </select>
          </div>
        </div>
      </div>

      <!-- IPK Summary -->
      <div v-if="pagination" class="summary-card card">
        <div class="summary-grid">
          <div class="summary-item">
            <span class="summary-label">IPK</span>
            <span class="summary-value">{{ pagination.ipk || '0.00' }}</span>
          </div>
          <div class="summary-item">
            <span class="summary-label">Total SKS</span>
            <span class="summary-value">{{ pagination.total_sks || 0 }}</span>
          </div>
        </div>
      </div>

      <!-- KHS List -->
      <div class="table-card card">
        <table class="table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Mata Kuliah</th>
              <th>SKS</th>
              <th>UTS</th>
              <th>UAS</th>
              <th>Tugas</th>
              <th>Nilai Akhir</th>
              <th>Huruf Mutu</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="8" class="text-center">Memuat data...</td>
            </tr>
            <tr v-else-if="khsList.length === 0">
              <td colspan="8" class="text-center">Tidak ada data KHS</td>
            </tr>
            <tr v-else v-for="khs in khsList" :key="khs.id">
              <td>{{ khs.mata_kuliah?.kode }}</td>
              <td>{{ khs.mata_kuliah?.nama }}</td>
              <td>{{ khs.mata_kuliah?.sks }}</td>
              <td>{{ khs.nilai_uts || '-' }}</td>
              <td>{{ khs.nilai_uas || '-' }}</td>
              <td>{{ khs.nilai_tugas || '-' }}</td>
              <td>{{ khs.nilai_akhir || '-' }}</td>
              <td>
                <span :class="getHurufMutuClass(khs.huruf_mutu)">
                  {{ khs.huruf_mutu || '-' }}
                </span>
              </td>
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
import khsService, { type Khs } from '../../services/khs'

const khsList = ref<Khs[]>([])
const pagination = ref<any>(null)
const loading = ref(false)

const filters = reactive({
  tahun_akademik: '',
  semester: undefined as number | undefined,
  page: 1,
  per_page: 15,
})

function getHurufMutuClass(hurufMutu?: string) {
  if (!hurufMutu) return 'badge'
  const classes: Record<string, string> = {
    'A': 'badge badge-success',
    'B+': 'badge badge-success',
    'B': 'badge badge-info',
    'C+': 'badge badge-info',
    'C': 'badge badge-warning',
    'D': 'badge badge-warning',
    'E': 'badge badge-danger',
  }
  return classes[hurufMutu] || 'badge'
}

function handleFilter() {
  filters.page = 1
  loadKhs()
}

function goToPage(page: number) {
  filters.page = page
  loadKhs()
}

async function loadKhs() {
  loading.value = true
  try {
    const response = await khsService.getList(filters)
    khsList.value = response.data
    pagination.value = response.meta
  } catch (error) {
    console.error('Error loading KHS:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadKhs()
})
</script>

<style scoped>
.khs-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.khs-container.mahasiswa-bg {
  background-image: url('/images/3.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.khs-container.mahasiswa-bg::before {
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

.khs-container.mahasiswa-bg > * {
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

.summary-card {
  margin-bottom: var(--spacing-xl);
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: var(--spacing-lg);
}

.summary-item {
  text-align: center;
  padding: var(--spacing-lg);
  background: var(--color-surface);
  border-radius: var(--radius-md);
}

.summary-label {
  display: block;
  font-size: 0.875rem;
  color: var(--color-text-secondary);
  margin-bottom: var(--spacing-sm);
}

.summary-value {
  display: block;
  font-size: 2rem;
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
