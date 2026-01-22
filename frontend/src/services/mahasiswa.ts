import api from './api'

export interface Mahasiswa {
  id: number
  nim: string
  nama: string
  email: string
  prodi: string
  angkatan: number
  status: 'aktif' | 'cuti' | 'lulus' | 'dropout'
  created_at?: string
  updated_at?: string
}

export interface MahasiswaListResponse {
  data: Mahasiswa[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
  }
}

export interface MahasiswaResponse {
  data: Mahasiswa
}

export interface MahasiswaFilters {
  q?: string
  prodi?: string
  status?: string
  angkatan?: number
  sortBy?: string
  sortDir?: 'asc' | 'desc'
  page?: number
  per_page?: number
}

class MahasiswaService {
  async getList(filters: MahasiswaFilters = {}): Promise<MahasiswaListResponse> {
    const params = new URLSearchParams()
    
    if (filters.q) params.append('q', filters.q)
    if (filters.prodi) params.append('prodi', filters.prodi)
    if (filters.status) params.append('status', filters.status)
    if (filters.angkatan) params.append('angkatan', filters.angkatan.toString())
    if (filters.sortBy) params.append('sortBy', filters.sortBy)
    if (filters.sortDir) params.append('sortDir', filters.sortDir)
    if (filters.page) params.append('page', filters.page.toString())
    if (filters.per_page) params.append('per_page', filters.per_page.toString())

    const response = await api.get<MahasiswaListResponse>(`/mahasiswa?${params.toString()}`)
    return response.data
  }

  async getById(id: number): Promise<MahasiswaResponse> {
    const response = await api.get<MahasiswaResponse>(`/mahasiswa/${id}`)
    return response.data
  }

  async create(data: Omit<Mahasiswa, 'id' | 'created_at' | 'updated_at'> & { password: string }): Promise<MahasiswaResponse> {
    const response = await api.post<MahasiswaResponse>('/mahasiswa', data)
    return response.data
  }

  async update(id: number, data: Partial<Omit<Mahasiswa, 'id' | 'created_at' | 'updated_at'> & { password?: string }>): Promise<MahasiswaResponse> {
    const response = await api.put<MahasiswaResponse>(`/mahasiswa/${id}`, data)
    return response.data
  }

  async delete(id: number): Promise<void> {
    await api.delete(`/mahasiswa/${id}`)
  }
}

export default new MahasiswaService()
