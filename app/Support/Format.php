<?php

namespace App\Support;

use Carbon\CarbonInterface;

final class Format
{
    private const MONTHS = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
    ];

    public static function rupiah(int|float $amount): string
    {
        return 'Rp'.number_format((int) $amount, 0, ',', '.');
    }

    public static function number(int|float|string $value, int $precision = 2): string
    {
        $text = number_format((float) $value, $precision, ',', '.');

        if ($precision > 0) {
            $text = rtrim(rtrim($text, '0'), ',');
        }

        return $text === '' ? '0' : $text;
    }

    public static function weight(int|float|string $kilograms, int $precision = 2): string
    {
        return self::number($kilograms, $precision).' kg';
    }

    public static function dateTime(CarbonInterface $date): string
    {
        $wib = $date->copy()->setTimezone('Asia/Jakarta');

        return sprintf(
            '%s %s %s, %s WIB',
            $wib->format('d'),
            self::MONTHS[(int) $wib->format('n')],
            $wib->format('Y'),
            $wib->format('H.i'),
        );
    }

    public static function date(CarbonInterface $date): string
    {
        $wib = $date->copy()->setTimezone('Asia/Jakarta');

        return sprintf(
            '%s %s %s',
            $wib->format('d'),
            self::MONTHS[(int) $wib->format('n')],
            $wib->format('Y'),
        );
    }
}
