<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('zones')->insert([
            [
                'name' => 'Zona 1 - Gerbangkertosusila',
                'cities' => 'Surabaya, Sidoarjo, Gresik, Mojokerto, Lamongan',
                'rate_per_kg' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zona 2 - Malang Raya & Tapal Kuda',
                'cities' => 'Malang, Batu, Pasuruan, Probolinggo, Jember',
                'rate_per_kg' => 12000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zona 3 - Mataraman & Madura',
                'cities' => 'Kediri, Madiun, Bojonegoro, Banyuwangi, Bangkalan, Sumenep',
                'rate_per_kg' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}