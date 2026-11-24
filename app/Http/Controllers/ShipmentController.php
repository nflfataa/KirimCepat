<?php

namespace App\Http\Controllers;

use App\Exceptions\TrackingNumberExhaustedException;
use App\Http\Requests\StoreShipmentRequest;
use App\Models\Shipment;
use App\Models\Zone;
use App\Services\OngkirService;
use App\Services\ShipmentCreator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ShipmentController extends Controller
{
    public function create(Request $request): View
    {
        $prefill = [];

        foreach (['zone_id', 'actual_weight', 'length', 'width', 'height'] as $field) {
            $value = $request->query($field);
            $prefill[$field] = is_scalar($value) ? (string) $value : '';
        }

        return view('shipments.create', [
            'zones' => Zone::query()->orderBy('id')->get(),
            'prefill' => $prefill,
        ]);
    }

    public function store(
        StoreShipmentRequest $request,
        OngkirService $ongkir,
        ShipmentCreator $creator,
    ): RedirectResponse {
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
            return back()->withInput()->withErrors(['tracking' => $exception->getMessage()]);
        }

        return redirect()->to(URL::temporarySignedRoute(
            'shipments.show',
            now()->addDay(),
            ['shipment' => $shipment->tracking_number],
        ));
    }

    public function show(Shipment $shipment): View
    {
        $shipment->load(['zone', 'histories.courier']);

        return view('shipments.show', ['shipment' => $shipment]);
    }
}
