<?php

namespace Tests\Feature;

use App\Enums\ShipmentStatus;
use App\Exceptions\StatusTransitionException;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Zone;
use App\Services\StatusTransitionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusTransitionTest extends TestCase
{
    use RefreshDatabase;

    private function zone(): Zone
    {
        return Zone::create(['name' => 'Zona Uji', 'cities' => 'Surabaya', 'rate_per_kg' => 8_000]);
    }

    private function shipment(string $status = 'DIBUAT'): Shipment
    {
        $shipment = Shipment::create([
            'tracking_number' => 'KC20261123000042',
            'sender_name' => 'Rani',
            'sender_phone' => '081234567890',
            'sender_address' => 'Surabaya',
            'receiver_name' => 'Bagas',
            'receiver_phone' => '081398765432',
            'receiver_address' => 'Malang',
            'zone_id' => $this->zone()->id,
            'actual_weight' => 1,
            'length' => 10,
            'width' => 10,
            'height' => 10,
            'volumetric_weight' => 0.17,
            'chargeable_weight' => 1,
            'total_cost' => 8_000,
            'current_status' => $status,
        ]);

        $shipment->histories()->create(['status' => $status]);

        return $shipment;
    }

    public function test_one_step_advance_records_courier_and_note(): void
    {
        $courier = User::factory()->create();
        $shipment = $this->shipment();

        (new StatusTransitionService)->advance($shipment, $courier, 'Paket dijemput.');

        $this->assertSame('DIJEMPUT', $shipment->current_status->value);

        $history = $shipment->histories->last();
        $this->assertSame('DIJEMPUT', $history->status->value);
        $this->assertSame($courier->id, $history->courier_id);
        $this->assertSame('Paket dijemput.', $history->notes);
    }

    public function test_status_cannot_skip_a_step(): void
    {
        $courier = User::factory()->create();
        $shipment = $this->shipment();

        $this->expectException(StatusTransitionException::class);
        $this->expectExceptionMessage('Status hanya bisa maju satu langkah. Status berikutnya adalah DIJEMPUT.');

        (new StatusTransitionService)->advance(
            $shipment,
            $courier,
            null,
            ShipmentStatus::DITERIMA,
        );
    }

    public function test_final_status_cannot_advance(): void
    {
        $courier = User::factory()->create();
        $shipment = $this->shipment('DITERIMA');

        $this->expectException(StatusTransitionException::class);

        (new StatusTransitionService)->advance($shipment, $courier);
    }

    public function test_api_requires_an_authenticated_courier(): void
    {
        $this->shipment();

        $this->patchJson('/api/pengiriman/KC20261123000042/status')
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Masuk sebagai kurir untuk memperbarui status paket.');
    }

    public function test_api_advances_status_for_authenticated_courier(): void
    {
        $courier = User::factory()->create(['name' => 'Kurir Demo']);
        $this->shipment();

        $this->actingAs($courier)
            ->patchJson('/api/pengiriman/KC20261123000042/status', ['notes' => 'Dijemput pukul 21.10'])
            ->assertOk()
            ->assertJsonPath('status', 'DIJEMPUT')
            ->assertJsonPath('riwayat.1.kurir', 'Kurir Demo')
            ->assertJsonPath('riwayat.1.catatan', 'Dijemput pukul 21.10');
    }

    public function test_api_rejects_a_status_jump_with_conflict(): void
    {
        $courier = User::factory()->create();
        $this->shipment();

        $this->actingAs($courier)
            ->patchJson('/api/pengiriman/KC20261123000042/status', ['status' => 'DITERIMA'])
            ->assertStatus(409)
            ->assertJsonPath('message', 'Status hanya bisa maju satu langkah. Status berikutnya adalah DIJEMPUT.');
    }

    public function test_api_rejects_unknown_status_value(): void
    {
        $courier = User::factory()->create();
        $this->shipment();

        $this->actingAs($courier)
            ->patchJson('/api/pengiriman/KC20261123000042/status', ['status' => 'HILANG'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function test_api_returns_friendly_404_for_unknown_receipt(): void
    {
        $courier = User::factory()->create();

        $this->actingAs($courier)
            ->patchJson('/api/pengiriman/KC20261123009999/status')
            ->assertNotFound()
            ->assertJsonPath('message', 'Resi tidak ditemukan. Periksa kembali 16 karakter nomor resi.');
    }

    public function test_web_dashboard_update_advances_and_flashes_status(): void
    {
        $courier = User::factory()->create();
        $this->shipment();

        $this->actingAs($courier)
            ->patch('/kurir/pengiriman/KC20261123000042/status', ['notes' => 'Paket dijemput.'])
            ->assertRedirect('/kurir')
            ->assertSessionHas('status', 'Resi KC20261123000042 kini berstatus DIJEMPUT.');

        $this->assertSame('DIJEMPUT', $this->shipmentRecord()->current_status->value);
    }

    private function shipmentRecord(): Shipment
    {
        return Shipment::query()->where('tracking_number', 'KC20261123000042')->firstOrFail();
    }
}
