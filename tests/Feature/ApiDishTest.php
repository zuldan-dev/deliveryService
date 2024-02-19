<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\Restaurant;
use App\Models\RestaurantDish;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ApiDishTest extends TestCase
{
    use DatabaseTransactions;

    protected const DISH_LIST_ROUTE = 'api/dishes/list';
    protected const WRONG_RESTAURANT_ID = 999999;
    protected const RESTAURANTS = [
        [
            'id' => 99991,
            'name' => 'KFC',
        ],
        [
            'id' => 99992,
            'name' => 'Subway',
        ],
    ];
    protected const EMPTY_RESTAURANT_NAME = 'Subway';
    protected const DISHES = [
        [
            'id' => 99991,
            'name' => 'Hamburger',
        ],
        [
            'id' => 99992,
            'name' => 'Cheeseburger',
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();

        Restaurant::factory()->createMany(self::RESTAURANTS);

        Dish::factory()->createMany(self::DISHES);

        RestaurantDish::insert([
            ['restaurant_id' => self::RESTAURANTS[0]['id'], 'dish_id' => self::DISHES[0]['id']],
            ['restaurant_id' => self::RESTAURANTS[0]['id'], 'dish_id' => self::DISHES[1]['id']],
        ]);
    }

    /**
     * Success list response
     * @return void
     */
    public function testApiDishListSuccess(): void
    {
        $response = $this->getJson(self::DISH_LIST_ROUTE . '?restaurant_id=' . self::RESTAURANTS[0]['id']);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['dishes']);
        $this->assertCount(count(self::DISHES), $response->json(['dishes']));
    }

    /**
     * Empty list response
     * @return void
     */
    public function testApiDishListEmpty(): void
    {
        $response = $this->getJson(self::DISH_LIST_ROUTE . '?restaurant_id=' . self::RESTAURANTS[1]['id']);
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['dishes']);
        $this->assertCount(0, $response->json(['dishes']));
    }

    /**
     * Error response
     * @return void
     */
    public function testApiDishListError(): void
    {
        // Not exists restaurant Id
        $response = $this->getJson(self::DISH_LIST_ROUTE . '?restaurant_id=' . self::WRONG_RESTAURANT_ID);
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)->assertJsonStructure(['error']);

        // Not valid restaurant Id
        $response = $this->getJson(self::DISH_LIST_ROUTE . '?restaurant_id=aaa');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'restaurant_id' => [trans('validation.numeric', ['attribute' => 'restaurant id'])],
            ]);
    }
}
