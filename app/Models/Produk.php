<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // Beri tahu Laravel primary key kita bernama id_produk
    protected $primaryKey = 'id_produk';

    // Izinkan kolom-kolom ini diisi data dari formulir
    protected $fillable = [
        'nama_produk',
        'kategori_produk',
        'harga',
        'stok',
        'gambar',
    ];
}