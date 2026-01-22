# Quick Setup Database - Step 2

## 1. Buat Database PostgreSQL

```sql
-- Login ke PostgreSQL
psql -U postgres

-- Buat database
CREATE DATABASE saktimini;

-- Keluar
\q
```

## 2. Setup .env File

```bash
cd backend

# Copy .env.example ke .env (jika belum ada)
copy .env.example .env

# Edit .env dan ubah bagian database:
```

**Edit file `.env`:**

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=saktimini
DB_USERNAME=postgres
DB_PASSWORD=password_postgres_anda
```

## 3. Generate Keys

```bash
php artisan key:generate
php artisan jwt:secret
```

## 4. Jalankan Migrations

```bash
php artisan migrate
```

## 5. (Opsional) Buat User Test

```bash
php artisan tinker
```

Kemudian jalankan:

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin Prodi',
    'email' => 'admin@test.com',
    'password' => Hash::make('password123'),
    'role' => 'admin_prodi',
]);

exit
```

## Selesai! ✅

Database sudah siap digunakan.
