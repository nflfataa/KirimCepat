<?php

namespace App\Services;

use App\Exceptions\TrackingNumberExhaustedException;
use App\Models\TrackingSequence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class TrackingNumberGenerator
{
    public function next(): string
    {
        $now = now();
        $date = $now->toDateString();

        return DB::transaction(function () use ($now, $date) {
            TrackingSequence::query()->insertOrIgnore([
                'date' => $date,
                'last_number' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $sequence = TrackingSequence::query()->whereKey($date)->lockForUpdate()->firstOrFail();

            if ($sequence->last_number >= 999_999) {
                Log::warning('Kuota nomor resi harian habis.', ['date' => $date]);
                throw TrackingNumberExhaustedException::forDate($date);
            }

            $next = $sequence->last_number + 1;

            TrackingSequence::query()->whereKey($date)->update([
                'last_number' => $next,
                'updated_at' => $now,
            ]);

            return 'KC'.$now->format('Ymd').str_pad((string) $next, 6, '0', STR_PAD_LEFT);
        });
    }
}
