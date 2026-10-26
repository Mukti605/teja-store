<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ganti lewat .env: ADMIN_EMAIL dan ADMIN_PASSWORD (WAJIB diganti sebelum online).
        $user = User::firstOrNew(['email' => env('ADMIN_EMAIL', 'admin@tejastore.test')]);

        // forceFill dipakai karena is_admin sengaja tidak mass-assignable.
        $user->forceFill([
            'name' => 'Admin Teja Store',
            'password' => env('ADMIN_PASSWORD', 'Admin@12345'), // di-hash otomatis oleh cast 'hashed'
            'is_admin' => true,
        ])->save();
    }
}
