<?php

namespace App\Services;

use App\Enums\ShipmentStatus;
use App\Models\Shipment;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;

final class ShipmentCreator
{
    public function __construct(private readonly TrackingNumberGenerator $trackingNumbers) {}

    public function create(Zone $zone, array $data, OngkirBreakdown $breakdown): Shipment
    {
        return DB::transaction(function () use ($zone, $data, $breakdown) {
            $shipment = Shipment::create([
                'tracking_number' => $this->trackingNumbers->next(),
                'sender_name' => $data['sender_name'],
                'sender_phone' => $data['sender_phone'],
                'sender_address' => $data['sender_address'],
                'receiver_name' => $data['receiver_name'],
                'receiver_phone' => $data['receiver_phone'],
                'receiver_address' => $data['receiver_address'],
                'zone_id' => $zone->id,
                'actual_weight' => $breakdown->actualWeight,
                'length' => $data['length'],
                'width' => $data['width'],
                'height' => $data['height'],
                'volumetric_weight' => $breakdown->volumetricWeight,
                'chargeable_weight' => $breakdown->chargeableWeight,
                'total_cost' => $breakdown->totalCost,
                'current_status' => ShipmentStatus::DIBUAT,
            ]);

            $shipment->histories()->create([
                'status' => ShipmentStatus::DIBUAT,
                'notes' => 'Pengiriman dibuat dan resi diterbitkan.',
            ]);

            return $shipment->load('zone');
        });
    }
}
