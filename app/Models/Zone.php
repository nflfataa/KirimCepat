<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'cities', 'rate_per_kg'])]
class Zone extends Model
{
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
