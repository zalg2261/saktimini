<template>
  <AppLayout>
    <div class="absensi-container dosen-bg">
      <div class="page-header">
        <h1 class="page-title">Absensi</h1>
        <button @click="showAddModal = true" class="btn btn-primary">
          + Buat Sesi Absensi
        </button>
      </div>

      <!-- Absensi List -->
      <div class="table-card card">
        <table class="table">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Mata Kuliah</th>
              <th>Jam</th>
              <th>Topik</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="5" class="text-center">Memuat data...</td>
            </tr>
            <tr v-else-if="absensiList.length === 0">
              <td colspan="5" class="text-center">Tidak ada data absensi</td>
            </tr>
            <tr v-else v-for="absensi in absensiList" :key="absensi.id">
              <td>{{ formatDate(absensi.tanggal) }}</td>
              <td>{{ absensi.mata_kuliah?.nama }}</td>
              <td>{{ absensi.jam_mulai }} - {{ absensi.jam_selesai || '-' }}</td>
              <td>{{ absensi.topik || '-' }}</td>
              <td>
                <button @click="viewPresensi(absensi.id)" class="btn btn-primary btn-sm">
                  Lihat Presensi
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
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import absensiService, { type Absensi } from '../../services/absensi'

const absensiList = ref<Absensi[]>([])
const loading = ref(false)
const showAddModal = ref(false)

function formatDate(date: string) {
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

function viewPresensi(id: number) {
}

async function loadAbsensi() {
  loading.value = true
  try {
    const response = await absensiService.getList({})
    absensiList.value = response.data
  } catch (error) {
    console.error('Error loading absensi:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadAbsensi()
})
</script>

<style scoped>
.absensi-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.absensi-container.dosen-bg {
  background-image: url('/images/4.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.absensi-container.dosen-bg::before {
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

.absensi-container.dosen-bg > * {
  position: relative;
  z-index: 1;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-xl);
}

.table-card {
  overflow-x: auto;
}

.text-center {
  text-align: center;
  padding: var(--spacing-xl) !important;
}
</style>
