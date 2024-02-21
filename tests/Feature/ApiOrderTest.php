<?php

namespace Tests\Feature;

use App\Enums\OrderStatusEnum;
use App\Models\Dish;
use App\Models\Order;
use App\Models\User;
use App\Support\ApiMessages;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ApiOrderTest extends TestCase
{
    use DatabaseTransactions;

    protected const ORDER_CREATE_ROUTE = 'api/order/create';
    protected const ORDER_STATUS_VIEW_ROUTE = 'api/order/status/view';
    protected const ORDER_STATUS_UPDATE_ROUTE = 'api/order/status/update';
    protected const WRONG_ORDER_ID = 999999;
    protected const WRONG_DISH_ID = 999999;
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
    protected const ORDER = [
        'id' => 99991,
        'status' => OrderStatusEnum::pending->value,
    ];

    protected string $userSuperadminToken;
    protected string $userClientToken;

    public function setUp(): void
    {
        parent::setUp();

        // Creating token for Superadmin
        $superadminUser = User::where(['email' => config('app.demo_users.superadmin.email')])->first();
        $this->userSuperadminToken = $superadminUser->createToken('test')->accessToken;

        // Creating token for Client
        $clientUser = User::where(['email' => config('app.demo_users.client.email')])->first();
        $this->userClientToken = $clientUser->createToken('test')->accessToken;

        Dish::factory()->createMany(self::DISHES);
        Order::insert([
            'id' => self::ORDER['id'],
            'status' => self::ORDER['status'],
            'user_id' => $clientUser->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function testOrderCreateSuccess(): void
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->putJson(self::ORDER_CREATE_ROUTE, [
                'dishes' => [self::DISHES[0]['id'], self::DISHES[1]['id']],
            ]);
        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['message' => ApiMessages::MESSAGE_ORDER_CREATED]);
    }

    public function testOrderCreateAuthorizationFailed(): void
    {
        // Not authorized
        $response = $this->putJson(self::ORDER_CREATE_ROUTE);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        // Wrong group
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userSuperadminToken])
            ->putJson(self::ORDER_CREATE_ROUTE);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testOrderCreateFailed(): void
    {
        // Empty dishes
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->putJson(self::ORDER_CREATE_ROUTE);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Not numeric dishes
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->putJson(self::ORDER_CREATE_ROUTE, ['dishes' => ['test']]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Not exists dishes
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->putJson(self::ORDER_CREATE_ROUTE, ['dishes' => [self::WRONG_DISH_ID]]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testOrderStatusViewSuccess(): void
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->getJson(self::ORDER_STATUS_VIEW_ROUTE . '?id=' . self::ORDER['id']);
        $response->assertStatus(Response::HTTP_OK)->assertJsonFragment(['status' => OrderStatusEnum::pending->value]);
    }

    public function testOrderStatusViewAuthorizationFailed(): void
    {
        // Not authorized
        $response = $this->getJson(self::ORDER_STATUS_VIEW_ROUTE);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        // Wrong group
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userSuperadminToken])
            ->getJson(self::ORDER_STATUS_VIEW_ROUTE);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testOrderStatusViewFailed(): void
    {
        // Empty Order ID
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->getJson(self::ORDER_STATUS_VIEW_ROUTE);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Wrong Order ID
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->getJson(self::ORDER_STATUS_VIEW_ROUTE . '?id=' . self::WRONG_ORDER_ID);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testOrderStatusUpdateSuccess(): void
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->putJson(self::ORDER_STATUS_UPDATE_ROUTE, [
                'id' => self::ORDER['id'],
                'status' => OrderStatusEnum::completed->value,
            ]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => ApiMessages::MESSAGE_ORDER_UPDATED]);
    }

    public function testOrderStatusUpdateAuthorizationFailed(): void
    {
        // Not authorized
        $response = $this->putJson(self::ORDER_STATUS_UPDATE_ROUTE);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        // Wrong group
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userSuperadminToken])
            ->putJson(self::ORDER_STATUS_UPDATE_ROUTE);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testOrderStatusUpdateFailed(): void
    {
        // Empty Order ID
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->putJson(self::ORDER_STATUS_UPDATE_ROUTE);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Wrong Order ID
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->putJson(self::ORDER_STATUS_UPDATE_ROUTE, [
                'id' => self::WRONG_ORDER_ID,
                'status' => OrderStatusEnum::completed->value,
            ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Wrong Status
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->putJson(self::ORDER_STATUS_UPDATE_ROUTE, [
                'id' => self::ORDER['id'],
                'status' => 'test',
            ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
