<?php

namespace App\Http\Controllers\Api;

use App\Enums\ShipmentStatus;
use App\Exceptions\StatusTransitionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\Shipment;
use App\Services\StatusTransitionService;
use App\Support\Payload;
use Illuminate\Http\JsonResponse;

class StatusController extends Controller
{
    public function update(
        UpdateStatusRequest $request,
        Shipment $shipment,
        StatusTransitionService $transitions,
    ): JsonResponse {
        $requested = $request->filled('status')
            ? ShipmentStatus::from($request->string('status')->toString())
            : null;

        try {
            $transitions->advance($shipment, $request->user(), $request->input('notes'), $requested);
        } catch (StatusTransitionException $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }

        return response()->json(Payload::tracking($shipment));
    }
}
