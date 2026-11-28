<?php

namespace Tests\Feature;

use App\Exceptions\TrackingNumberExhaustedException;
use App\Services\TrackingNumberGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TrackingNumberTest extends TestCase
{
    use RefreshDatabase;

    public function test_number_matches_the_documented_format_and_uses_the_business_date(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-23 23:59:00', 'Asia/Jakarta'));

        $resi = (new TrackingNumberGenerator)->next();

        $this->assertMatchesRegularExpression('/^KC[0-9]{14}$/', $resi);
        $this->assertSame('KC20261123000001', $resi);

        Carbon::setTestNow();
    }

    public function test_sequence_increments_with_zero_padding_within_a_day(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-23 08:00:00', 'Asia/Jakarta'));

        $generator = new TrackingNumberGenerator;

        $this->assertSame('KC20261123000001', $generator->next());
        $this->assertSame('KC20261123000002', $generator->next());

        Carbon::setTestNow();
    }

    public function test_sequence_resets_the_next_day(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-23 23:59:00', 'Asia/Jakarta'));
        $generator = new TrackingNumberGenerator;
        $generator->next();

        Carbon::setTestNow(Carbon::parse('2026-11-24 00:01:00', 'Asia/Jakarta'));

        $this->assertSame('KC20261124000001', $generator->next());

        Carbon::setTestNow();
    }

    public function test_exhausted_daily_quota_is_rejected(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-23 12:00:00', 'Asia/Jakarta'));

        DB::table('tracking_sequences')->insert([
            'date' => '2026-11-23',
            'last_number' => 999_999,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->expectException(TrackingNumberExhaustedException::class);

        (new TrackingNumberGenerator)->next();
    }
}
