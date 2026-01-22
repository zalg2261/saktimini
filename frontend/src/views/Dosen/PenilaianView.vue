<template>
  <AppLayout>
    <div class="penilaian-container dosen-bg">
      <div class="page-header">
        <h1 class="page-title">Penilaian</h1>
        <button @click="showAddModal = true" class="btn btn-primary">
          + Tambah Penilaian
        </button>
      </div>

      <!-- Filters -->
      <div class="filters-card card">
        <div class="filters-grid">
          <div class="form-group">
            <label class="form-label">Cari Mahasiswa</label>
            <input
              v-model="filters.q"
              type="text"
              class="form-input"
              placeholder="NIM atau Nama"
              @input="handleFilter"
            />
          </div>
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

      <!-- Penilaian List -->
      <div class="table-card card">
        <table class="table">
          <thead>
            <tr>
              <th>Mahasiswa</th>
              <th>Mata Kuliah</th>
              <th>UTS</th>
              <th>UAS</th>
              <th>Tugas</th>
              <th>Kehadiran</th>
              <th>Nilai Akhir</th>
              <th>Huruf Mutu</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="9" class="text-center">Memuat data...</td>
            </tr>
            <tr v-else-if="penilaianList.length === 0">
              <td colspan="9" class="text-center">Tidak ada data penilaian</td>
            </tr>
            <tr v-else v-for="penilaian in penilaianList" :key="penilaian.id">
              <td>{{ penilaian.mahasiswa?.nama }} ({{ penilaian.mahasiswa?.nim }})</td>
              <td>{{ penilaian.mata_kuliah?.nama }}</td>
              <td>{{ penilaian.nilai_uts || '-' }}</td>
              <td>{{ penilaian.nilai_uas || '-' }}</td>
              <td>{{ penilaian.nilai_tugas || '-' }}</td>
              <td>{{ penilaian.nilai_kehadiran || '-' }}</td>
              <td>{{ penilaian.nilai_akhir || '-' }}</td>
              <td>
                <span :class="getHurufMutuClass(penilaian.huruf_mutu)">
                  {{ penilaian.huruf_mutu || '-' }}
                </span>
              </td>
              <td>
                <button @click="editPenilaian(penilaian)" class="btn btn-primary btn-sm">
                  Edit
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import penilaianService, { type Penilaian } from '../../services/penilaian'

const penilaianList = ref<Penilaian[]>([])
const loading = ref(false)
const showAddModal = ref(false)

const filters = reactive({
  q: '',
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
  loadPenilaian()
}

function editPenilaian(penilaian: Penilaian) {
}

async function loadPenilaian() {
  loading.value = true
  try {
    const response = await penilaianService.getList(filters)
    penilaianList.value = response.data
  } catch (error) {
    console.error('Error loading penilaian:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadPenilaian()
})
</script>

<style scoped>
.penilaian-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.penilaian-container.dosen-bg {
  background-image: url('/images/4.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.penilaian-container.dosen-bg::before {
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

.penilaian-container.dosen-bg > * {
  position: relative;
  z-index: 1;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-xl);
}

.filters-card {
  margin-bottom: var(--spacing-xl);
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: var(--spacing-md);
}

.table-card {
  overflow-x: auto;
}

.text-center {
  text-align: center;
  padding: var(--spacing-xl) !important;
}
</style>
