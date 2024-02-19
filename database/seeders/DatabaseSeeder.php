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
        // Creating roles and test users for all environments
        $this->call(UsersRolesSeeder::class);

        if (in_array(getenv('APP_ENV'), config('app.seeder_environments'))) {
            $this->call([
                RestaurantsSeeder::class,
                DishesSeeder::class,
                DriversSeeder::class,
            ]);
        }
    }
}
