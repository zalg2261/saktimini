Informasi Website SAKTI University

Jumlah Role di Sistem

Ada 4 role yang bisa login ke sistem ini:
1. Admin Pusat
2. Admin Prodi  
3. Dosen
4. Mahasiswa

Setiap role punya akses yang berbeda-beda. Admin Pusat itu kayak super admin, bisa akses semua fitur. Yang lain cuma bisa akses sesuai rolenya aja.

Akses Per Role

o	Admin Pusat

Admin Pusat itu role paling tinggi, bisa akses semua fitur di sistem. Jadi kalau ada fitur yang cuma bisa diakses role tertentu, Admin Pusat tetap bisa masuk.

Fitur yang bisa diakses:
- Login dan logout
- Dashboard (halaman utama setelah login)
- Profile (lihat dan edit profil sendiri)
- CRUD Mahasiswa (bisa lihat semua mahasiswa, tambah, edit, hapus)
- CRUD Dosen (bisa lihat semua dosen, tambah, edit, hapus)
- Lihat KRS mahasiswa (bisa lihat semua KRS dari semua mahasiswa)
- Lihat KHS mahasiswa (bisa lihat semua KHS dari semua mahasiswa)
- Lihat Presensi mahasiswa (bisa lihat semua presensi dari semua mahasiswa)
- Lihat Penilaian dosen (bisa lihat semua penilaian dari semua dosen)
- Lihat Materi dosen (bisa lihat semua materi dari semua dosen)
- Lihat Absensi dosen (bisa lihat semua absensi dari semua dosen)
- Integrasi PDDIKTI (sinkronisasi data dengan sistem PDDIKTI)
- Payment UKT (kelola pembayaran UKT mahasiswa)
- SSO (Single Sign On, untuk integrasi dengan sistem lain)

o	Admin Prodi

Admin Prodi itu kayak admin untuk program studi. Fokusnya ke manajemen data mahasiswa di prodinya.

Fitur yang bisa diakses:
- Login dan logout
- Dashboard
- Profile
- CRUD Mahasiswa (bisa lihat, tambah, edit, hapus mahasiswa)

Yang tidak bisa diakses:
- CRUD Dosen (cuma Admin Pusat yang bisa)
- Fitur mahasiswa seperti KRS, KHS, Presensi (itu cuma untuk mahasiswa)
- Fitur dosen seperti Penilaian, Materi, Absensi (itu cuma untuk dosen)
- PDDIKTI, UKT, SSO (cuma Admin Pusat)

o	Dosen

Dosen itu untuk pengajar. Bisa kelola penilaian, materi, dan absensi untuk mahasiswa yang diajar.

Fitur yang bisa diakses:
- Login dan logout
- Dashboard
- Profile
- Penilaian (CRUD penilaian untuk mahasiswa yang diajar)
- Materi (CRUD materi pembelajaran)
- Absensi (CRUD absensi dan record presensi mahasiswa)

Yang tidak bisa diakses:
- CRUD Mahasiswa (cuma Admin Prodi dan Admin Pusat)
- CRUD Dosen (cuma Admin Pusat)
- KRS, KHS, Presensi mahasiswa (itu cuma untuk mahasiswa)
- PDDIKTI, UKT, SSO (cuma Admin Pusat)

o	Mahasiswa

Mahasiswa itu untuk yang kuliah. Bisa lihat KRS, KHS, dan presensi mereka sendiri.

Fitur yang bisa diakses:
- Login dan logout
- Dashboard
- Profile
- KRS (lihat dan kelola Kartu Rencana Studi, bisa tambah/hapus mata kuliah)
- KHS (lihat Kartu Hasil Studi, nilai-nilai yang sudah didapat)
- Presensi (lihat riwayat presensi mereka sendiri)

Yang tidak bisa diakses:
- CRUD Mahasiswa (cuma Admin Prodi dan Admin Pusat)
- CRUD Dosen (cuma Admin Pusat)
- Penilaian, Materi, Absensi (itu cuma untuk dosen)
- PDDIKTI, UKT, SSO (cuma Admin Pusat)

Detail CRUD Operations

o	CRUD Mahasiswa

Yang bisa akses: Admin Prodi dan Admin Pusat

Operasi yang bisa dilakukan:
- Create: Tambah mahasiswa baru (termasuk buat akun user otomatis)
- Read: Lihat daftar mahasiswa dengan fitur search, filter, sort, dan pagination
- Update: Edit data mahasiswa (termasuk update akun user)
- Delete: Hapus mahasiswa (soft delete, data tidak benar-benar dihapus dari database)

Fitur tambahan:
- Search: Bisa cari berdasarkan NIM, nama, atau email
- Filter: Bisa filter berdasarkan prodi, status (aktif/cuti/lulus/dropout), atau angkatan
- Sort: Bisa sort berdasarkan kolom apapun (NIM, nama, email, prodi, angkatan, status, atau tanggal dibuat)
- Pagination: Bisa atur berapa banyak data per halaman (maksimal 100)

o	CRUD Dosen

Yang bisa akses: Admin Pusat saja

Operasi yang bisa dilakukan:
- Create: Tambah dosen baru (termasuk buat akun user otomatis)
- Read: Lihat daftar dosen
- Update: Edit data dosen (termasuk update akun user)
- Delete: Hapus dosen (soft delete)

o	CRUD KRS (Kartu Rencana Studi)

Yang bisa akses: Mahasiswa (untuk KRS mereka sendiri) dan Admin Pusat (bisa lihat semua)

Operasi yang bisa dilakukan:
- Create: Tambah mata kuliah ke KRS
- Read: Lihat daftar mata kuliah di KRS
- Update: Edit data KRS
- Delete: Hapus mata kuliah dari KRS

Fitur tambahan:
- Available Mata Kuliah: Lihat daftar mata kuliah yang bisa diambil

o	CRUD KHS (Kartu Hasil Studi)

Yang bisa akses: Mahasiswa (untuk KHS mereka sendiri) dan Admin Pusat (bisa lihat semua)

Operasi yang bisa dilakukan:
- Read: Lihat daftar nilai di KHS (index dan detail)

o	CRUD Presensi

Yang bisa akses: Mahasiswa (untuk presensi mereka sendiri) dan Admin Pusat (bisa lihat semua)

Operasi yang bisa dilakukan:
- Read: Lihat riwayat presensi (index dan detail)

o	CRUD Penilaian

Yang bisa akses: Dosen (untuk penilaian mahasiswa yang diajar) dan Admin Pusat (bisa lihat semua)

Operasi yang bisa dilakukan:
- Create: Tambah penilaian untuk mahasiswa
- Read: Lihat daftar penilaian
- Update: Edit penilaian
- Delete: Hapus penilaian

o	CRUD Materi

Yang bisa akses: Dosen (untuk materi mereka sendiri) dan Admin Pusat (bisa lihat semua)

Operasi yang bisa dilakukan:
- Create: Tambah materi pembelajaran
- Read: Lihat daftar materi
- Update: Edit materi
- Delete: Hapus materi

o	CRUD Absensi

Yang bisa akses: Dosen (untuk absensi kelas yang diajar) dan Admin Pusat (bisa lihat semua)

Operasi yang bisa dilakukan:
- Create: Tambah absensi
- Read: Lihat daftar absensi
- Update: Edit absensi
- Delete: Hapus absensi
- Record Presensi: Record presensi mahasiswa untuk absensi tertentu

Fitur Admin Pusat Khusus

o	PDDIKTI Integration

Yang bisa akses: Admin Pusat saja

Fitur:
- Sync Mahasiswa: Sinkronisasi data mahasiswa dari PDDIKTI
- Sync All Mahasiswa: Sinkronisasi semua data mahasiswa sekaligus
- Sync Dosen: Sinkronisasi data dosen dari PDDIKTI
- Sync Status: Lihat status sinkronisasi terakhir

o	Payment UKT

Yang bisa akses: Admin Pusat saja

Fitur:
- CRUD pembayaran UKT mahasiswa

o	SSO (Single Sign On)

Yang bisa akses: Admin Pusat saja

Fitur:
- Generate Token: Generate token SSO untuk integrasi
- Validate Token: Validasi token SSO
- Get Config: Lihat konfigurasi SSO
- Update Config: Update konfigurasi SSO

Sistem Keamanan

o	Authentication

Semua fitur kecuali login memerlukan authentication. Sistem pakai JWT token yang disimpan di HttpOnly cookie. Jadi kalau belum login, otomatis diarahkan ke halaman login.

o	Authorization

Setelah login, sistem cek role user. Kalau role tidak sesuai dengan yang dibutuhkan fitur, akan dapat error 403 Forbidden atau diarahkan kembali ke dashboard.

o	Admin Pusat Bypass

Admin Pusat itu spesial, bisa bypass semua permission check. Jadi di middleware CheckRole, kalau user adalah admin_pusat, langsung diizinkan akses tanpa cek role lain.

o	Data Access Control

Mahasiswa cuma bisa lihat data mereka sendiri. Dosen cuma bisa lihat data mahasiswa yang mereka ajar. Admin Prodi bisa lihat semua mahasiswa. Admin Pusat bisa lihat semua data.

Tapi di beberapa controller, Admin Pusat bisa akses semua data dengan filter opsional. Misalnya di KrsController, Admin Pusat bisa lihat semua KRS atau filter berdasarkan mahasiswa_id.
