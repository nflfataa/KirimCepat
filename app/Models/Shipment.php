<?php

namespace App\Models;

use App\Enums\ShipmentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'tracking_number',
    'sender_name',
    'sender_phone',
    'sender_address',
    'receiver_name',
    'receiver_phone',
    'receiver_address',
    'zone_id',
    'actual_weight',
    'length',
    'width',
    'height',
    'volumetric_weight',
    'chargeable_weight',
    'total_cost',
    'current_status',
])]
class Shipment extends Model
{
    protected function casts(): array
    {
        return [
            'actual_weight' => 'decimal:2',
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'volumetric_weight' => 'decimal:2',
            'chargeable_weight' => 'integer',
            'total_cost' => 'integer',
            'current_status' => ShipmentStatus::class,
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'tracking_number';
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ShipmentHistory::class)->orderBy('created_at')->orderBy('id');
    }
}
