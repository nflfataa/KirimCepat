<?php

namespace App\Services;

use App\Models\Zone;
use InvalidArgumentException;

final class OngkirService
{
    public function hitung(
        Zone $zone,
        int|float|string $actualWeight,
        int|float|string $length,
        int|float|string $width,
        int|float|string $height,
    ): OngkirBreakdown {
        $actualHundredths = $this->toHundredths($actualWeight);
        $lengthHundredths = $this->toHundredths($length);
        $widthHundredths = $this->toHundredths($width);
        $heightHundredths = $this->toHundredths($height);

        if ($actualHundredths < 1 || $lengthHundredths < 1 || $widthHundredths < 1 || $heightHundredths < 1) {
            throw new InvalidArgumentException('Berat dan dimensi harus lebih dari 0.');
        }

        // Volume cm3 / 6000 kg, dihitung dalam gram agar tidak memakai pecahan biner.
        $volume = $lengthHundredths * $widthHundredths * $heightHundredths;
        $volumetricGrams = intdiv($volume + 5_999_999, 6_000_000);
        $actualGrams = $actualHundredths * 10;
        $chargeableWeight = intdiv(max($actualGrams, $volumetricGrams) + 999, 1000);
        $ratePerKg = (int) $zone->rate_per_kg;

        return new OngkirBreakdown(
            actualWeight: $actualHundredths / 100,
            volumetricWeight: round($volumetricGrams / 1000, 2),
            chargeableWeight: $chargeableWeight,
            ratePerKg: $ratePerKg,
            totalCost: $chargeableWeight * $ratePerKg,
        );
    }

    private function toHundredths(int|float|string $value): int
    {
        $normalized = str_replace(',', '.', (string) $value);

        return (int) round((float) $normalized * 100);
    }
}
