<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Akun Admin
        User::create([
            'name' => 'Admin Daisy',
            'email' => 'admin@daisy.com',
            'password' => Hash::make('password'), // Sandinya: password
            'role' => 'admin',
        ]);

        // 2. Membuat Akun Pembeli (User)
        User::create([
            'name' => 'Pelanggan Setia',
            'email' => 'user@daisy.com',
            'password' => Hash::make('password'), // Sandinya: password
            'role' => 'user',
        ]);

        // 3. Memanggil Seeder Produk yang sudah kamu buat sebelumnya
        $this->call([
            ProdukSeeder::class,
        ]);
    }
}