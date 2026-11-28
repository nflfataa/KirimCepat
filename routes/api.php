<?php

use App\Http\Controllers\Api;
use App\Http\Middleware\AuthenticateCourier;
use Illuminate\Support\Facades\Route;

Route::post('/ongkir', [Api\OngkirController::class, 'store']);
Route::post('/pengiriman', [Api\ShipmentController::class, 'store']);
Route::get('/lacak/{resi}', [Api\TrackingController::class, 'show']);
Route::patch('/pengiriman/{shipment}/status', [Api\StatusController::class, 'update'])
    ->middleware(AuthenticateCourier::class)
    ->missing(fn () => response()->json([
        'message' => 'Resi tidak ditemukan. Periksa kembali 16 karakter nomor resi.',
    ], 404));
