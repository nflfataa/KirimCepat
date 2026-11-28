<?php

namespace Tests\Feature;

use App\Models\Shipment;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ShipmentCreationTest extends TestCase
{
    use RefreshDatabase;

    private function zone(): Zone
    {
        return Zone::create([
            'name' => 'Zona 2 - Malang Raya',
            'cities' => 'Malang, Batu',
            'rate_per_kg' => 12_000,
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'zone_id' => $this->zone()->id,
            'sender_name' => 'Rani Puspita',
            'sender_phone' => '0812 3456 7890',
            'sender_address' => 'Jl. Rungkut Asri Timur 12, Surabaya',
            'receiver_name' => 'Bagas Pratama',
            'receiver_phone' => '0813 9876 5432',
            'receiver_address' => 'Jl. Soekarno-Hatta 45, Malang',
            'actual_weight' => '1',
            'length' => '40',
            'width' => '30',
            'height' => '20',
        ], $overrides);
    }

    public function test_web_form_creates_shipment_with_generated_tracking_number(): void
    {
        $response = $this->post('/kirim', $this->payload());

        $shipment = Shipment::sole();

        $response->assertRedirect();
        $this->assertMatchesRegularExpression('/^KC[0-9]{14}$/', $shipment->tracking_number);
        $this->assertStringContainsString($shipment->tracking_number, $response->headers->get('Location'));
        $this->assertSame('DIBUAT', $shipment->current_status->value);
    }

    public function test_ongkir_is_calculated_server_side_and_matches_stored_totals(): void
    {
        $this->post('/kirim', $this->payload(['actual_weight' => '1.01', 'length' => 10, 'width' => 10, 'height' => 10]));

        $shipment = Shipment::sole();

        $this->assertSame('1.01', $shipment->actual_weight);
        $this->assertSame(2, $shipment->chargeable_weight);
        $this->assertSame(24_000, $shipment->total_cost);
        $this->assertSame(12_000, (int) $shipment->zone->rate_per_kg);
    }

    public function test_each_shipment_receives_an_increasing_tracking_number(): void
    {
        $this->post('/kirim', $this->payload());
        $this->post('/kirim', $this->payload());

        $numbers = Shipment::orderBy('id')->pluck('tracking_number');

        $this->assertSame((int) substr($numbers[0], -6) + 1, (int) substr($numbers[1], -6));
    }

    public function test_initial_history_entry_is_recorded(): void
    {
        $this->post('/kirim', $this->payload());

        $history = Shipment::sole()->histories()->sole();

        $this->assertSame('DIBUAT', $history->status->value);
        $this->assertNull($history->courier_id);
    }

    public function test_invalid_package_data_redirects_back_with_indonesian_errors(): void
    {
        $response = $this->from('/kirim')->post('/kirim', $this->payload(['actual_weight' => '0']));

        $response->assertRedirect('/kirim');
        $response->assertSessionHasErrors(['actual_weight' => 'Berat aktual harus lebih dari 0.']);
        $this->assertDatabaseCount('shipments', 0);
    }

    public function test_receipt_page_requires_a_valid_signature(): void
    {
        $this->post('/kirim', $this->payload());

        $this->get('/kirim/'.Shipment::sole()->tracking_number)->assertForbidden();
    }

    public function test_signed_receipt_page_shows_the_shipment(): void
    {
        $this->post('/kirim', $this->payload());

        $shipment = Shipment::sole();
        $url = URL::temporarySignedRoute(
            'shipments.show',
            now()->addDay(),
            ['shipment' => $shipment->tracking_number],
        );

        $this->get($url)
            ->assertOk()
            ->assertSee($shipment->tracking_number)
            ->assertSee('Rp48.000', false);
    }

    public function test_api_creates_shipment_and_returns_tracking_payload(): void
    {
        $response = $this->postJson('/api/pengiriman', $this->payload());

        $response->assertCreated()
            ->assertJsonPath('status', 'DIBUAT')
            ->assertJsonPath('ongkir.chargeable_weight_kg', 4)
            ->assertJsonPath('ongkir.total_cost', 48_000)
            ->assertJsonPath('ongkir.zone.rate_per_kg', 12_000);

        $this->assertMatchesRegularExpression('/^KC[0-9]{14}$/', $response->json('resi'));
        $this->assertSame(route('track.show', $response->json('resi')), $response->json('lacak_url'));
    }

    public function test_api_rejects_unknown_zone(): void
    {
        $this->postJson('/api/pengiriman', $this->payload(['zone_id' => 999]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['zone_id' => 'Zona tujuan yang dipilih tidak tersedia.']);
    }
}
