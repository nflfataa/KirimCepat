<?php

namespace Tests\Feature;

use App\Models\Shipment;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    use RefreshDatabase;

    private function shipment(): Shipment
    {
        $zone = Zone::create(['name' => 'Zona 1', 'cities' => 'Surabaya', 'rate_per_kg' => 8_000]);

        $shipment = Shipment::create([
            'tracking_number' => 'KC20261123000042',
            'sender_name' => 'Rani Puspita',
            'sender_phone' => '081234567890',
            'sender_address' => 'Jl. Rungkut Asri Timur 12',
            'receiver_name' => 'Bagas Pratama',
            'receiver_phone' => '081398765432',
            'receiver_address' => 'Jl. Soekarno-Hatta 45',
            'zone_id' => $zone->id,
            'actual_weight' => 1,
            'length' => 10,
            'width' => 10,
            'height' => 10,
            'volumetric_weight' => 0.17,
            'chargeable_weight' => 1,
            'total_cost' => 8_000,
            'current_status' => 'DIBUAT',
        ]);

        $shipment->histories()->create([
            'status' => 'DIBUAT',
            'notes' => 'Pengiriman dibuat dan resi diterbitkan.',
        ]);

        return $shipment;
    }

    public function test_api_returns_tracking_history_without_private_contact_data(): void
    {
        $this->shipment();

        $response = $this->getJson('/api/lacak/KC20261123000042')
            ->assertOk()
            ->assertJsonPath('resi', 'KC20261123000042')
            ->assertJsonPath('status', 'DIBUAT')
            ->assertJsonPath('pengirim', 'Rani Puspita')
            ->assertJsonPath('penerima', 'Bagas Pratama')
            ->assertJsonCount(1, 'riwayat');

        $body = $response->getContent();
        $this->assertStringNotContainsString('081234567890', $body);
        $this->assertStringNotContainsString('081398765432', $body);
        $this->assertStringNotContainsString('Jl. Rungkut', $body);
        $this->assertStringNotContainsString('Jl. Soekarno', $body);
    }

    public function test_api_returns_404_for_unknown_receipt(): void
    {
        $this->getJson('/api/lacak/KC20261123009999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Resi tidak ditemukan. Periksa kembali 16 karakter nomor resi.');
    }

    public function test_public_page_shows_status_but_not_private_details(): void
    {
        $this->shipment();

        $this->get('/lacak/KC20261123000042')
            ->assertOk()
            ->assertSee('KC20261123000042')
            ->assertSee('Rani Puspita')
            ->assertSee('Bagas Pratama')
            ->assertDontSee('081234567890')
            ->assertDontSee('Jl. Rungkut')
            ->assertDontSee('Jl. Soekarno');
    }

    public function test_public_page_renders_friendly_not_found_instead_of_error(): void
    {
        $this->get('/lacak/KC20261123009999')
            ->assertOk()
            ->assertSee('Resi tidak ditemukan.');
    }

    public function test_invalid_receipt_format_gets_its_own_message(): void
    {
        $this->get('/lacak/ABC123')
            ->assertOk()
            ->assertSee('Format resi belum sesuai.');
    }

    public function test_track_form_redirects_valid_format_to_detail_page(): void
    {
        $this->shipment();

        $this->get('/lacak?resi=kc20261123000042')
            ->assertRedirect('/lacak/KC20261123000042');
    }

    public function test_track_index_renders_without_query(): void
    {
        $this->get('/lacak')
            ->assertOk()
            ->assertSee('Lacak resi');
    }
}
