import api from './api'

export interface Materi {
  id: number
  dosen_id: number
  mata_kuliah_id: number
  judul: string
  deskripsi?: string
  file_path?: string
  file_name?: string
  tipe: 'pdf' | 'doc' | 'ppt' | 'video' | 'link'
  mata_kuliah?: MataKuliah
  created_at?: string
  updated_at?: string
}

export interface MataKuliah {
  id: number
  kode: string
  nama: string
  prodi: string
}

export interface MateriListResponse {
  data: Materi[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

class MateriService {
  async getList(filters: any = {}): Promise<MateriListResponse> {
    const params = new URLSearchParams()
    if (filters.mata_kuliah_id) params.append('mata_kuliah_id', filters.mata_kuliah_id.toString())
    if (filters.q) params.append('q', filters.q)
    if (filters.page) params.append('page', filters.page.toString())
    if (filters.per_page) params.append('per_page', filters.per_page.toString())

    const response = await api.get<MateriListResponse>(`/dosen/materi?${params.toString()}`)
    return response.data
  }

  async getById(id: number): Promise<{ data: Materi }> {
    const response = await api.get<{ data: Materi }>(`/dosen/materi/${id}`)
    return response.data
  }

  async create(data: FormData): Promise<{ data: Materi; message: string }> {
    const response = await api.post<{ data: Materi; message: string }>('/dosen/materi', data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data
  }

  async update(id: number, data: FormData): Promise<{ data: Materi; message: string }> {
    const response = await api.put<{ data: Materi; message: string }>(`/dosen/materi/${id}`, data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data
  }

  async delete(id: number): Promise<void> {
    await api.delete(`/dosen/materi/${id}`)
  }
}

export default new MateriService()
