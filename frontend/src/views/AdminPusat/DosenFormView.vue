<template>
  <AppLayout>
    <div class="dosen-form-container admin-pusat-bg">
      <div class="page-header">
        <h1 class="page-title">{{ isEdit ? 'Edit Dosen' : 'Tambah Dosen' }}</h1>
        <router-link to="/dosen" class="btn btn-secondary">
          ← Kembali
        </router-link>
      </div>

      <form @submit.prevent="handleSubmit" class="form-card card">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">NIDN <span class="required">*</span></label>
            <input
              v-model="form.nidn"
              type="text"
              class="form-input"
              required
              placeholder="Nomor Induk Dosen Nasional"
            />
            <span v-if="errors.nidn" class="error-text">{{ errors.nidn }}</span>
          </div>

          <div class="form-group">
            <label class="form-label">Nama <span class="required">*</span></label>
            <input
              v-model="form.nama"
              type="text"
              class="form-input"
              required
              placeholder="Nama lengkap"
            />
            <span v-if="errors.nama" class="error-text">{{ errors.nama }}</span>
          </div>

          <div class="form-group">
            <label class="form-label">Email <span class="required">*</span></label>
            <input
              v-model="form.email"
              type="email"
              class="form-input"
              required
              placeholder="email@kampus.ac.id"
            />
            <span v-if="errors.email" class="error-text">{{ errors.email }}</span>
          </div>

          <div class="form-group">
            <label class="form-label">Password <span class="required">*</span></label>
            <input
              v-model="form.password"
              type="password"
              class="form-input"
              :required="!isEdit"
              :placeholder="isEdit ? 'Kosongkan jika tidak ingin mengubah' : 'Minimal 8 karakter'"
              minlength="8"
            />
            <span v-if="errors.password" class="error-text">{{ errors.password }}</span>
          </div>

          <div class="form-group">
            <label class="form-label">Prodi <span class="required">*</span></label>
            <input
              v-model="form.prodi"
              type="text"
              class="form-input"
              required
              placeholder="Program Studi"
            />
            <span v-if="errors.prodi" class="error-text">{{ errors.prodi }}</span>
          </div>

          <div class="form-group">
            <label class="form-label">Jabatan</label>
            <select v-model="form.jabatan" class="form-select">
              <option value="">Pilih Jabatan</option>
              <option value="Profesor">Profesor</option>
              <option value="Lektor Kepala">Lektor Kepala</option>
              <option value="Lektor">Lektor</option>
              <option value="Asisten Ahli">Asisten Ahli</option>
            </select>
            <span v-if="errors.jabatan" class="error-text">{{ errors.jabatan }}</span>
          </div>
        </div>

        <div v-if="errorMessage" class="error-message">
          {{ errorMessage }}
        </div>

        <div class="form-actions">
          <router-link to="/dosen" class="btn btn-secondary">
            Batal
          </router-link>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            {{ loading ? 'Menyimpan...' : 'Simpan' }}
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/AppLayout.vue'
import dosenService, { type Dosen } from '../../services/dosen'

const route = useRoute()
const router = useRouter()
const isEdit = ref(false)
const loading = ref(false)
const errorMessage = ref('')
const errors = reactive<Record<string, string>>({})

const form = reactive({
  nidn: '',
  nama: '',
  email: '',
  password: '',
  prodi: '',
  jabatan: '',
})

async function loadDosen() {
  if (!route.params.id) {
    return
  }

  try {
    const response = await dosenService.getById(Number(route.params.id))
    Object.assign(form, {
      nidn: response.data.nidn,
      nama: response.data.nama,
      email: response.data.email,
      password: '',
      prodi: response.data.prodi,
      jabatan: response.data.jabatan || '',
    })
  } catch (error) {
    console.error('Error loading dosen:', error)
    router.push('/dosen')
  }
}

async function handleSubmit() {
  loading.value = true
  errorMessage.value = ''
  Object.keys(errors).forEach(key => delete errors[key])

  try {
    const data: any = {
      nidn: form.nidn,
      nama: form.nama,
      email: form.email,
      prodi: form.prodi,
      jabatan: form.jabatan || null,
    }

    if (form.password) {
      data.password = form.password
    }

    if (isEdit.value) {
      await dosenService.update(Number(route.params.id), data)
    } else {
      if (!form.password) {
        errors.password = 'Password wajib diisi'
        loading.value = false
        return
      }
      await dosenService.create(data)
    }

    router.push('/dosen')
  } catch (error: any) {
    if (error.response?.status === 422) {
      const validationErrors = error.response.data.errors
      Object.keys(validationErrors).forEach(key => {
        errors[key] = validationErrors[key][0]
      })
    } else {
      errorMessage.value = error.response?.data?.message || 'Gagal menyimpan data dosen'
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  if (route.params.id) {
    isEdit.value = true
    loadDosen()
  }
})
</script>

<style scoped>
.dosen-form-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.dosen-form-container.admin-pusat-bg {
  background-image: url('/images/2.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.dosen-form-container.admin-pusat-bg::before {
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

.dosen-form-container.admin-pusat-bg > * {
  position: relative;
  z-index: 1;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-xl);
}

.form-card {
  max-width: 800px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: var(--spacing-lg);
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-label {
  margin-bottom: var(--spacing-sm);
  font-weight: 500;
}

.required {
  color: red;
}

.error-text {
  color: red;
  font-size: 0.875rem;
  margin-top: var(--spacing-xs);
}

.error-message {
  background: #fee;
  color: #c33;
  padding: var(--spacing-md);
  border-radius: var(--radius-md);
  margin-bottom: var(--spacing-lg);
}

.form-actions {
  display: flex;
  gap: var(--spacing-md);
  justify-content: flex-end;
  margin-top: var(--spacing-xl);
  padding-top: var(--spacing-lg);
  border-top: 1px solid var(--color-border);
}
</style>
