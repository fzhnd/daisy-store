<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    public function calculateShipping(Request $request)
    {
        $apiKey = '85wARTqA537029e6f0c9ccd7exiwxFO0'; 
        $kodepos = $request->kodepos;

        $search = Http::withHeaders(['key' => $apiKey])
            ->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $kodepos
            ]);

        $searchData = $search->json();

        if (!$search->successful() || empty($searchData['data'])) {
            return response()->json([
                'pesan' => 'Gagal mencari lokasi tujuan',
                'detail' => $searchData 
            ], 400); 
        }

        $destination_id = $searchData['data'][0]['id'];

        $calc = Http::withHeaders(['key' => $apiKey])
            ->asForm()
            ->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'origin' => $destination_id, // UBAH SEMENTARA JADI $destination_id
                'destination' => $destination_id,
                'weight' => 1000, 
                'courier' => 'jne:sicepat:jnt' // Kita coba 1 kurir dulu agar tidak bentrok
            ]);

        $calcData = $calc->json();

        if (!$calc->successful() || empty($calcData['data'])) {
            return response()->json([
                'pesan' => 'Gagal menghitung ongkir',
                'detail' => $calcData 
            ], 400); 
        }

        $formattedOptions = [];
        foreach ($calcData['data'] as $option) {
            $kurir = strtoupper($option['code'] ?? 'KURIR');
            $layanan = strtoupper($option['service'] ?? 'REG');

            if ($kurir === 'JNE') {
                if (!in_array($layanan, ['CTC', 'CTCYES'])) {
                    continue;
                }
            }

            $formattedOptions[] = [
                'shipping_name' => $kurir,
                'service_name' => $option['service'] ?? 'REG',
                'shipping_cost_net' => $option['cost'] ?? 0,
                'etd' => $option['etd'] ?? '-'
            ];
        }

        return response()->json([
            'data' => [
                'calculate_reguler' => $formattedOptions
            ]
        ]);
    }
}