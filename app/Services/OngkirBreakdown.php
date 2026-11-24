<?php

namespace App\Services;

final readonly class OngkirBreakdown
{
    public function __construct(
        public float $actualWeight,
        public float $volumetricWeight,
        public int $chargeableWeight,
        public int $ratePerKg,
        public int $totalCost,
    ) {}
}
