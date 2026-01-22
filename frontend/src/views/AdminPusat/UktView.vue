<template>
  <AppLayout>
    <div class="ukt-container admin-pusat-bg">
      <div class="page-header">
        <h1 class="page-title">Payment UKT</h1>
        <button @click="showAddModal = true" class="btn btn-primary">
          + Tambah Pembayaran
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
            <label class="form-label">Status</label>
            <select v-model="filters.status" class="form-select" @change="handleFilter">
              <option value="">Semua</option>
              <option value="pending">Pending</option>
              <option value="lunas">Lunas</option>
              <option value="tertunda">Tertunda</option>
              <option value="dibatalkan">Dibatalkan</option>
            </select>
          </div>
        </div>
      </div>

      <!-- UKT List -->
      <div class="table-card card">
        <table class="table">
          <thead>
            <tr>
              <th>Mahasiswa</th>
              <th>Tahun Akademik</th>
              <th>Semester</th>
              <th>Jumlah</th>
              <th>Status</th>
              <th>Tanggal Bayar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="7" class="text-center">Memuat data...</td>
            </tr>
            <tr v-else-if="uktList.length === 0">
              <td colspan="7" class="text-center">Tidak ada data pembayaran</td>
            </tr>
            <tr v-else v-for="ukt in uktList" :key="ukt.id">
              <td>{{ ukt.mahasiswa?.nama }} ({{ ukt.mahasiswa?.nim }})</td>
              <td>{{ ukt.tahun_akademik }}</td>
              <td>{{ ukt.semester }}</td>
              <td>Rp {{ formatCurrency(ukt.jumlah) }}</td>
              <td>
                <span :class="getStatusBadgeClass(ukt.status)">
                  {{ getStatusLabel(ukt.status) }}
                </span>
              </td>
              <td>{{ ukt.tanggal_bayar ? formatDate(ukt.tanggal_bayar) : '-' }}</td>
              <td>
                <button @click="editUkt(ukt)" class="btn btn-primary btn-sm">
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
import uktService, { type PembayaranUkt } from '../../services/ukt'

const uktList = ref<PembayaranUkt[]>([])
const loading = ref(false)
const showAddModal = ref(false)

const filters = reactive({
  q: '',
  status: '',
  page: 1,
  per_page: 15,
})

function formatCurrency(amount: number) {
  return new Intl.NumberFormat('id-ID').format(amount)
}

function formatDate(date: string) {
  return new Date(date).toLocaleDateString('id-ID')
}

function getStatusLabel(status: string) {
  const labels: Record<string, string> = {
    pending: 'Pending',
    lunas: 'Lunas',
    tertunda: 'Tertunda',
    dibatalkan: 'Dibatalkan',
  }
  return labels[status] || status
}

function getStatusBadgeClass(status: string) {
  const classes: Record<string, string> = {
    pending: 'badge badge-warning',
    lunas: 'badge badge-success',
    tertunda: 'badge badge-info',
    dibatalkan: 'badge badge-danger',
  }
  return classes[status] || 'badge'
}

function handleFilter() {
  filters.page = 1
  loadUkt()
}

function editUkt(ukt: PembayaranUkt) {
}

async function loadUkt() {
  loading.value = true
  try {
    const response = await uktService.getList(filters)
    uktList.value = response.data
  } catch (error) {
    console.error('Error loading UKT:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadUkt()
})
</script>

<style scoped>
.ukt-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.ukt-container.admin-pusat-bg {
  background-image: url('/images/2.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.ukt-container.admin-pusat-bg::before {
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

.ukt-container.admin-pusat-bg > * {
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
