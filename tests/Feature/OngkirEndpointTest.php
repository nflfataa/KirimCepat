<?php

namespace Tests\Feature;

use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OngkirEndpointTest extends TestCase
{
    use RefreshDatabase;

    private function zone(): Zone
    {
        return Zone::create(['name' => 'Zona 1 - Gerbangkertosusila', 'cities' => 'Surabaya', 'rate_per_kg' => 8_000]);
    }

    public function test_returns_breakdown_for_valid_package(): void
    {
        $this->postJson('/api/ongkir', [
            'zone_id' => $this->zone()->id,
            'actual_weight' => '2,3',
            'length' => 20,
            'width' => 20,
            'height' => 20,
        ])
            ->assertOk()
            ->assertExactJson([
                'zone' => [
                    'id' => 1,
                    'name' => 'Zona 1 - Gerbangkertosusila',
                    'rate_per_kg' => 8000,
                ],
                'actual_weight_kg' => 2.3,
                'volumetric_weight_kg' => 1.33,
                'chargeable_weight_kg' => 3,
                'rate_per_kg' => 8000,
                'total_cost' => 24000,
            ]);
    }

    public function test_rejects_missing_fields_with_indonesian_messages(): void
    {
        $this->postJson('/api/ongkir', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'zone_id' => 'Zona tujuan wajib dipilih.',
                'actual_weight' => 'Berat aktual wajib diisi.',
            ]);
    }

    public function test_rejects_zero_weight(): void
    {
        $this->postJson('/api/ongkir', [
            'zone_id' => $this->zone()->id,
            'actual_weight' => 0,
            'length' => 10,
            'width' => 10,
            'height' => 10,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['actual_weight' => 'Berat aktual harus lebih dari 0.']);
    }
}
