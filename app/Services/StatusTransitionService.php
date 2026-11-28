<?php

namespace App\Services;

use App\Enums\ShipmentStatus;
use App\Exceptions\StatusTransitionException;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class StatusTransitionService
{
    public function advance(Shipment $shipment, User $courier, ?string $notes = null, ?ShipmentStatus $requested = null): Shipment
    {
        $next = $shipment->current_status->next();

        if ($next === null) {
            throw StatusTransitionException::finalStatus();
        }

        if ($requested !== null && $requested !== $next) {
            throw StatusTransitionException::mustAdvanceOneStep($next->value);
        }

        DB::transaction(function () use ($shipment, $courier, $notes, $next) {
            $shipment->update(['current_status' => $next]);

            $shipment->histories()->create([
                'status' => $next,
                'courier_id' => $courier->id,
                'notes' => $notes,
            ]);
        });

        return $shipment->refresh();
    }
}
