<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'kurir@kirimcepat.test'],
            [
                'name' => 'Kurir Demo',
                'password' => 'password',
            ],
        );
    }
}
