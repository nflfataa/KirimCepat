<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Support\Payload;
use Illuminate\Http\JsonResponse;

class TrackingController extends Controller
{
    public function show(string $resi): JsonResponse
    {
        $resi = preg_replace('/\s+/', '', strtoupper(trim($resi)));

        $shipment = preg_match('/^KC[0-9]{14}$/', $resi)
            ? Shipment::query()
                ->with(['zone', 'histories.courier'])
                ->where('tracking_number', $resi)
                ->first()
            : null;

        if ($shipment === null) {
            return response()->json([
                'message' => 'Resi tidak ditemukan. Periksa kembali 16 karakter nomor resi.',
            ], 404);
        }

        return response()->json(Payload::tracking($shipment));
    }
}
