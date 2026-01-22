import api from './api'

export interface SsoToken {
  token: string
  expires_at: string
  user: {
    id: number
    name: string
    email: string
    role: string
  }
}

export interface SsoConfig {
  enabled: boolean
  provider: string
  endpoint: string
}

class SsoService {
  async generateToken(userId: number, expiresIn?: number): Promise<{ data: SsoToken; message: string }> {
    const response = await api.post<{ data: SsoToken; message: string }>('/admin-pusat/sso/generate-token', {
      user_id: userId,
      expires_in: expiresIn,
    })
    return response.data
  }

  async validateToken(token: string): Promise<{ data: { valid: boolean; message: string } }> {
    const response = await api.post<{ data: { valid: boolean; message: string } }>('/admin-pusat/sso/validate-token', {
      token,
    })
    return response.data
  }

  async getConfig(): Promise<{ data: SsoConfig }> {
    const response = await api.get<{ data: SsoConfig }>('/admin-pusat/sso/config')
    return response.data
  }

  async updateConfig(config: Partial<SsoConfig>): Promise<{ message: string; data: SsoConfig }> {
    const response = await api.put<{ message: string; data: SsoConfig }>('/admin-pusat/sso/config', config)
    return response.data
  }
}

export default new SsoService()
