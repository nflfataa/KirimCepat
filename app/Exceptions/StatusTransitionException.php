<?php

namespace App\Exceptions;

use RuntimeException;

class StatusTransitionException extends RuntimeException
{
    public static function finalStatus(): self
    {
        return new self('Paket sudah berstatus DITERIMA dan tidak bisa diperbarui lagi.');
    }

    public static function mustAdvanceOneStep(string $expected): self
    {
        return new self("Status hanya bisa maju satu langkah. Status berikutnya adalah {$expected}.");
    }
}
