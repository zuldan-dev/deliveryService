<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (in_array(getenv('APP_ENV'), config('app.seeder_environments'))) {
            $this->call([
                UsersRolesSeeder::class,
                RestaurantsSeeder::class,
                DishesSeeder::class,
                DriversSeeder::class,
            ]);
        }
    }
}
