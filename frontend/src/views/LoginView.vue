<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1 class="login-title">SAKTI University</h1>
        <p class="login-subtitle">Portal Akademik Kampus</p>
      </div>
      
      <form @submit.prevent="handleLogin" class="login-form">
        <div class="form-group">
          <label for="email" class="form-label">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="form-input"
            required
            :disabled="loading"
            placeholder="Masukkan email"
          />
          <span v-if="errors.email" class="form-error">{{ errors.email }}</span>
        </div>

        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            class="form-input"
            required
            :disabled="loading"
            placeholder="Masukkan password"
          />
          <span v-if="errors.password" class="form-error">{{ errors.password }}</span>
        </div>

        <div v-if="errorMessage" class="error-message">
          {{ errorMessage }}
        </div>

        <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
          <span v-if="loading">Memproses...</span>
          <span v-else>Masuk</span>
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: '',
})

const errors = reactive({
  email: '',
  password: '',
})

const errorMessage = ref('')
const loading = ref(false)

function validateForm() {
  errors.email = ''
  errors.password = ''
  let isValid = true

  if (!form.email) {
    errors.email = 'Email wajib diisi'
    isValid = false
  } else if (!/\S+@\S+\.\S+/.test(form.email)) {
    errors.email = 'Email tidak valid'
    isValid = false
  }

  if (!form.password) {
    errors.password = 'Password wajib diisi'
    isValid = false
  }

  return isValid
}

async function handleLogin() {
  errorMessage.value = ''
  
  if (!validateForm()) {
    return
  }

  loading.value = true

  try {
    const result = await authStore.login(form.email, form.password)
    
    if (result.success) {
      const redirect = route.query.redirect as string || '/'
      router.push(redirect)
    } else {
      errorMessage.value = result.message || 'Login gagal'
    }
  } catch (error: any) {
    errorMessage.value = error.response?.data?.message || 'Terjadi kesalahan saat login'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  width: 100vw;
  display: flex;
  align-items: center;
  justify-content: center;
  background-image: url('/images/1.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  padding: var(--spacing-md);
  position: relative;
  overflow: hidden;
}

.login-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(47, 65, 86, 0.6);
  pointer-events: none;
}

.login-card {
  background: var(--color-white);
  border-radius: 16px;
  box-shadow: 0 10px 40px rgba(47, 65, 86, 0.1);
  padding: var(--spacing-2xl);
  width: 100%;
  max-width: 420px;
  position: relative;
  z-index: 1;
}

.login-header {
  text-align: center;
  margin-bottom: var(--spacing-2xl);
}

.login-title {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  font-weight: 600;
  color: var(--color-primary);
  margin-bottom: var(--spacing-sm);
  line-height: 1.2;
}

.login-subtitle {
  font-size: 0.95rem;
  color: var(--color-text-muted);
  font-weight: 400;
}

.login-form {
  margin-top: var(--spacing-xl);
}

.form-group {
  margin-bottom: var(--spacing-lg);
}

.form-label {
  display: block;
  margin-bottom: var(--spacing-sm);
  font-weight: 500;
  color: var(--color-text-primary);
  font-size: 0.9rem;
}

.form-input {
  width: 100%;
  padding: var(--spacing-md);
  border: 1.5px solid #e0e0e0;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.2s;
  background-color: #f8f9fa;
  font-family: 'Inter', sans-serif;
}

.form-input:focus {
  outline: none;
  border-color: var(--color-primary);
  background-color: var(--color-white);
  box-shadow: 0 0 0 3px rgba(47, 65, 86, 0.1);
}

.form-input::placeholder {
  color: #adb5bd;
}

.form-error {
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: var(--spacing-xs);
  display: block;
}

.btn-block {
  width: 100%;
  margin-top: var(--spacing-lg);
  padding: var(--spacing-md);
  font-size: 1rem;
  font-weight: 600;
  border-radius: 8px;
}

.error-message {
  background-color: #f8d7da;
  color: #721c24;
  padding: var(--spacing-md);
  border-radius: 8px;
  margin-bottom: var(--spacing-md);
  font-size: 0.875rem;
  text-align: center;
}

@media (max-width: 480px) {
  .login-container {
    padding: var(--spacing-sm);
  }

  .login-card {
    padding: var(--spacing-xl);
    max-width: 100%;
    border-radius: 12px;
  }

  .login-title {
    font-size: 2rem;
  }

  .login-subtitle {
    font-size: 0.875rem;
  }
}

@media (min-width: 481px) and (max-width: 768px) {
  .login-card {
    max-width: 400px;
  }
}

@media (min-width: 769px) {
  .login-card {
    max-width: 420px;
  }
}

@media (min-width: 1024px) {
  .login-card {
    padding: var(--spacing-2xl) var(--spacing-2xl);
  }
}

@media (min-width: 1440px) {
  .login-card {
    max-width: 480px;
    padding: 3rem;
  }

  .login-title {
    font-size: 3rem;
  }
}
</style>
