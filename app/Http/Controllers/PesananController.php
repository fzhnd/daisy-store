<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'All');

        $query = Pesanan::where('user_id', auth()->id())->with('detail.produk');

        if ($status !== 'All') {
            $query->where('status', str_replace('_', ' ', $status));
        }

        $pesanan = $query->latest()->get();
        return view('user.pesanan', compact('pesanan', 'status'));
    }

    public function store(Request $request)
    {
        session()->forget('cart'); 

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dibuat!');
    }
}
