<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produkBaru = Produk::latest()->take(10)->get(); 
        $pakaian = Produk::where('kategori_produk', 'Pakaian')->take(10)->get();
        $aksesoris = Produk::where('kategori_produk', 'Aksesoris')->take(10)->get();
        $alatTulis = Produk::where('kategori_produk', 'Alat Tulis')->take(10)->get();

        return view('produk.index', compact('produkBaru', 'pakaian', 'aksesoris', 'alatTulis'));
    }

    public function kategori($kategori)
    {
        $produks = Produk::where('kategori_produk', $kategori)->get();
        
        return view('produk.kategori', compact('produks', 'kategori'));
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        $terakhirDilihat = Produk::where('id_produk', '!=', $id)->latest()->take(5)->get();

        return view('produk.show', compact('produk', 'terakhirDilihat'));
    }

    public function favorit()
    {
        $produks = Produk::all();
        
        return view('produk.favorit', compact('produks'));
    }

    public function checkout()
    {
        return view('produk.checkout');
    }

    public function search(\Illuminate\Http\Request $request)
    {
        $kata_kunci = $request->input('q');    
        $produks = Produk::where('nama_produk', 'like', "%{$kata_kunci}%")->get();
        
        return view('produk.search', compact('produks', 'kata_kunci'));
    }
}