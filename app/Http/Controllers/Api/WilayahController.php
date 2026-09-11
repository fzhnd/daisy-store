<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->tipe;
        $code = $request->id;

        $apiKey = '...';

        $baseUrl = 'https://use.api.co.id/regional/indonesia';
        $url = '';

        if ($tipe === 'prov') $url = "$baseUrl/provinces";
        if ($tipe === 'kota') $url = "$baseUrl/provinces/$code/regencies";
        if ($tipe === 'kec')  $url = "$baseUrl/regencies/$code/districts";
        if ($tipe === 'kel')  $url = "$baseUrl/districts/$code/villages";

        if (!$url) return response()->json([]);

        $response = Http::withHeaders([
            'x-api-co-id' => $apiKey
        ])->get($url);

        if ($response->successful()) {
            return $response->json('data');
        }

        return response()->json([
            'error' => 'Gagal akses API. Pastikan API Key benar.',
            'detail' => $response->json('message'),
            'status' => $response->status()
        ], 400);
    }
}
