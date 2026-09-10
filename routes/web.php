<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Models\Produk;

Route::get('/', function () {
    // Ambil 4 produk terbaru saja untuk bagian rekomendasi
    $rekomendasi = Produk::latest()->take(4)->get();
    return view('welcome', compact('rekomendasi'));
});

use App\Http\Controllers\AdminProdukController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AdminProdukController::class, 'index'])->name('dashboard');
    
    // Rute Create (Simpan) yang sudah kita buat
    Route::get('/produk/tambah', [AdminProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk/simpan', [AdminProdukController::class, 'store'])->name('produk.store');
    
    // Rute Update (Edit)
    Route::get('/produk/{id}/edit', [AdminProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [AdminProdukController::class, 'update'])->name('produk.update');
    
    // Rute Delete (Hapus)
    Route::delete('/produk/{id}', [AdminProdukController::class, 'destroy'])->name('produk.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\ProdukController;

Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');

Route::get('/kategori/{kategori}', [App\Http\Controllers\ProdukController::class, 'kategori'])->name('kategori.show');

Route::get('/produk/detail/{id}', [App\Http\Controllers\ProdukController::class, 'show'])->name('produk.show');

Route::get('/favorit', [App\Http\Controllers\ProdukController::class, 'favorit'])->name('favorit');

Route::get('/checkout', [App\Http\Controllers\ProdukController::class, 'checkout'])->name('checkout')->middleware('auth');

Route::get('/search', [App\Http\Controllers\ProdukController::class, 'search'])->name('produk.search');

use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\ShippingController;

Route::get('/wilayah', [WilayahController::class, 'index']);
Route::get('/shipping-cost', [ShippingController::class, 'getShippingCost']);

use App\Http\Controllers\RajaOngkirController;

Route::get('/ro/calculate-by-district', [RajaOngkirController::class, 'calculateShipping']);

use App\Http\Controllers\PesananController;

// 1. Rute Dashboard
Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return view('dashboard');
    }
    return redirect()->route('pesanan.index');
})->middleware(['auth'])->name('dashboard');


// 2. Rute Pesanan & Profil
Route::middleware(['auth'])->group(function () {
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');

    Route::post('/pesanan', [PesananController::class, 'store'])->name('pesanan.store');

});