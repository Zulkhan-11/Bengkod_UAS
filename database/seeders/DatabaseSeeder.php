<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil seeder dalam bentuk array.
        // Ini adalah praktik terbaik karena memudahkan Anda untuk
        // menambahkan seeder lain di masa depan.
        $this->call([
            UserSeeder::class,
            // contoh: PoliSeeder::class,
            // contoh: ObatSeeder::class,
        ]);
    }
}
