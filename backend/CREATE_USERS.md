# Membuat User Testing

## Opsi 1: Menggunakan Script (Recommended)

Jalankan script yang sudah dibuat:

```bash
cd backend
php create_users.php
```

Script ini akan membuat 4 user:
- ✅ Admin Prodi: `admin@test.com` / `password123`
- ✅ Admin Pusat: `adminpusat@test.com` / `password123`
- ✅ Dosen: `dosen@test.com` / `password123`
- ✅ Mahasiswa: `mahasiswa@test.com` / `password123`

## Opsi 2: Menggunakan Tinker

```bash
cd backend
php artisan tinker
```

Kemudian jalankan:

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Admin Prodi
User::create([
    'name' => 'Admin Prodi',
    'email' => 'admin@test.com',
    'password' => Hash::make('password123'),
    'role' => 'admin_prodi',
]);

// Admin Pusat
User::create([
    'name' => 'Admin Pusat',
    'email' => 'adminpusat@test.com',
    'password' => Hash::make('password123'),
    'role' => 'admin_pusat',
]);

// Dosen
User::create([
    'name' => 'Dosen',
    'email' => 'dosen@test.com',
    'password' => Hash::make('password123'),
    'role' => 'dosen',
]);

// Mahasiswa
User::create([
    'name' => 'Mahasiswa',
    'email' => 'mahasiswa@test.com',
    'password' => Hash::make('password123'),
    'role' => 'mahasiswa',
]);

exit
```

## Login Credentials

Setelah user dibuat, gunakan credentials berikut untuk login:

| Role | Email | Password |
|------|-------|----------|
| Admin Prodi | `admin@test.com` | `password123` |
| Admin Pusat | `adminpusat@test.com` | `password123` |
| Dosen | `dosen@test.com` | `password123` |
| Mahasiswa | `mahasiswa@test.com` | `password123` |

## Fitur per Role

### Admin Prodi
- ✅ Login/Logout
- ✅ Dashboard
- ✅ Profile
- ✅ **CRUD Mahasiswa** (Create, Read, Update, Delete)

### Admin Pusat
- ✅ Login/Logout
- ✅ Dashboard
- ✅ Profile
- ⏳ Integrasi PDDIKTI (akan dikembangkan)
- ⏳ Payment UKT (akan dikembangkan)
- ⏳ SSO (akan dikembangkan)

### Dosen
- ✅ Login/Logout
- ✅ Dashboard
- ✅ Profile
- ⏳ Penilaian (akan dikembangkan)
- ⏳ Materi (akan dikembangkan)
- ⏳ Absensi (akan dikembangkan)

### Mahasiswa
- ✅ Login/Logout
- ✅ Dashboard
- ✅ Profile
- ⏳ KRS (akan dikembangkan)
- ⏳ KHS (akan dikembangkan)
- ⏳ Presensi (akan dikembangkan)

## Catatan

⚠️ **PENTING:** Password `password123` hanya untuk development/testing. 
Di production, gunakan password yang kuat dan unik untuk setiap user!
