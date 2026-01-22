import api from './api'

export interface Penilaian {
  id: number
  dosen_id: number
  mahasiswa_id: number
  mata_kuliah_id: number
  tahun_akademik: string
  semester: number
  nilai_uts?: number
  nilai_uas?: number
  nilai_tugas?: number
  nilai_kehadiran?: number
  nilai_akhir?: number
  huruf_mutu?: string
  catatan?: string
  mahasiswa?: Mahasiswa
  mata_kuliah?: MataKuliah
  created_at?: string
  updated_at?: string
}

export interface Mahasiswa {
  id: number
  nim: string
  nama: string
  email: string
}

export interface MataKuliah {
  id: number
  kode: string
  nama: string
  prodi: string
  sks: number
}

export interface PenilaianListResponse {
  data: Penilaian[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

class PenilaianService {
  async getList(filters: any = {}): Promise<PenilaianListResponse> {
    const params = new URLSearchParams()
    if (filters.mata_kuliah_id) params.append('mata_kuliah_id', filters.mata_kuliah_id.toString())
    if (filters.tahun_akademik) params.append('tahun_akademik', filters.tahun_akademik)
    if (filters.semester) params.append('semester', filters.semester.toString())
    if (filters.q) params.append('q', filters.q)
    if (filters.page) params.append('page', filters.page.toString())
    if (filters.per_page) params.append('per_page', filters.per_page.toString())

    const response = await api.get<PenilaianListResponse>(`/dosen/penilaian?${params.toString()}`)
    return response.data
  }

  async getById(id: number): Promise<{ data: Penilaian }> {
    const response = await api.get<{ data: Penilaian }>(`/dosen/penilaian/${id}`)
    return response.data
  }

  async create(data: Partial<Penilaian>): Promise<{ data: Penilaian; message: string }> {
    const response = await api.post<{ data: Penilaian; message: string }>('/dosen/penilaian', data)
    return response.data
  }

  async update(id: number, data: Partial<Penilaian>): Promise<{ data: Penilaian; message: string }> {
    const response = await api.put<{ data: Penilaian; message: string }>(`/dosen/penilaian/${id}`, data)
    return response.data
  }
}

export default new PenilaianService()
