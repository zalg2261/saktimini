<template>
  <AppLayout>
    <div class="mahasiswa-list-container admin-prodi-bg">
      <div class="page-header">
        <h1 class="page-title">Data Mahasiswa</h1>
        <router-link to="/mahasiswa/create" class="btn btn-primary">
          + Tambah Mahasiswa
        </router-link>
      </div>

      <!-- Filters -->
      <div class="filters-card card">
        <div class="filters-grid">
          <div class="form-group">
            <label class="form-label">Cari</label>
            <input
              v-model="filters.q"
              type="text"
              class="form-input"
              placeholder="NIM, Nama, atau Email"
              @input="handleSearch"
            />
          </div>

          <div class="form-group">
            <label class="form-label">Prodi</label>
            <input
              v-model="filters.prodi"
              type="text"
              class="form-input"
              placeholder="Program Studi"
              @input="handleFilter"
            />
          </div>

          <div class="form-group">
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select" @change="handleFilter">
              <option value="">Semua</option>
              <option value="aktif">Aktif</option>
              <option value="cuti">Cuti</option>
              <option value="lulus">Lulus</option>
              <option value="dropout">Dropout</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Angkatan</label>
            <input
              v-model.number="filters.angkatan"
              type="number"
              class="form-input"
              placeholder="Tahun"
              min="2000"
              max="2099"
              @input="handleFilter"
            />
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="table-card card">
        <table class="table">
          <thead>
            <tr>
              <th @click="handleSort('nim')">
                NIM
                <span v-if="filters.sortBy === 'nim'">
                  {{ filters.sortDir === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th @click="handleSort('nama')">
                Nama
                <span v-if="filters.sortBy === 'nama'">
                  {{ filters.sortDir === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th @click="handleSort('email')">
                Email
                <span v-if="filters.sortBy === 'email'">
                  {{ filters.sortDir === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th @click="handleSort('prodi')">
                Prodi
                <span v-if="filters.sortBy === 'prodi'">
                  {{ filters.sortDir === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th @click="handleSort('angkatan')">
                Angkatan
                <span v-if="filters.sortBy === 'angkatan'">
                  {{ filters.sortDir === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th @click="handleSort('status')">
                Status
                <span v-if="filters.sortBy === 'status'">
                  {{ filters.sortDir === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="7" class="text-center">Memuat data...</td>
            </tr>
            <tr v-else-if="mahasiswaList.length === 0">
              <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            <tr v-else v-for="mahasiswa in mahasiswaList" :key="mahasiswa.id">
              <td>{{ mahasiswa.nim }}</td>
              <td>{{ mahasiswa.nama }}</td>
              <td>{{ mahasiswa.email }}</td>
              <td>{{ mahasiswa.prodi }}</td>
              <td>{{ mahasiswa.angkatan }}</td>
              <td>
                <span :class="getStatusBadgeClass(mahasiswa.status)">
                  {{ getStatusLabel(mahasiswa.status) }}
                </span>
              </td>
              <td>
                <div class="action-buttons">
                  <router-link
                    :to="`/mahasiswa/${mahasiswa.id}`"
                    class="btn btn-secondary btn-sm"
                  >
                    Detail
                  </router-link>
                  <router-link
                    :to="`/mahasiswa/${mahasiswa.id}/edit`"
                    class="btn btn-secondary btn-sm"
                  >
                    Edit
                  </router-link>
                </div>
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
            (Total: {{ pagination.total }})
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
import { useAuthStore } from '../stores/auth'
import AppLayout from '../components/AppLayout.vue'
import mahasiswaService, { type MahasiswaFilters } from '../services/mahasiswa'

const authStore = useAuthStore()
const mahasiswaList = ref<any[]>([])
const pagination = ref<any>(null)
const loading = ref(false)

const filters = reactive<MahasiswaFilters>({
  q: '',
  prodi: '',
  status: '',
  angkatan: undefined,
  sortBy: 'created_at',
  sortDir: 'desc',
  page: 1,
  per_page: 15,
})

let searchTimeout: ReturnType<typeof setTimeout>

function handleSearch() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    filters.page = 1
    loadMahasiswa()
  }, 500)
}

function handleFilter() {
  filters.page = 1
  loadMahasiswa()
}

function handleSort(column: string) {
  if (filters.sortBy === column) {
    filters.sortDir = filters.sortDir === 'asc' ? 'desc' : 'asc'
  } else {
    filters.sortBy = column
    filters.sortDir = 'asc'
  }
  loadMahasiswa()
}

function goToPage(page: number) {
  filters.page = page
  loadMahasiswa()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

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
  try {
    const response = await mahasiswaService.getList(filters)
    mahasiswaList.value = response.data
    pagination.value = response.meta
  } catch (error: any) {
    console.error('Error loading mahasiswa:', error)
    mahasiswaList.value = []
    
    if (error.response?.status === 401) {
      alert('Session expired. Silakan login kembali.')
    } else if (error.response?.status === 403) {
      alert('Anda tidak memiliki akses ke halaman ini.')
    } else {
      alert('Gagal memuat data mahasiswa: ' + (error.response?.data?.message || error.message))
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadMahasiswa()
})
</script>

<style scoped>
.mahasiswa-list-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.mahasiswa-list-container.admin-prodi-bg {
  background-image: url('/images/5.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.mahasiswa-list-container.admin-prodi-bg::before {
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

.mahasiswa-list-container.admin-prodi-bg > * {
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

.action-buttons {
  display: flex;
  gap: var(--spacing-sm);
}

.pagination {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: var(--spacing-xl);
  padding-top: var(--spacing-lg);
  border-top: 1px solid var(--color-border);
}

.pagination-info {
  color: var(--color-text-secondary);
}
</style>
