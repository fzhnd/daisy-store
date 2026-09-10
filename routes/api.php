<?php

use App\Http\Controllers\Api\WilayahController;

Route::get('/wilayah', [WilayahController::class, 'index']);

use App\Http\Controllers\RajaOngkirController;

Route::get('/ro/calculate-by-district', [RajaOngkirController::class, 'calculateByEmsifaDistrict']);