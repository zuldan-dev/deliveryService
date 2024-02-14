<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\RestaurantDish;
use Illuminate\Database\Seeder;

class DishesSeeder extends Seeder
{
    private const SEED_DISHES = 'Spaghetti Carbonara,Chicken Alfredo,Cheeseburger,Pad Thai,Margherita Pizza,' .
    'Sushi Rolls,Beef Tacos,Chicken Tikka Masala,Caesar Salad,Fish and Chips,Lobster Bisque,Shrimp Scampi,' .
    'Chicken Parmesan,Philly Cheesesteak,California Roll,Pulled Pork Sandwich,Steak Frites,Clam Chowder,' .
    'Chicken Satay,Fettuccine Alfredo,Beef Stroganoff,Gyoza,Eggs Benedict,Beef Wellington,Paella,French Soup,' .
    'Falafel Wrap,Margarita Pizza,Chicken Fried Rice,Tandoori Chicken,Beef Burgundy,Shrimp Cocktail,' .
    'Eggs Florentine,Lobster Roll,Chicken Caesar Salad,Crab Cakes,Vegetable Stir-Fry,New York Cheesecake,' .
    'Chicken Shawarma,Miso Soup,Beef Teriyaki,Lobster Mac and Cheese,Tiramisu,Beef Brisket,Greek Salad,' .
    'Chicken Quesadilla,Pad See Ew,Pulled Chicken Sandwich,Chicken Pot Pie,Veggie Burger';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Dish::count() === 0) {
            $dishes = array_map(function ($dish) {
                return ['name' => $dish];
            }, explode(',', self::SEED_DISHES));

            Dish::factory()->createMany($dishes);

            // Simple Restaurant-Dish relations creating
            for ($i = 1; $i <= 10; $i++) {
                for ($j = 1; $j <= 5; $j++) {
                    RestaurantDish::create([
                        'restaurant_id' => $i,
                        'dish_id' => ($i - 1) * 5 + $j,
                    ]);
                }
            }
        }
    }
}
