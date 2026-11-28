<?php

namespace App\Exceptions;

use RuntimeException;

class TrackingNumberExhaustedException extends RuntimeException
{
    public static function forDate(string $date): self
    {
        return new self("Kuota nomor resi untuk tanggal {$date} sudah habis.");
    }
}
