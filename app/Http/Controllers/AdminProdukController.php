<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class AdminProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::all();
        return view('dashboard', compact('produks'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_produk' => 'required|string',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $nama_gambar = time() . '.' . $request->gambar->extension();
        $request->gambar->move(public_path('images/produk'), $nama_gambar);

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'kategori_produk' => $request->kategori_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $nama_gambar,
        ]);

        return redirect()->route('dashboard');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_produk' => 'required|string',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'nama_produk' => $request->nama_produk,
            'kategori_produk' => $request->kategori_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ];

        if ($request->hasFile('gambar')) {
            $nama_gambar = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('images/produk'), $nama_gambar);
            $data['gambar'] = $nama_gambar;
        }

        $produk->update($data);
        return redirect()->route('dashboard');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return redirect()->route('dashboard');
    }
}
