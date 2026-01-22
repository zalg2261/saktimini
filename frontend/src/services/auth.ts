import api from './api'

export interface LoginCredentials {
  email: string
  password: string
}

export interface User {
  id: number
  name: string
  email: string
  role: 'admin_prodi' | 'admin_pusat' | 'dosen' | 'mahasiswa'
}

export interface LoginResponse {
  data: {
    user: User
  }
  message: string
}

export interface ProfileResponse {
  data: {
    user: User
  }
}

class AuthService {
  async login(credentials: LoginCredentials): Promise<LoginResponse> {
    const response = await api.post<LoginResponse>('/login', credentials)
    return response.data
  }

  async logout(): Promise<void> {
    await api.post('/logout')
  }

  async getProfile(): Promise<ProfileResponse> {
    const response = await api.get<ProfileResponse>('/profile')
    return response.data
  }
}

export default new AuthService()
