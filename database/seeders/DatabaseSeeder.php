<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            ProductSeeder::class,
        ]);

        // Transaksi contoh hanya untuk lingkungan lokal.
        if (app()->environment('local')) {
            $this->call(DemoOrderSeeder::class);
        }
    }
}
