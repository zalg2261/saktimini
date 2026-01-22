import { defineStore } from 'pinia'
import { ref } from 'vue'
import authService, { type User } from '../services/auth'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const isAuthenticated = ref(false)
  const loading = ref(false)

  async function checkAuth() {
    try {
      loading.value = true
      const response = await authService.getProfile()
      user.value = response.data.user
      isAuthenticated.value = true
      return true
    } catch (error) {
      user.value = null
      isAuthenticated.value = false
      return false
    } finally {
      loading.value = false
    }
  }

  async function login(email: string, password: string) {
    try {
      loading.value = true
      const response = await authService.login({ email, password })
      user.value = response.data.user
      isAuthenticated.value = true
      return { success: true }
    } catch (error: any) {
      const message = error.response?.data?.message || 'Login gagal'
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      await authService.logout()
    } catch (error) {
    } finally {
      user.value = null
      isAuthenticated.value = false
    }
  }

  function isAdminProdi() {
    return user.value?.role === 'admin_prodi'
  }

  function isAdminPusat() {
    return user.value?.role === 'admin_pusat'
  }

  function isDosen() {
    return user.value?.role === 'dosen'
  }

  function isMahasiswa() {
    return user.value?.role === 'mahasiswa'
  }

  return {
    user,
    isAuthenticated,
    loading,
    checkAuth,
    login,
    logout,
    isAdminProdi,
    isAdminPusat,
    isDosen,
    isMahasiswa,
  }
})
