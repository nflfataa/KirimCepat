<?php

namespace Tests\Feature;

use App\Models\Shipment;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourierDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function shipment(string $status = 'DIBUAT'): Shipment
    {
        $zone = Zone::create(['name' => 'Zona 1', 'cities' => 'Surabaya', 'rate_per_kg' => 8_000]);

        $shipment = Shipment::create([
            'tracking_number' => 'KC20261123000042',
            'sender_name' => 'Rani',
            'sender_phone' => '081234567890',
            'sender_address' => 'Surabaya',
            'receiver_name' => 'Bagas',
            'receiver_phone' => '081398765432',
            'receiver_address' => 'Malang',
            'zone_id' => $zone->id,
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

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/kurir')->assertRedirect('/kurir/masuk');
    }

    public function test_login_page_renders(): void
    {
        $this->get('/kurir/masuk')
            ->assertOk()
            ->assertSee('Panel kurir')
            ->assertSee('Masuk');
    }

    public function test_demo_credentials_are_hidden_outside_local_environment(): void
    {
        $this->get('/kurir/masuk')
            ->assertOk()
            ->assertDontSee('Akun demo pengembangan');
    }

    public function test_courier_can_log_in_and_see_active_shipments(): void
    {
        $courier = User::factory()->create([
            'email' => 'kurir@kirimcepat.test',
            'password' => 'password',
        ]);
        $this->shipment();

        $this->post('/kurir/masuk', [
            'email' => 'kurir@kirimcepat.test',
            'password' => 'password',
        ])->assertRedirect('/kurir');

        $this->actingAs($courier)
            ->get('/kurir')
            ->assertOk()
            ->assertSee('KC20261123000042')
            ->assertSee('Bagas')
            ->assertSee('Dijemput kurir');
    }

    public function test_wrong_password_returns_error_without_logging_in(): void
    {
        User::factory()->create(['email' => 'kurir@kirimcepat.test', 'password' => 'password']);

        $this->from('/kurir/masuk')
            ->post('/kurir/masuk', ['email' => 'kurir@kirimcepat.test', 'password' => 'salah'])
            ->assertRedirect('/kurir/masuk')
            ->assertSessionHasErrors(['email' => 'Email atau kata sandi tidak cocok.']);

        $this->assertGuest();
    }

    public function test_finished_shipments_move_out_of_the_action_list(): void
    {
        $courier = User::factory()->create();
        $this->shipment('DITERIMA');

        $this->actingAs($courier)
            ->get('/kurir')
            ->assertOk()
            ->assertSee('Tidak ada paket yang menunggu pembaruan.')
            ->assertSeeInOrder(['Terakhir diterima', 'KC20261123000042']);
    }

    public function test_empty_state_is_shown_when_no_shipments_exist(): void
    {
        $courier = User::factory()->create();

        $this->actingAs($courier)
            ->get('/kurir')
            ->assertOk()
            ->assertSee('Tidak ada paket yang menunggu pembaruan.');
    }
}
