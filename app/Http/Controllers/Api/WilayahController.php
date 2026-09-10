<?php

namespace App\Http\Controllers\Api; // Sesuaikan dengan lokasi file Anda

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->tipe;
        $code = $request->id;
        
        // ⚠️ PENTING: Anda WAJIB mengganti tulisan di bawah ini dengan API Key asli dari dashboard api.co.id Anda!
        $apiKey = 'sk-dkXs22HLg36mpUUpro0NaUq9LHIQpQ5ZVl868jpuzBKZbYbokg'; 
        
        $baseUrl = 'https://use.api.co.id/regional/indonesia'; //
        $url = '';

        if ($tipe === 'prov') $url = "$baseUrl/provinces"; //[cite: 1]
        if ($tipe === 'kota') $url = "$baseUrl/provinces/$code/regencies"; //[cite: 1]
        if ($tipe === 'kec')  $url = "$baseUrl/regencies/$code/districts"; //[cite: 1]
        if ($tipe === 'kel')  $url = "$baseUrl/districts/$code/villages"; //[cite: 1]

        if (!$url) return response()->json([]);

        $response = Http::withHeaders([
            'x-api-co-id' => $apiKey //[cite: 1]
        ])->get($url);

        // Jika berhasil, kirim data wilayah
        if ($response->successful()) {
            return $response->json('data');
        }

        // Jika gagal (misal karena API Key salah), kirim pesan error asli ke frontend
        return response()->json([
            'error' => 'Gagal akses API. Pastikan API Key benar.',
            'detail' => $response->json('message'),
            'status' => $response->status()
        ], 400);
    }
}