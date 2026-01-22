^^^Informasi Website SAKTI University^^^

-----Jumlah Role di Sistem-----

Ada 4 role yang bisa login ke sistem ini:
1. Admin Pusat
2. Admin Prodi  
3. Dosen
4. Mahasiswa
Setiap role punya akses yang berbeda-beda. Admin Pusat itu kayak super admin, bisa akses semua fitur. Yang lain cuma bisa akses sesuai rolenya aja.

*Akses Per Role

1. Admin Pusat

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

2. Admin Prodi

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

3. Dosen
   
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

4. Mahasiswa
   
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

-----Detail CRUD Operations-----

CRUD Mahasiswa
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

CRUD Dosen
Yang bisa akses: Admin Pusat saja
Operasi yang bisa dilakukan:
- Create: Tambah dosen baru (termasuk buat akun user otomatis)
- Read: Lihat daftar dosen
- Update: Edit data dosen (termasuk update akun user)
- Delete: Hapus dosen (soft delete)

CRUD KRS (Kartu Rencana Studi)
Yang bisa akses: Mahasiswa (untuk KRS mereka sendiri) dan Admin Pusat (bisa lihat semua)
Operasi yang bisa dilakukan:
- Create: Tambah mata kuliah ke KRS
- Read: Lihat daftar mata kuliah di KRS
- Update: Edit data KRS
- Delete: Hapus mata kuliah dari KRS
Fitur tambahan:
- Available Mata Kuliah: Lihat daftar mata kuliah yang bisa diambil

CRUD KHS (Kartu Hasil Studi)
Yang bisa akses: Mahasiswa (untuk KHS mereka sendiri) dan Admin Pusat (bisa lihat semua)
Operasi yang bisa dilakukan:
- Read: Lihat daftar nilai di KHS (index dan detail)

CRUD Presensi
Yang bisa akses: Mahasiswa (untuk presensi mereka sendiri) dan Admin Pusat (bisa lihat semua)
Operasi yang bisa dilakukan:
- Read: Lihat riwayat presensi (index dan detail)

CRUD Penilaian
Yang bisa akses: Dosen (untuk penilaian mahasiswa yang diajar) dan Admin Pusat (bisa lihat semua)
Operasi yang bisa dilakukan:
- Create: Tambah penilaian untuk mahasiswa
- Read: Lihat daftar penilaian
- Update: Edit penilaian
- Delete: Hapus penilaian

CRUD Materi
Yang bisa akses: Dosen (untuk materi mereka sendiri) dan Admin Pusat (bisa lihat semua)
Operasi yang bisa dilakukan:
- Create: Tambah materi pembelajaran
- Read: Lihat daftar materi
- Update: Edit materi
- Delete: Hapus materi

CRUD Absensi
Yang bisa akses: Dosen (untuk absensi kelas yang diajar) dan Admin Pusat (bisa lihat semua)
Operasi yang bisa dilakukan:
- Create: Tambah absensi
- Read: Lihat daftar absensi
- Update: Edit absensi
- Delete: Hapus absensi
- Record Presensi: Record presensi mahasiswa untuk absensi tertentu

*Fitur Admin Pusat Khusus
PDDIKTI Integration
Yang bisa akses: Admin Pusat saja
Fitur:
- Sync Mahasiswa: Sinkronisasi data mahasiswa dari PDDIKTI
- Sync All Mahasiswa: Sinkronisasi semua data mahasiswa sekaligus
- Sync Dosen: Sinkronisasi data dosen dari PDDIKTI
- Sync Status: Lihat status sinkronisasi terakhir
Payment UKT
Yang bisa akses: Admin Pusat saja
Fitur:
- CRUD pembayaran UKT mahasiswa
SSO (Single Sign On)
Yang bisa akses: Admin Pusat saja
Fitur:
- Generate Token: Generate token SSO untuk integrasi
- Validate Token: Validasi token SSO
- Get Config: Lihat konfigurasi SSO
- Update Config: Update konfigurasi SSO

-----Sistem Keamanan-----

-	Authentication
Semua fitur kecuali login memerlukan authentication. Sistem pakai JWT token yang disimpan di HttpOnly cookie. Jadi kalau belum login, otomatis diarahkan ke halaman login.
- Authorization
Setelah login, sistem cek role user. Kalau role tidak sesuai dengan yang dibutuhkan fitur, akan dapat error 403 Forbidden atau diarahkan kembali ke dashboard.
- Admin Pusat Bypass
Admin Pusat itu spesial, bisa bypass semua permission check. Jadi di middleware CheckRole, kalau user adalah admin_pusat, langsung diizinkan akses tanpa cek role lain.
- Data Access Control
Mahasiswa cuma bisa lihat data mereka sendiri. Dosen cuma bisa lihat data mahasiswa yang mereka ajar. Admin Prodi bisa lihat semua mahasiswa. Admin Pusat bisa lihat semua data.
Tapi di beberapa controller, Admin Pusat bisa akses semua data dengan filter opsional. Misalnya di KrsController, Admin Pusat bisa lihat semua KRS atau filter berdasarkan mahasiswa_id.

-----Cara Kerja Database-----

Struktur Tabel Utama
Sistem ini pakai database PostgreSQL dengan beberapa tabel utama:
1. Tabel users
Ini tabel untuk authentication. Menyimpan data user yang bisa login ke sistem.
- id: Primary key
- name: Nama user
- email: Email unik (untuk login)
- password: Password yang sudah di-hash pakai bcrypt
- role: Role user (admin_prodi, admin_pusat, dosen, mahasiswa)
- created_at, updated_at: Timestamp

2. Tabel mahasiswa
Menyimpan data mahasiswa. Tabel ini terpisah dari users tapi email-nya harus sama dengan users untuk sinkronisasi.
- id: Primary key
- nim: Nomor Induk Mahasiswa (unik)
- nama: Nama lengkap mahasiswa
- email: Email unik (harus sama dengan email di tabel users)
- prodi: Program studi
- angkatan: Tahun masuk (4 digit, misal 2024)
- status: Status mahasiswa (aktif, cuti, lulus, dropout)
- created_at, updated_at, deleted_at: Timestamp (pakai soft delete)

3. Tabel dosen
Menyimpan data dosen. Sama kayak mahasiswa, terpisah dari users tapi email-nya harus sinkron.
- id: Primary key
- nidn: Nomor Induk Dosen Nasional (unik)
- nama: Nama lengkap dosen
- email: Email unik (harus sama dengan email di tabel users)
- prodi: Program studi yang diajar
- jabatan: Jabatan dosen (Lektor, Lektor Kepala, Profesor, dll)
- created_at, updated_at, deleted_at: Timestamp (pakai soft delete)

4. Tabel mata_kuliah
Menyimpan data mata kuliah yang bisa diambil mahasiswa.
- id: Primary key
- kode: Kode mata kuliah (unik, misal TI101)
- nama: Nama mata kuliah
- prodi: Program studi
- sks: Jumlah SKS (1-6)
- semester: Semester berapa mata kuliah ini (1-8)
- dosen_id: Foreign key ke tabel dosen (bisa null)
- kapasitas: Kapasitas kelas (default 40)
- created_at, updated_at, deleted_at: Timestamp (pakai soft delete)

5. Tabel krs
Menyimpan Kartu Rencana Studi mahasiswa. Satu mahasiswa bisa punya banyak KRS untuk semester berbeda.
- id: Primary key
- mahasiswa_id: Foreign key ke tabel mahasiswa
- mata_kuliah_id: Foreign key ke tabel mata_kuliah
- tahun_akademik: Tahun akademik (misal 2024/2025)
- semester: Semester (1 atau 2)
- status: Status KRS (pending, disetujui, ditolak)
- created_at, updated_at: Timestamp
- Unique constraint: Satu mahasiswa tidak bisa ambil mata kuliah yang sama di tahun akademik dan semester yang sama

6. Tabel khs
Menyimpan Kartu Hasil Studi mahasiswa. Ini berisi nilai-nilai yang sudah didapat.
- id: Primary key
- mahasiswa_id: Foreign key ke tabel mahasiswa
- mata_kuliah_id: Foreign key ke tabel mata_kuliah
- tahun_akademik: Tahun akademik
- semester: Semester
- nilai_uts: Nilai UTS (0.00 - 100.00)
- nilai_uas: Nilai UAS (0.00 - 100.00)
- nilai_tugas: Nilai tugas (0.00 - 100.00)
- nilai_akhir: Nilai akhir (rata-rata)
- huruf_mutu: Huruf mutu (A, B, C, D, E)
- created_at, updated_at: Timestamp
- Unique constraint: Satu mahasiswa tidak bisa punya KHS yang sama untuk mata kuliah, tahun akademik, dan semester yang sama

7. Tabel presensi
Menyimpan riwayat presensi mahasiswa.
- id: Primary key
- mahasiswa_id: Foreign key ke tabel mahasiswa
- mata_kuliah_id: Foreign key ke tabel mata_kuliah
- tanggal: Tanggal presensi
- waktu_presensi: Waktu presensi (timestamp)
- status: Status presensi (hadir, izin, sakit, alpha)
- created_at, updated_at: Timestamp

8. Tabel penilaian
Menyimpan penilaian dosen untuk mahasiswa.
- id: Primary key
- dosen_id: Foreign key ke tabel dosen
- mahasiswa_id: Foreign key ke tabel mahasiswa
- mata_kuliah_id: Foreign key ke tabel mata_kuliah
- tahun_akademik: Tahun akademik
- semester: Semester
- nilai_uts, nilai_uas, nilai_tugas, nilai_kehadiran: Nilai-nilai komponen
- nilai_akhir: Nilai akhir
- huruf_mutu: Huruf mutu
- catatan: Catatan dari dosen
- created_at, updated_at: Timestamp
- Unique constraint: Satu dosen tidak bisa kasih penilaian yang sama untuk mahasiswa, mata kuliah, tahun akademik, dan semester yang sama

9. Tabel materi
Menyimpan materi pembelajaran yang diupload dosen.
- id: Primary key
- dosen_id: Foreign key ke tabel dosen
- mata_kuliah_id: Foreign key ke tabel mata_kuliah
- judul: Judul materi
- deskripsi: Deskripsi materi
- file_path: Path file materi (jika ada)
- created_at, updated_at, deleted_at: Timestamp (pakai soft delete)

10. Tabel absensi
Menyimpan data absensi kelas yang dibuat dosen.
- id: Primary key
- dosen_id: Foreign key ke tabel dosen
- mata_kuliah_id: Foreign key ke tabel mata_kuliah
- tanggal: Tanggal absensi
- jam_mulai: Jam mulai kelas
- jam_selesai: Jam selesai kelas (bisa null)
- topik: Topik pembelajaran
- catatan: Catatan dari dosen
- created_at, updated_at: Timestamp

11. Tabel pembayaran_ukt
Menyimpan data pembayaran UKT mahasiswa.
- id: Primary key
- mahasiswa_id: Foreign key ke tabel mahasiswa
- tahun_akademik: Tahun akademik
- semester: Semester
- jumlah: Jumlah pembayaran
- status: Status pembayaran (pending, lunas, gagal)
- tanggal_bayar: Tanggal pembayaran (bisa null)
- created_at, updated_at: Timestamp

*Relasi Antar Tabel
Relasi di database ini pakai foreign key dengan cascade delete. Jadi kalau data utama dihapus, data yang terkait juga ikut terhapus.
1. Mahasiswa punya relasi:
- hasMany KRS (satu mahasiswa punya banyak KRS)
- hasMany KHS (satu mahasiswa punya banyak KHS)
- hasMany Presensi (satu mahasiswa punya banyak presensi)
- hasMany Penilaian (satu mahasiswa punya banyak penilaian)
- hasMany PembayaranUkt (satu mahasiswa punya banyak pembayaran UKT)

2. Dosen punya relasi:
- hasMany MataKuliah (satu dosen bisa mengajar banyak mata kuliah)
- hasMany Materi (satu dosen bisa upload banyak materi)
- hasMany Penilaian (satu dosen bisa kasih banyak penilaian)
- hasMany Absensi (satu dosen bisa buat banyak absensi)

3. MataKuliah punya relasi:
- belongsTo Dosen (satu mata kuliah punya satu dosen pengajar, bisa null)
- hasMany KRS (satu mata kuliah bisa diambil banyak mahasiswa)
- hasMany KHS (satu mata kuliah punya banyak KHS)
- hasMany Presensi (satu mata kuliah punya banyak presensi)
- hasMany Materi (satu mata kuliah punya banyak materi)
- hasMany Penilaian (satu mata kuliah punya banyak penilaian)
- hasMany Absensi (satu mata kuliah punya banyak absensi)

*Sinkronisasi User Account
Sistem ini punya mekanisme sinkronisasi antara tabel users dengan tabel mahasiswa/dosen. Jadi kalau create atau update mahasiswa/dosen, otomatis create atau update user account juga.
Cara kerjanya:
1. Kalau create mahasiswa baru, sistem otomatis create user dengan email yang sama dan password default "password123"
2. Kalau update email mahasiswa, sistem otomatis update email di tabel users juga
3. Kalau update password mahasiswa, sistem otomatis update password di tabel users juga (di-hash pakai bcrypt)
4. Sama kayak mahasiswa, berlaku juga untuk dosen

*Soft Delete
Beberapa tabel pakai soft delete, jadi data tidak benar-benar dihapus dari database. Cuma ditandai dengan deleted_at. Tabel yang pakai soft delete:
- mahasiswa
- dosen
- mata_kuliah
- materi
Kenapa pakai soft delete? Supaya data tidak hilang kalau terhapus tidak sengaja, dan bisa di-restore lagi kalau perlu. Plus untuk audit trail, jadi bisa track kapan data dihapus.

*Index Database
Database sudah dioptimasi dengan index untuk query yang lebih cepat:
- Tabel mahasiswa: index di nim, email, prodi, angkatan, status
- Tabel dosen: index di nidn, email, prodi
- Tabel krs: index di mahasiswa_id, mata_kuliah_id, tahun_akademik
- Tabel khs: index di mahasiswa_id, mata_kuliah_id
- Tabel penilaian: index di dosen_id, mahasiswa_id, mata_kuliah_id
- Tabel absensi: index di dosen_id, mata_kuliah_id, tanggal

*Data Dummy yang dibuat-buat
1. Users: 4 user untuk testing (satu untuk setiap role) plus user yang dibuat otomatis dari seeder mahasiswa dan dosen
2. Mahasiswa: 100 data mahasiswa dummy dengan:
- NIM: 0000000001 sampai 0000000100
- Nama: Nama random dari kombinasi first name dan last name
- Email: Format nama.surname@sakti.ac.id (atau dengan angka kalau duplicate)
- Prodi: Random dari 10 program studi (Teknik Informatika, Sistem Informasi, Teknik Komputer, Manajemen, Akuntansi, Psikologi, Hukum, Kedokteran, Farmasi, Teknik Sipil)
- Angkatan: Random dari 2020, 2021, 2022, 2023, 2024
- Status: Random dari aktif, cuti, lulus, dropout (tapi lebih banyak yang aktif)
3. Dosen: 6 data dosen dummy dengan:
- NIDN: 0012345678 sampai 0012345683
- Nama: Nama dosen dengan gelar
- Email: Format nama.surname@sakti.ac.id
- Prodi: Teknik Informatika atau Sistem Informasi
- Jabatan: Profesor, Lektor Kepala, atau Lektor
4. User Account untuk Mahasiswa dan Dosen: Otomatis dibuat dengan:
- Email: Sama dengan email di tabel mahasiswa/dosen
- Password: password123 (default)
- Role: mahasiswa untuk mahasiswa, dosen untuk dosen

*Email Domain
Semua email mahasiswa dan dosen pakai domain @sakti.ac.id. Jadi format emailnya:
- Mahasiswa: nama.surname@sakti.ac.id (atau dengan angka kalau duplicate)
- Dosen: nama.surname@sakti.ac.id

*Password Default
Password default untuk semua user baru (baik dari seeder maupun dari create manual) adalah "password123". Password ini di-hash pakai bcrypt sebelum disimpan di database.
Kalau create mahasiswa atau dosen baru lewat form, bisa langsung set password baru. Tapi kalau tidak diisi, akan pakai default "password123".

-----Summary-----

1. Soft Delete: Untuk Mahasiswa dan Dosen, delete itu soft delete. Data tidak benar-benar dihapus dari database, cuma ditandai sebagai deleted. Jadi kalau perlu, bisa di-restore lagi.
2. User Account Sync: Kalau create atau update Mahasiswa/Dosen, sistem otomatis create atau update user account di tabel users. Jadi email dan password sinkron antara tabel mahasiswa/dosen dengan tabel users.
3. Route Order: Di backend/routes/api.php, route untuk fitur mahasiswa (krs, khs, presensi) harus diletakkan sebelum route CRUD mahasiswa. Ini untuk menghindari route conflict karena Laravel akan match route yang lebih spesifik dulu.
4. Email Domain: Semua email mahasiswa dan dosen menggunakan domain @sakti.ac.id.
5. Default Password: Kalau create mahasiswa atau dosen baru, password default adalah "password123" (bisa diubah saat create atau update).
6. Pagination Limit: Untuk list data, maksimal 100 item per halaman untuk mencegah abuse.
7. Search dan Filter: Fitur search dan filter tersedia di beberapa endpoint, terutama untuk list mahasiswa. Bisa search berdasarkan NIM, nama, atau email. Bisa filter berdasarkan prodi, status, atau angkatan.
8. Sorting: Bisa sort berdasarkan kolom apapun yang diizinkan. Default sort adalah berdasarkan created_at descending (yang terbaru di atas).
