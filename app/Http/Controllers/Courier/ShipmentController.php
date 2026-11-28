<?php

namespace App\Http\Controllers\Courier;

use App\Enums\ShipmentStatus;
use App\Exceptions\StatusTransitionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\Shipment;
use App\Services\StatusTransitionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ShipmentController extends Controller
{
    public function index(): View
    {
        return view('courier.index', [
            'active' => Shipment::query()
                ->with('zone')
                ->where('current_status', '!=', ShipmentStatus::DITERIMA)
                ->orderBy('updated_at')
                ->get(),
            'finished' => Shipment::query()
                ->with('zone')
                ->where('current_status', ShipmentStatus::DITERIMA)
                ->latest('updated_at')
                ->limit(8)
                ->get(),
        ]);
    }

    public function update(
        UpdateStatusRequest $request,
        Shipment $shipment,
        StatusTransitionService $transitions,
    ): RedirectResponse {
        $requested = $request->filled('status')
            ? ShipmentStatus::from($request->string('status')->toString())
            : null;

        try {
            $transitions->advance($shipment, $request->user(), $request->input('notes'), $requested);
        } catch (StatusTransitionException $exception) {
            return redirect()
                ->route('kurir.dashboard')
                ->withErrors(['status' => $exception->getMessage()]);
        }

        return redirect()
            ->route('kurir.dashboard')
            ->with('status', "Resi {$shipment->tracking_number} kini berstatus {$shipment->current_status->value}.");
    }
}
