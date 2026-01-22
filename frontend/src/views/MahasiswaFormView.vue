<template>
  <AppLayout>
    <div class="mahasiswa-form-container admin-prodi-bg">
      <h1 class="page-title">{{ isEdit ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' }}</h1>

      <div class="card">
        <form @submit.prevent="handleSubmit" class="mahasiswa-form">
          <div class="form-row">
            <div class="form-group">
              <label for="nim" class="form-label">NIM <span class="required">*</span></label>
              <input
                id="nim"
                v-model="form.nim"
                type="text"
                class="form-input"
                :class="{ 'error': errors.nim }"
                required
                :disabled="loading"
                placeholder="Nomor Induk Mahasiswa"
              />
              <span v-if="errors.nim" class="form-error">{{ errors.nim }}</span>
            </div>

            <div class="form-group">
              <label for="nama" class="form-label">Nama <span class="required">*</span></label>
              <input
                id="nama"
                v-model="form.nama"
                type="text"
                class="form-input"
                :class="{ 'error': errors.nama }"
                required
                :disabled="loading"
                placeholder="Nama Lengkap"
              />
              <span v-if="errors.nama" class="form-error">{{ errors.nama }}</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="email" class="form-label">Email <span class="required">*</span></label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                class="form-input"
                :class="{ 'error': errors.email }"
                required
                :disabled="loading"
                placeholder="email@example.com"
              />
              <span v-if="errors.email" class="form-error">{{ errors.email }}</span>
            </div>

            <div class="form-group">
              <label for="password" class="form-label">Password <span class="required">*</span></label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                class="form-input"
                :class="{ 'error': errors.password }"
                :required="!isEdit"
                :disabled="loading"
                :placeholder="isEdit ? 'Kosongkan jika tidak ingin mengubah' : 'Minimal 8 karakter'"
                minlength="8"
              />
              <span v-if="errors.password" class="form-error">{{ errors.password }}</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="prodi" class="form-label">Program Studi <span class="required">*</span></label>
              <input
                id="prodi"
                v-model="form.prodi"
                type="text"
                class="form-input"
                :class="{ 'error': errors.prodi }"
                required
                :disabled="loading"
                placeholder="Program Studi"
              />
              <span v-if="errors.prodi" class="form-error">{{ errors.prodi }}</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="angkatan" class="form-label">Angkatan <span class="required">*</span></label>
              <input
                id="angkatan"
                v-model.number="form.angkatan"
                type="number"
                class="form-input"
                :class="{ 'error': errors.angkatan }"
                required
                :disabled="loading"
                min="2000"
                max="2099"
                placeholder="Tahun"
              />
              <span v-if="errors.angkatan" class="form-error">{{ errors.angkatan }}</span>
            </div>

            <div class="form-group">
              <label for="status" class="form-label">Status <span class="required">*</span></label>
              <select
                id="status"
                v-model="form.status"
                class="form-select"
                :class="{ 'error': errors.status }"
                required
                :disabled="loading"
              >
                <option value="">Pilih Status</option>
                <option value="aktif">Aktif</option>
                <option value="cuti">Cuti</option>
                <option value="lulus">Lulus</option>
                <option value="dropout">Dropout</option>
              </select>
              <span v-if="errors.status" class="form-error">{{ errors.status }}</span>
            </div>
          </div>

          <div v-if="errorMessage" class="error-message">
            {{ errorMessage }}
          </div>

          <div class="form-actions">
            <router-link to="/mahasiswa" class="btn btn-secondary">Batal</router-link>
            <button type="submit" class="btn btn-primary" :disabled="loading">
              <span v-if="loading">Menyimpan...</span>
              <span v-else>{{ isEdit ? 'Update' : 'Simpan' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import AppLayout from '../components/AppLayout.vue'
import mahasiswaService from '../services/mahasiswa'

const router = useRouter()
const route = useRoute()

const isEdit = ref(false)
const loading = ref(false)
const errorMessage = ref('')

const form = reactive({
  nim: '',
  nama: '',
  email: '',
  password: '',
  prodi: '',
  angkatan: undefined as number | undefined,
  status: '',
})

const errors = reactive({
  nim: '',
  nama: '',
  email: '',
  password: '',
  prodi: '',
  angkatan: '',
  status: '',
})

onMounted(async () => {
  const id = route.params.id as string
  if (id && id !== 'create') {
    isEdit.value = true
    await loadMahasiswa(parseInt(id))
  }
})

async function loadMahasiswa(id: number) {
  try {
    loading.value = true
    const response = await mahasiswaService.getById(id)
    Object.assign(form, {
      ...response.data,
      password: ''
    })
  } catch (error: any) {
    errorMessage.value = error.response?.data?.message || 'Gagal memuat data mahasiswa'
  } finally {
    loading.value = false
  }
}

function validateForm() {
  Object.keys(errors).forEach(key => {
    errors[key as keyof typeof errors] = ''
  })
  let isValid = true

  if (!form.nim) {
    errors.nim = 'NIM wajib diisi'
    isValid = false
  }

  if (!form.nama) {
    errors.nama = 'Nama wajib diisi'
    isValid = false
  }

  if (!form.email) {
    errors.email = 'Email wajib diisi'
    isValid = false
  } else if (!/\S+@\S+\.\S+/.test(form.email)) {
    errors.email = 'Email tidak valid'
    isValid = false
  }

  if (!isEdit.value && !form.password) {
    errors.password = 'Password wajib diisi'
    isValid = false
  } else if (form.password && form.password.length < 8) {
    errors.password = 'Password minimal 8 karakter'
    isValid = false
  }

  if (!form.prodi) {
    errors.prodi = 'Program Studi wajib diisi'
    isValid = false
  }

  if (!form.angkatan) {
    errors.angkatan = 'Angkatan wajib diisi'
    isValid = false
  } else if (form.angkatan < 2000 || form.angkatan > 2099) {
    errors.angkatan = 'Angkatan harus antara 2000-2099'
    isValid = false
  }

  if (!form.status) {
    errors.status = 'Status wajib dipilih'
    isValid = false
  }

  return isValid
}

async function handleSubmit() {
  errorMessage.value = ''

  if (!validateForm()) {
    return
  }

  loading.value = true

  try {
    if (isEdit.value) {
      const id = parseInt(route.params.id as string)
      await mahasiswaService.update(id, form)
      router.push('/mahasiswa')
    } else {
      await mahasiswaService.create(form)
      router.push('/mahasiswa')
    }
  } catch (error: any) {
    if (error.response?.status === 422) {
      const validationErrors = error.response.data.errors
      Object.keys(validationErrors).forEach(key => {
        if (key in errors) {
          errors[key as keyof typeof errors] = validationErrors[key][0]
        }
      })
    } else {
      errorMessage.value = error.response?.data?.message || 'Terjadi kesalahan saat menyimpan data'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.mahasiswa-form-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.mahasiswa-form-container.admin-prodi-bg {
  background-image: url('/images/5.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.mahasiswa-form-container.admin-prodi-bg::before {
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

.mahasiswa-form-container.admin-prodi-bg > * {
  position: relative;
  z-index: 1;
}

.page-title {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  font-weight: 600;
  color: var(--color-primary);
  margin-bottom: var(--spacing-xl);
}

.mahasiswa-form {
  max-width: 800px;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: var(--spacing-lg);
  margin-bottom: var(--spacing-lg);
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }
}

.required {
  color: #dc3545;
}

.form-input.error,
.form-select.error {
  border-color: #dc3545;
}

.error-message {
  background-color: #f8d7da;
  color: #721c24;
  padding: var(--spacing-md);
  border-radius: var(--radius-md);
  margin-bottom: var(--spacing-md);
  font-size: 0.875rem;
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
