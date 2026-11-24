<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TrackingNumberExhaustedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipmentRequest;
use App\Models\Zone;
use App\Services\OngkirService;
use App\Services\ShipmentCreator;
use App\Support\Payload;
use Illuminate\Http\JsonResponse;

class ShipmentController extends Controller
{
    public function store(
        StoreShipmentRequest $request,
        OngkirService $ongkir,
        ShipmentCreator $creator,
    ): JsonResponse {
        $zone = Zone::query()->findOrFail($request->integer('zone_id'));
        $data = $request->validated();
        $breakdown = $ongkir->hitung(
            $zone,
            $data['actual_weight'],
            $data['length'],
            $data['width'],
            $data['height'],
        );

        try {
            $shipment = $creator->create($zone, $data, $breakdown);
        } catch (TrackingNumberExhaustedException $exception) {
            return response()->json(['message' => $exception->getMessage()], 503);
        }

        return response()->json([
            'resi' => $shipment->tracking_number,
            'status' => $shipment->current_status->value,
            'status_label' => $shipment->current_status->label(),
            'ongkir' => Payload::ongkir($zone, $breakdown),
            'dibuat_pada' => $shipment->created_at->toIso8601String(),
            'lacak_url' => route('track.show', $shipment->tracking_number),
        ], 201);
    }
}
