import api from './api'

export interface PembayaranUkt {
  id: number
  mahasiswa_id: number
  tahun_akademik: string
  semester: number
  jumlah: number
  status: 'pending' | 'lunas' | 'tertunda' | 'dibatalkan'
  tanggal_bayar?: string
  metode_pembayaran?: string
  bukti_pembayaran?: string
  keterangan?: string
  mahasiswa?: Mahasiswa
  created_at?: string
  updated_at?: string
}

export interface Mahasiswa {
  id: number
  nim: string
  nama: string
  email: string
}

export interface UktListResponse {
  data: PembayaranUkt[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

class UktService {
  async getList(filters: any = {}): Promise<UktListResponse> {
    const params = new URLSearchParams()
    if (filters.q) params.append('q', filters.q)
    if (filters.tahun_akademik) params.append('tahun_akademik', filters.tahun_akademik)
    if (filters.semester) params.append('semester', filters.semester.toString())
    if (filters.status) params.append('status', filters.status)
    if (filters.mahasiswa_id) params.append('mahasiswa_id', filters.mahasiswa_id.toString())
    if (filters.page) params.append('page', filters.page.toString())
    if (filters.per_page) params.append('per_page', filters.per_page.toString())

    const response = await api.get<UktListResponse>(`/admin-pusat/ukt?${params.toString()}`)
    return response.data
  }

  async getById(id: number): Promise<{ data: PembayaranUkt }> {
    const response = await api.get<{ data: PembayaranUkt }>(`/admin-pusat/ukt/${id}`)
    return response.data
  }

  async create(data: Partial<PembayaranUkt>): Promise<{ data: PembayaranUkt; message: string }> {
    const response = await api.post<{ data: PembayaranUkt; message: string }>('/admin-pusat/ukt', data)
    return response.data
  }

  async update(id: number, data: FormData): Promise<{ data: PembayaranUkt; message: string }> {
    const response = await api.put<{ data: PembayaranUkt; message: string }>(`/admin-pusat/ukt/${id}`, data, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data
  }

  async delete(id: number): Promise<void> {
    await api.delete(`/admin-pusat/ukt/${id}`)
  }
}

export default new UktService()
