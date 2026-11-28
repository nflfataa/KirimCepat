<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OngkirRequest;
use App\Models\Zone;
use App\Services\OngkirService;
use App\Support\Payload;
use Illuminate\Http\JsonResponse;

class OngkirController extends Controller
{
    public function store(OngkirRequest $request, OngkirService $ongkir): JsonResponse
    {
        $zone = Zone::query()->findOrFail($request->integer('zone_id'));
        $breakdown = $ongkir->hitung(
            $zone,
            $request->input('actual_weight'),
            $request->input('length'),
            $request->input('width'),
            $request->input('height'),
        );

        return response()->json(Payload::ongkir($zone, $breakdown));
    }
}
