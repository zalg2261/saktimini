<template>
  <AppLayout>
    <div class="materi-container dosen-bg">
      <div class="page-header">
        <h1 class="page-title">Materi</h1>
        <button @click="showAddModal = true" class="btn btn-primary">
          + Tambah Materi
        </button>
      </div>

      <!-- Materi List -->
      <div class="materi-grid">
        <div v-for="materi in materiList" :key="materi.id" class="materi-card card">
          <h3>{{ materi.judul }}</h3>
          <p v-if="materi.deskripsi" class="materi-description">{{ materi.deskripsi }}</p>
          <div class="materi-meta">
            <span class="materi-mata-kuliah">{{ materi.mata_kuliah?.nama }}</span>
            <span class="materi-tipe">{{ materi.tipe.toUpperCase() }}</span>
          </div>
          <div class="materi-actions">
            <a v-if="materi.file_path" :href="getFileUrl(materi.file_path)" target="_blank" class="btn btn-primary btn-sm">
              Download
            </a>
            <button @click="deleteMateri(materi.id)" class="btn btn-danger btn-sm">
              Hapus
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import materiService, { type Materi } from '../../services/materi'

const materiList = ref<Materi[]>([])
const showAddModal = ref(false)

function getFileUrl(filePath: string) {
  return filePath
}

async function deleteMateri(id: number) {
  if (!confirm('Apakah Anda yakin ingin menghapus materi ini?')) {
    return
  }

  try {
    await materiService.delete(id)
    loadMateri()
  } catch (error) {
    console.error('Error deleting materi:', error)
  }
}

async function loadMateri() {
  try {
    const response = await materiService.getList({})
    materiList.value = response.data
  } catch (error) {
    console.error('Error loading materi:', error)
  }
}

onMounted(() => {
  loadMateri()
})
</script>

<style scoped>
.materi-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.materi-container.dosen-bg {
  background-image: url('/images/4.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.materi-container.dosen-bg::before {
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

.materi-container.dosen-bg > * {
  position: relative;
  z-index: 1;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-xl);
}

.materi-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: var(--spacing-lg);
}

.materi-card {
  padding: var(--spacing-lg);
}

.materi-description {
  color: var(--color-text-muted);
  margin: var(--spacing-md) 0;
}

.materi-meta {
  display: flex;
  justify-content: space-between;
  margin: var(--spacing-md) 0;
  font-size: 0.875rem;
}

.materi-mata-kuliah {
  color: var(--color-text-secondary);
}

.materi-tipe {
  background: var(--color-sky-blue);
  padding: var(--spacing-xs) var(--spacing-sm);
  border-radius: var(--radius-sm);
}

.materi-actions {
  display: flex;
  gap: var(--spacing-sm);
  margin-top: var(--spacing-md);
}
</style>
