<?php

namespace App\Enums;

enum ShipmentStatus: string
{
    case DIBUAT = 'DIBUAT';
    case DIJEMPUT = 'DIJEMPUT';
    case TRANSIT = 'TRANSIT';
    case DIANTAR = 'DIANTAR';
    case DITERIMA = 'DITERIMA';

    public function label(): string
    {
        return match ($this) {
            self::DIBUAT => 'Pesanan dibuat',
            self::DIJEMPUT => 'Dijemput kurir',
            self::TRANSIT => 'Dalam transit',
            self::DIANTAR => 'Sedang diantar',
            self::DITERIMA => 'Diterima',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::DIBUAT => 'Data paket dan ongkir tersimpan. Paket menunggu penjemputan.',
            self::DIJEMPUT => 'Paket sudah diambil kurir dari alamat pengirim.',
            self::TRANSIT => 'Paket bergerak menuju kota tujuan.',
            self::DIANTAR => 'Kurir membawa paket ke alamat penerima.',
            self::DITERIMA => 'Paket diterima di alamat tujuan.',
        };
    }

    public function next(): ?self
    {
        return match ($this) {
            self::DIBUAT => self::DIJEMPUT,
            self::DIJEMPUT => self::TRANSIT,
            self::TRANSIT => self::DIANTAR,
            self::DIANTAR => self::DITERIMA,
            self::DITERIMA => null,
        };
    }
}
