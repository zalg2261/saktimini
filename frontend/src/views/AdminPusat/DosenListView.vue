<template>
  <AppLayout>
    <div class="dosen-container admin-pusat-bg">
      <div class="page-header">
        <h1 class="page-title">Data Dosen</h1>
        <router-link to="/dosen/create" class="btn btn-primary">
          + Tambah Dosen
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
              placeholder="NIDN, Nama, atau Email"
              @input="handleFilter"
            />
          </div>
          <div class="form-group">
            <label class="form-label">Prodi</label>
            <input
              v-model="filters.prodi"
              type="text"
              class="form-input"
              placeholder="Filter prodi"
              @input="handleFilter"
            />
          </div>
          <div class="form-group">
            <label class="form-label">Jabatan</label>
            <input
              v-model="filters.jabatan"
              type="text"
              class="form-input"
              placeholder="Filter jabatan"
              @input="handleFilter"
            />
          </div>
        </div>
      </div>

      <!-- Dosen List -->
      <div class="table-card card">
        <table class="table">
          <thead>
            <tr>
              <th>NIDN</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Prodi</th>
              <th>Jabatan</th>
              <th>Mata Kuliah</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="7" class="text-center">Memuat data...</td>
            </tr>
            <tr v-else-if="dosenList.length === 0">
              <td colspan="7" class="text-center">Tidak ada data dosen</td>
            </tr>
            <tr v-else v-for="dosen in dosenList" :key="dosen.id">
              <td>{{ dosen.nidn }}</td>
              <td>{{ dosen.nama }}</td>
              <td>{{ dosen.email }}</td>
              <td>{{ dosen.prodi }}</td>
              <td>{{ dosen.jabatan || '-' }}</td>
              <td>{{ dosen.mata_kuliah?.length || 0 }} mata kuliah</td>
              <td>
                <div class="action-buttons">
                  <router-link :to="`/dosen/${dosen.id}`" class="btn btn-info btn-sm">
                    Detail
                  </router-link>
                  <router-link :to="`/dosen/${dosen.id}/edit`" class="btn btn-primary btn-sm">
                    Edit
                  </router-link>
                  <button
                    @click="handleDelete(dosen.id)"
                    class="btn btn-danger btn-sm"
                  >
                    Hapus
                  </button>
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
import { useRouter } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import dosenService, { type Dosen } from '../../services/dosen'

const router = useRouter()
const dosenList = ref<Dosen[]>([])
const pagination = ref<any>(null)
const loading = ref(false)

const filters = reactive({
  q: '',
  prodi: '',
  jabatan: '',
  page: 1,
  per_page: 15,
})

function handleFilter() {
  filters.page = 1
  loadDosen()
}

function goToPage(page: number) {
  filters.page = page
  loadDosen()
}

async function loadDosen() {
  loading.value = true
  try {
    const response = await dosenService.getList(filters)
    dosenList.value = response.data
    pagination.value = response.meta
  } catch (error) {
    console.error('Error loading dosen:', error)
  } finally {
    loading.value = false
  }
}

async function handleDelete(id: number) {
  if (!confirm('Apakah Anda yakin ingin menghapus dosen ini?')) {
    return
  }

  try {
    await dosenService.delete(id)
    loadDosen()
  } catch (error: any) {
    alert(error.response?.data?.message || 'Gagal menghapus dosen')
  }
}

onMounted(() => {
  loadDosen()
})
</script>

<style scoped>
.dosen-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.dosen-container.admin-pusat-bg {
  background-image: url('/images/2.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.dosen-container.admin-pusat-bg::before {
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

.dosen-container.admin-pusat-bg > * {
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

.action-buttons {
  display: flex;
  gap: var(--spacing-sm);
}
</style>
