<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        
        // Seed in order (important for foreign keys)
        $this->call([
            UserSeeder::class,
            MahasiswaSeeder::class,
            DosenSeeder::class,
            MataKuliahSeeder::class,
            KrsSeeder::class,
            KhsSeeder::class,
            PresensiSeeder::class,
            MateriSeeder::class,
            PenilaianSeeder::class,
            AbsensiSeeder::class,
            PembayaranUktSeeder::class,
            SyncUserAccountsSeeder::class,
        ]);

        $this->command->info('✅ All seeders completed successfully!');
    }
}
