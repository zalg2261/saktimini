import api from './api'

class PddiktiService {
  async syncMahasiswa(mahasiswaId: number): Promise<{ message: string; data: any }> {
    const response = await api.post<{ message: string; data: any }>('/admin-pusat/pddikti/sync/mahasiswa', {
      mahasiswa_id: mahasiswaId,
    })
    return response.data
  }

  async syncAllMahasiswa(): Promise<{ message: string; data: any }> {
    const response = await api.post<{ message: string; data: any }>('/admin-pusat/pddikti/sync/all-mahasiswa')
    return response.data
  }

  async syncDosen(dosenId: number): Promise<{ message: string; data: any }> {
    const response = await api.post<{ message: string; data: any }>('/admin-pusat/pddikti/sync/dosen', {
      dosen_id: dosenId,
    })
    return response.data
  }

  async getSyncStatus(): Promise<{ data: any }> {
    const response = await api.get<{ data: any }>('/admin-pusat/pddikti/sync/status')
    return response.data
  }
}

export default new PddiktiService()
