<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'name' => 'Zona 1 - Gerbangkertosusila',
                'cities' => 'Surabaya, Sidoarjo, Gresik, Mojokerto, Lamongan',
                'rate_per_kg' => 8000,
            ],
            [
                'name' => 'Zona 2 - Malang Raya & Tapal Kuda',
                'cities' => 'Malang, Batu, Pasuruan, Probolinggo, Jember',
                'rate_per_kg' => 12000,
            ],
            [
                'name' => 'Zona 3 - Mataraman & Madura',
                'cities' => 'Kediri, Madiun, Bojonegoro, Banyuwangi, Bangkalan, Sumenep',
                'rate_per_kg' => 15000,
            ],
        ];

        foreach ($zones as $zone) {
            Zone::query()->updateOrCreate(['name' => $zone['name']], $zone);
        }
    }
}
