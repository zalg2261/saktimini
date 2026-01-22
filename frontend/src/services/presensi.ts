import api from './api'

export interface Presensi {
  id: number
  mahasiswa_id: number
  mata_kuliah_id: number
  tanggal: string
  jam_mulai: string
  jam_selesai?: string
  status: 'hadir' | 'izin' | 'sakit' | 'alpha'
  keterangan?: string
  mata_kuliah?: MataKuliah
  created_at?: string
  updated_at?: string
}

export interface MataKuliah {
  id: number
  kode: string
  nama: string
  prodi: string
  dosen?: Dosen
}

export interface Dosen {
  id: number
  nama: string
}

export interface PresensiListResponse {
  data: Presensi[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
    statistik: {
      total: number
      hadir: number
      persentase_hadir: number
    }
  }
}

class PresensiService {
  async getList(filters: any = {}): Promise<PresensiListResponse> {
    const params = new URLSearchParams()
    if (filters.mata_kuliah_id) params.append('mata_kuliah_id', filters.mata_kuliah_id.toString())
    if (filters.tanggal_dari) params.append('tanggal_dari', filters.tanggal_dari)
    if (filters.tanggal_sampai) params.append('tanggal_sampai', filters.tanggal_sampai)
    if (filters.status) params.append('status', filters.status)
    if (filters.page) params.append('page', filters.page.toString())
    if (filters.per_page) params.append('per_page', filters.per_page.toString())

    const response = await api.get<PresensiListResponse>(`/mahasiswa/presensi?${params.toString()}`)
    return response.data
  }

  async getById(id: number): Promise<{ data: Presensi }> {
    const response = await api.get<{ data: Presensi }>(`/mahasiswa/presensi/${id}`)
    return response.data
  }
}

export default new PresensiService()
