<?php

namespace App\Support;

use App\Models\Shipment;
use App\Models\Zone;
use App\Services\OngkirBreakdown;

final class Payload
{
    public static function ongkir(Zone $zone, OngkirBreakdown $breakdown): array
    {
        return [
            'zone' => [
                'id' => $zone->id,
                'name' => $zone->name,
                'rate_per_kg' => (int) $zone->rate_per_kg,
            ],
            'actual_weight_kg' => $breakdown->actualWeight,
            'volumetric_weight_kg' => $breakdown->volumetricWeight,
            'chargeable_weight_kg' => $breakdown->chargeableWeight,
            'rate_per_kg' => $breakdown->ratePerKg,
            'total_cost' => $breakdown->totalCost,
        ];
    }

    public static function tracking(Shipment $shipment): array
    {
        $shipment->loadMissing(['zone', 'histories.courier']);

        return [
            'resi' => $shipment->tracking_number,
            'status' => $shipment->current_status->value,
            'status_label' => $shipment->current_status->label(),
            'pengirim' => $shipment->sender_name,
            'penerima' => $shipment->receiver_name,
            'zona' => $shipment->zone->name,
            'berat' => [
                'aktual_kg' => (float) $shipment->actual_weight,
                'volumetrik_kg' => (float) $shipment->volumetric_weight,
                'dikenakan_kg' => $shipment->chargeable_weight,
            ],
            'ongkir' => $shipment->total_cost,
            'riwayat' => $shipment->histories->map(fn ($history) => [
                'status' => $history->status->value,
                'status_label' => $history->status->label(),
                'waktu' => $history->created_at->toIso8601String(),
                'kurir' => $history->courier?->name,
                'catatan' => $history->notes,
            ])->values()->all(),
        ];
    }
}
