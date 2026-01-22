<template>
  <AppLayout>
    <div class="sso-container admin-pusat-bg">
      <h1 class="page-title">SSO Configuration</h1>

      <div class="info-card card">
        <h2>Single Sign-On</h2>
        <p>Konfigurasi dan kelola SSO untuk integrasi dengan sistem lain.</p>
        
        <div v-if="config" class="config-info">
          <p><strong>Status:</strong> {{ config.enabled ? 'Aktif' : 'Nonaktif' }}</p>
          <p><strong>Provider:</strong> {{ config.provider }}</p>
          <p><strong>Endpoint:</strong> {{ config.endpoint }}</p>
        </div>

        <div class="actions">
          <button @click="generateToken" class="btn btn-primary">
            Generate Token
          </button>
          <button @click="updateConfig" class="btn btn-secondary">
            Update Config
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AppLayout from '../../components/AppLayout.vue'
import ssoService from '../../services/sso'

const config = ref<any>(null)

async function generateToken() {
  alert('Fitur generate token akan segera tersedia')
}

async function updateConfig() {
  alert('Fitur update config akan segera tersedia')
}

async function loadConfig() {
  try {
    const response = await ssoService.getConfig()
    config.value = response.data
  } catch (error) {
    console.error('Error loading config:', error)
  }
}

onMounted(() => {
  loadConfig()
})
</script>

<style scoped>
.sso-container {
  width: 100%;
  position: relative;
  min-height: calc(100vh - 80px);
}

.sso-container.admin-pusat-bg {
  background-image: url('/images/2.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}

.sso-container.admin-pusat-bg::before {
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

.sso-container.admin-pusat-bg > * {
  position: relative;
  z-index: 1;
}

.info-card {
  margin-bottom: var(--spacing-xl);
}

.config-info {
  margin: var(--spacing-lg) 0;
  padding: var(--spacing-lg);
  background: var(--color-surface);
  border-radius: var(--radius-md);
}

.actions {
  display: flex;
  gap: var(--spacing-md);
  margin-top: var(--spacing-lg);
}
</style>
