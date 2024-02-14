<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantsSeeder extends Seeder
{
    private const SEED_RESTAURANTS =
        'McDonalds,LaSpecia,Filvarok,Tbilisso,Safari,Numo,Lucky Bull,SushiYa,Simbiosy,Kaiser';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Restaurant::count() === 0) {
            $restaurants = array_map(function ($restaurant) {
                return ['name' => $restaurant];
            }, explode(',', self::SEED_RESTAURANTS));

            Restaurant::factory()->createMany($restaurants);
        }
    }
}
