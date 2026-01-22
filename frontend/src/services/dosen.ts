import api from './api'

export interface Dosen {
  id: number
  nidn: string
  nama: string
  email: string
  prodi: string
  jabatan?: string
  mata_kuliah?: MataKuliah[]
  created_at?: string
  updated_at?: string
}

export interface MataKuliah {
  id: number
  kode: string
  nama: string
  prodi: string
  sks: number
  semester: number
}

export interface DosenListResponse {
  data: Dosen[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

class DosenService {
  async getList(filters: any = {}): Promise<DosenListResponse> {
    const params = new URLSearchParams()
    if (filters.q) params.append('q', filters.q)
    if (filters.prodi) params.append('prodi', filters.prodi)
    if (filters.jabatan) params.append('jabatan', filters.jabatan)
    if (filters.sort_by) params.append('sort_by', filters.sort_by)
    if (filters.sort_dir) params.append('sort_dir', filters.sort_dir)
    if (filters.page) params.append('page', filters.page.toString())
    if (filters.per_page) params.append('per_page', filters.per_page.toString())

    const response = await api.get<DosenListResponse>(`/admin-pusat/dosen?${params.toString()}`)
    return response.data
  }

  async getById(id: number): Promise<{ data: Dosen }> {
    const response = await api.get<{ data: Dosen }>(`/admin-pusat/dosen/${id}`)
    return response.data
  }

  async create(data: Partial<Dosen & { password: string }>): Promise<{ data: Dosen; message: string }> {
    const response = await api.post<{ data: Dosen; message: string }>('/admin-pusat/dosen', data)
    return response.data
  }

  async update(id: number, data: Partial<Dosen & { password?: string }>): Promise<{ data: Dosen; message: string }> {
    const response = await api.put<{ data: Dosen; message: string }>(`/admin-pusat/dosen/${id}`, data)
    return response.data
  }

  async delete(id: number): Promise<void> {
    await api.delete(`/admin-pusat/dosen/${id}`)
  }
}

export default new DosenService()
