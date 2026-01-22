import api from './api'

export interface Khs {
  id: number
  mahasiswa_id: number
  mata_kuliah_id: number
  tahun_akademik: string
  semester: number
  nilai_uts?: number
  nilai_uas?: number
  nilai_tugas?: number
  nilai_akhir?: number
  huruf_mutu?: string
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
  dosen?: Dosen
}

export interface Dosen {
  id: number
  nidn: string
  nama: string
}

export interface KhsListResponse {
  data: Khs[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
    ipk: number
    total_sks: number
  }
}

class KhsService {
  async getList(filters: any = {}): Promise<KhsListResponse> {
    const params = new URLSearchParams()
    if (filters.tahun_akademik) params.append('tahun_akademik', filters.tahun_akademik)
    if (filters.semester) params.append('semester', filters.semester.toString())
    if (filters.page) params.append('page', filters.page.toString())
    if (filters.per_page) params.append('per_page', filters.per_page.toString())

    const response = await api.get<KhsListResponse>(`/mahasiswa/khs?${params.toString()}`)
    return response.data
  }

  async getById(id: number): Promise<{ data: Khs }> {
    const response = await api.get<{ data: Khs }>(`/mahasiswa/khs/${id}`)
    return response.data
  }
}

export default new KhsService()
