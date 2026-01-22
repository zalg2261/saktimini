import api from './api'

export interface Absensi {
  id: number
  dosen_id: number
  mata_kuliah_id: number
  tanggal: string
  jam_mulai: string
  jam_selesai?: string
  topik?: string
  catatan?: string
  mata_kuliah?: MataKuliah
  created_at?: string
  updated_at?: string
}

export interface Presensi {
  id: number
  mahasiswa_id: number
  mata_kuliah_id: number
  tanggal: string
  status: 'hadir' | 'izin' | 'sakit' | 'alpha'
  mahasiswa?: Mahasiswa
}

export interface Mahasiswa {
  id: number
  nim: string
  nama: string
}

export interface MataKuliah {
  id: number
  kode: string
  nama: string
  prodi: string
}

export interface AbsensiListResponse {
  data: Absensi[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

class AbsensiService {
  async getList(filters: any = {}): Promise<AbsensiListResponse> {
    const params = new URLSearchParams()
    if (filters.mata_kuliah_id) params.append('mata_kuliah_id', filters.mata_kuliah_id.toString())
    if (filters.tanggal_dari) params.append('tanggal_dari', filters.tanggal_dari)
    if (filters.tanggal_sampai) params.append('tanggal_sampai', filters.tanggal_sampai)
    if (filters.page) params.append('page', filters.page.toString())
    if (filters.per_page) params.append('per_page', filters.per_page.toString())

    const response = await api.get<AbsensiListResponse>(`/dosen/absensi?${params.toString()}`)
    return response.data
  }

  async getById(id: number): Promise<{ data: { absensi: Absensi; presensi: Presensi[] } }> {
    const response = await api.get<{ data: { absensi: Absensi; presensi: Presensi[] } }>(`/dosen/absensi/${id}`)
    return response.data
  }

  async create(data: Partial<Absensi>): Promise<{ data: Absensi; message: string }> {
    const response = await api.post<{ data: Absensi; message: string }>('/dosen/absensi', data)
    return response.data
  }

  async update(id: number, data: Partial<Absensi>): Promise<{ data: Absensi; message: string }> {
    const response = await api.put<{ data: Absensi; message: string }>(`/dosen/absensi/${id}`, data)
    return response.data
  }

  async delete(id: number): Promise<void> {
    await api.delete(`/dosen/absensi/${id}`)
  }

  async recordPresensi(id: number, presensi: Array<{ mahasiswa_id: number; status: string; keterangan?: string }>): Promise<{ data: Presensi[]; message: string }> {
    const response = await api.post<{ data: Presensi[]; message: string }>(`/dosen/absensi/${id}/record-presensi`, { presensi })
    return response.data
  }
}

export default new AbsensiService()
