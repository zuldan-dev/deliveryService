<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriversSeeder extends Seeder
{
    private const SEED_CARS = 'BMW X5,VW Passat,Toyota Camry,Honda Civic,Ford Mustang,Chevrolet Silverado,' .
    'Tesla Model S,Audi A4,Mercedes-Benz C-Class,Subaru Outback';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Driver::count() === 0) {
            $drivers = array_map(function ($car) {
                return ['car' => $car];
            }, explode(',', self::SEED_CARS));

            Driver::factory()->createMany($drivers);
        }
    }
}
