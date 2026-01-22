import api from './api'

export interface Krs {
  id: number
  mahasiswa_id: number
  mata_kuliah_id: number
  tahun_akademik: string
  semester: number
  status: 'pending' | 'disetujui' | 'ditolak'
  mata_kuliah?: MataKuliah
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
  dosen_id?: number
  kapasitas: number
  dosen?: Dosen
}

export interface Dosen {
  id: number
  nidn: string
  nama: string
  email: string
  prodi: string
}

export interface KrsListResponse {
  data: Krs[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

export interface MataKuliahListResponse {
  data: MataKuliah[]
}

class KrsService {
  async getList(filters: any = {}): Promise<KrsListResponse> {
    const params = new URLSearchParams()
    if (filters.tahun_akademik) params.append('tahun_akademik', filters.tahun_akademik)
    if (filters.semester) params.append('semester', filters.semester.toString())
    if (filters.status) params.append('status', filters.status)
    if (filters.page) params.append('page', filters.page.toString())
    if (filters.per_page) params.append('per_page', filters.per_page.toString())

    const response = await api.get<KrsListResponse>(`/mahasiswa/krs?${params.toString()}`)
    return response.data
  }

  async getById(id: number): Promise<{ data: Krs }> {
    const response = await api.get<{ data: Krs }>(`/mahasiswa/krs/${id}`)
    return response.data
  }

  async create(data: { mata_kuliah_id: number; tahun_akademik: string; semester: number }): Promise<{ data: Krs; message: string }> {
    const response = await api.post<{ data: Krs; message: string }>('/mahasiswa/krs', data)
    return response.data
  }

  async delete(id: number): Promise<void> {
    await api.delete(`/mahasiswa/krs/${id}`)
  }

  async getAvailableMataKuliah(filters: any = {}): Promise<MataKuliahListResponse> {
    const params = new URLSearchParams()
    if (filters.semester) params.append('semester', filters.semester.toString())
    if (filters.tahun_akademik) params.append('tahun_akademik', filters.tahun_akademik)

    const response = await api.get<MataKuliahListResponse>(`/mahasiswa/krs/available-mata-kuliah?${params.toString()}`)
    return response.data
  }
}

export default new KrsService()
