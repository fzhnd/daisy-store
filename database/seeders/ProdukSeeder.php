<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk; // Pastikan memanggil Model Produk

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        Produk::create([
            'id_produk' => 1,
            'nama_produk' => '',
            'kategori_produk' => 'Pakaian',
            'stok' => 100,
            'harga' => 165000,
            'gambar' => '1.jpg'
        ]);

        Produk::create([
            'id_produk' => 3,
            'nama_produk' => '',
            'kategori_produk' => 'Aksesoris',
            'stok' => 100,
            'harga' =>65000,
            'gambar' => '6.jpg'
        ]);

        Produk::create([
            'id_produk' => 4,
            'nama_produk' => '',
            'kategori_produk' => 'Alat Tulis',
            'stok' => 100,
            'harga' => 5000,
            'gambar' => '7.jpg'
        ]);
    }
}