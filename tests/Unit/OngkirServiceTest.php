<?php

namespace Tests\Unit;

use App\Models\Zone;
use App\Services\OngkirService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class OngkirServiceTest extends TestCase
{
    private function zone(int $rate = 8000): Zone
    {
        $zone = new Zone(['name' => 'Zona Uji', 'cities' => 'Surabaya', 'rate_per_kg' => $rate]);
        $zone->id = 1;

        return $zone;
    }

    public function test_actual_weight_above_one_kilogram_rounds_up(): void
    {
        $breakdown = (new OngkirService)->hitung($this->zone(), '1.01', 10, 10, 10);

        $this->assertSame(1.01, $breakdown->actualWeight);
        $this->assertSame(2, $breakdown->chargeableWeight);
        $this->assertSame(16_000, $breakdown->totalCost);
    }

    public function test_exactly_one_kilogram_stays_one_chargeable_kilogram(): void
    {
        $breakdown = (new OngkirService)->hitung($this->zone(), 1, 10, 10, 10);

        $this->assertSame(1, $breakdown->chargeableWeight);
        $this->assertSame(8_000, $breakdown->totalCost);
    }

    public function test_volumetric_weight_wins_when_larger(): void
    {
        $breakdown = (new OngkirService)->hitung($this->zone(12_000), 1, 40, 30, 20);

        $this->assertSame(4.0, $breakdown->volumetricWeight);
        $this->assertSame(4, $breakdown->chargeableWeight);
        $this->assertSame(48_000, $breakdown->totalCost);
    }

    public function test_volumetric_weight_is_reported_in_kilograms(): void
    {
        $breakdown = (new OngkirService)->hitung($this->zone(), 2.3, 20, 20, 20);

        $this->assertSame(1.33, $breakdown->volumetricWeight);
        $this->assertSame(3, $breakdown->chargeableWeight);
        $this->assertSame(24_000, $breakdown->totalCost);
    }

    public function test_comma_decimals_are_accepted(): void
    {
        $breakdown = (new OngkirService)->hitung($this->zone(), '2,5', '10,5', 10, 10);

        $this->assertSame(2.5, $breakdown->actualWeight);
        $this->assertSame(3, $breakdown->chargeableWeight);
    }

    public function test_zero_dimension_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new OngkirService)->hitung($this->zone(), 1, 0, 10, 10);
    }
}
