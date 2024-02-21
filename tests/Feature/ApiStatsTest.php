<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ApiStatsTest extends TestCase
{
    use DatabaseTransactions;

    protected const COST_OF_ORDERS_ROUTE = 'api/stats/average/cost_of_orders';
    protected const AMOUNT_BY_DRIVERS_ROUTE = 'api/stats/daily/amount_by_drivers';

    protected string $userSuperadminToken;
    protected string $userClientToken;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Creating token for Superadmin
        $superadminUser = User::where(['email' => config('app.demo_users.superadmin.email')])->first();
        $this->userSuperadminToken = $superadminUser->createToken('test')->accessToken;

        // Creating token for Client
        $clientUser = User::where(['email' => config('app.demo_users.client.email')])->first();
        $this->userClientToken = $clientUser->createToken('test')->accessToken;
    }

    /**
     * @return void
     */
    public function testCostOfOrdersSuccess(): void
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userSuperadminToken])
            ->getJson(self::COST_OF_ORDERS_ROUTE);
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['result']);
    }

    /**
     * @return void
     */
    public function testCostOfOrdersFailed(): void
    {
        // Without authorization
        $response = $this->getJson(self::COST_OF_ORDERS_ROUTE);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        // Wrong user group
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->getJson(self::COST_OF_ORDERS_ROUTE);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @return void
     */
    public function testAmountByDriversSuccess(): void
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userSuperadminToken])
            ->getJson(self::AMOUNT_BY_DRIVERS_ROUTE);
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['result']);
    }

    /**
     * @return void
     */
    public function testAmountByDriversFailed(): void
    {
        // Without authorization
        $response = $this->getJson(self::AMOUNT_BY_DRIVERS_ROUTE);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        // Wrong user group
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->userClientToken])
            ->getJson(self::AMOUNT_BY_DRIVERS_ROUTE);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
