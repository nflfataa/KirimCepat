<?php

namespace Tests\Feature;

use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_lists_seeded_zones_and_the_calculator(): void
    {
        Zone::create(['name' => 'Zona 1 - Gerbangkertosusila', 'cities' => 'Surabaya, Sidoarjo', 'rate_per_kg' => 8_000]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Cek ongkir')
            ->assertSee('Zona 1 - Gerbangkertosusila')
            ->assertSee('Rp8.000')
            ->assertSee('Punya nomor resi?');
    }

    public function test_shipment_form_prefills_package_fields_from_query(): void
    {
        Zone::create(['name' => 'Zona 1', 'cities' => 'Surabaya', 'rate_per_kg' => 8_000]);

        $this->get('/kirim?actual_weight=2.3&length=20&width=20&height=20')
            ->assertOk()
            ->assertSee('value="2.3"', false);
    }

    public function test_pages_use_kirimcepat_only_as_document_title(): void
    {
        Zone::create(['name' => 'Zona 1', 'cities' => 'Surabaya', 'rate_per_kg' => 8_000]);

        foreach (['/', '/kirim', '/lacak', '/kurir/masuk', '/health'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('<title>KirimCepat</title>', false);
        }
    }
}
