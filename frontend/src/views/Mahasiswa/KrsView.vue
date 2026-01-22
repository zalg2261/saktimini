<template>
  <AppLayout>
    <div class="krs-container mahasiswa-bg">
      <div class="page-header">
        <h1 class="page-title">Kartu Rencana Studi (KRS)</h1>
        <button @click="showAddModal = true" class="btn btn-primary">
          + Tambah Mata Kuliah
        </button>
      </div>

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
          <div class="form-group">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select" @change="handleFilter">
              <option value="">Semua</option>
              <option value="pending">Pending</option>
              <option value="disetujui">Disetujui</option>
              <option value="ditolak">Ditolak</option>
            </select>
          </div>
        </div>
      </div>

      <!-- KRS List -->
      <div class="table-card card">
        <table class="table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Mata Kuliah</th>
              <th>SKS</th>
              <th>Dosen</th>
              <th>Tahun Akademik</th>
              <th>Semester</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="8" class="text-center">Memuat data...</td>
            </tr>
            <tr v-else-if="krsList.length === 0">
              <td colspan="8" class="text-center">Tidak ada data KRS</td>
            </tr>
            <tr v-else v-for="krs in krsList" :key="krs.id">
              <td>{{ krs.mata_kuliah?.kode }}</td>
              <td>{{ krs.mata_kuliah?.nama }}</td>
              <td>{{ krs.mata_kuliah?.sks }}</td>
              <td>{{ krs.mata_kuliah?.dosen?.nama || '-' }}</td>
              <td>{{ krs.tahun_akademik }}</td>
              <td>{{ krs.semester }}</td>
              <td>
                <span :class="getStatusBadgeClass(krs.status)">
                  {{ getStatusLabel(krs.status) }}
                </span>
              </td>
              <td>
                <button
                  @click="handleDelete(krs.id)"
                  class="btn btn-danger btn-sm"
                  :disabled="krs.status !== 'pending'"
                >
                  Hapus
                </button>
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

      <!-- Add Modal -->
      <div v-if="showAddModal" class="modal-overlay" @click="showAddModal = false">
        <div class="modal-content" @click.stop>
          <div class="modal-header">
            <h2>Tambah Mata Kuliah ke KRS</h2>
            <button @click="showAddModal = false" class="modal-close">×</button>
          </div>
          <form @submit.prevent="handleAddKrs" class="modal-body">
            <div class="form-group">
              <label class="form-label">Tahun Akademik</label>
              <input
                v-model="form.tahun_akademik"
                type="text"
                class="form-input"
                required
                placeholder="2024/2025"
              />
            </div>
            <div class="form-group">
              <label class="form-label">Semester</label>
              <select v-model.number="form.semester" class="form-select" required>
                <option value="">Pilih Semester</option>
                <option :value="1">1</option>
                <option :value="2">2</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Mata Kuliah</label>
              <select v-model.number="form.mata_kuliah_id" class="form-select" required>
                <option value="">Pilih Mata Kuliah</option>
                <option
                  v-for="mk in availableMataKuliah"
                  :key="mk.id"
                  :value="mk.id"
                >
                  {{ mk.kode }} - {{ mk.nama }} ({{ mk.sks }} SKS)
                </option>
              </select>
            </div>
            <div v-if="errorMessage" class="error-message">
              {{ errorMessage }}
            </div>
            <div class="modal-footer">
              <button type="button" @click="showAddModal = false" class="btn btn-secondary">
                Batal
              </button>
              <button type="submit" class="btn btn-primary" :disabled="loading">
                Tambah
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import krsService, { type Krs, type MataKuliah } from '../../services/krs'

const krsList = ref<Krs[]>([])
const availableMataKuliah = ref<MataKuliah[]>([])
const pagination = ref<any>(null)
const loading = ref(false)
const showAddModal = ref(false)
const errorMessage = ref('')

const filters = reactive({
  tahun_akademik: '',
  semester: undefined as number | undefined,
  status: '',
  page: 1,
  per_page: 15,
})

const form = reactive({
  tahun_akademik: '',
  semester: undefined as number | undefined,
  mata_kuliah_id: undefined as number | undefined,
})

function getStatusLabel(status: string) {
  const labels: Record<string, string> = {
    pending: 'Pending',
    disetujui: 'Disetujui',
    ditolak: 'Ditolak',
  }
  return labels[status] || status
}

function getStatusBadgeClass(status: string) {
  const classes: Record<string, string> = {
    pending: 'badge badge-warning',
    disetujui: 'badge badge-success',
    ditolak: 'badge badge-danger',
  }
  return classes[status] || 'badge'
}

function handleFilter() {
  filters.page = 1
  loadKrs()
}

function goToPage(page: number) {
  filters.page = page
  loadKrs()
}

async function loadKrs() {
  loading.value = true
  try {
    const response = await krsService.getList(filters)
    krsList.value = response.data
    pagination.value = response.meta
  } catch (error) {
    console.error('Error loading KRS:', error)
  } finally {
    loading.value = false
  }
}

async function loadAvailableMataKuliah() {
  try {
    const response = await krsService.getAvailableMataKuliah({
      semester: form.semester,
      tahun_akademik: form.tahun_akademik,
    })
    availableMataKuliah.value = response.data
  } catch (error) {
    console.error('Error loading mata kuliah:', error)
  }
}

async function handleAddKrs() {
  errorMessage.value = ''
  loading.value = true

  try {
    await krsService.create(form)
    showAddModal.value = false
    Object.assign(form, {
      tahun_akademik: '',
      semester: undefined,
      mata_kuliah_id: undefined,
    })
    loadKrs()
  } catch (error: any) {
    errorMessage.value = error.response?.data?.message || 'Gagal menambahkan mata kuliah'
  } finally {
    loading.value = false
  }
}

async function handleDelete(id: number) {
  if (!confirm('Apakah Anda yakin ingin menghapus mata kuliah ini dari KRS?')) {
    return
  }

  try {
    await krsService.delete(id)
    loadKrs()
  } catch (error: any) {
    alert(error.response?.data?.message || 'Gagal menghapus mata kuliah')
  }
}

onMounted(() => {
  loadKrs()
})

import { watch } from 'vue'
watch([() => form.semester, () => form.tahun_akademik], () => {
  if (form.semester && form.tahun_akademik) {
    loadAvailableMataKuliah()
  }
})
</script>

<style scoped>
.krs-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.krs-container.mahasiswa-bg {
  background-image: url('/images/3.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.krs-container.mahasiswa-bg::before {
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

.krs-container.mahasiswa-bg > * {
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

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: var(--radius-lg);
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--spacing-lg);
  border-bottom: 1px solid var(--color-border);
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: var(--color-text-muted);
}

.modal-body {
  padding: var(--spacing-lg);
}

.modal-footer {
  display: flex;
  gap: var(--spacing-md);
  justify-content: flex-end;
  margin-top: var(--spacing-lg);
  padding-top: var(--spacing-lg);
  border-top: 1px solid var(--color-border);
}
</style>
