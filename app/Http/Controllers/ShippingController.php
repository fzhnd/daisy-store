<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    public function getShippingCost(Request $request)
    {
        $apiKey = '...';

        $response = Http::withHeaders([
            'x-api-co-id' => $apiKey
        ])->get('https://use.api.co.id/expedition/shipping-cost', [
            'origin_village_code' => '3172051003',
            'de stination_village_code' => $request->destination_village_code,
            'weight' => 1
        ]);

         return $response->json();
    }
}
