<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\ApiMessages;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected const WRONG_USERS = [
        'incorrect' => [
            'email' => 'wrong@user.test',
            'password' => '*******',
        ],
        'invalid' => [
            'email' => 'wrong',
            'password' => '****',
        ],
    ];

    protected const LOGIN_ROUTE = 'api/login';
    protected const LOGOUT_ROUTE = 'api/logout';

    /**
     * Success superadmin/client authentication
     * @return void
     */
    public function testApiLoginSuccess(): void
    {
        // Superadmin login
        $response = $this->postJson(self::LOGIN_ROUTE, config('app.demo_users.superadmin'));
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['token']);

        // Client login
        $response = $this->postJson(self::LOGIN_ROUTE, config('app.demo_users.client'));
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['token']);
    }

    /**
     * Validation/wrong credentials authentication fail
     * @return void
     */
    public function testApiLoginFailed(): void
    {
        // Invalid credentials
        $response = $this->postJson(self::LOGIN_ROUTE, self::WRONG_USERS['invalid']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment([
                'email' => [trans('validation.email', ['attribute' => 'email'])],
                'password' => [trans('validation.min.string', ['attribute' => 'password', 'min' => '5'])],
            ]);

        // Not existed user
        $response = $this->postJson(self::LOGIN_ROUTE, self::WRONG_USERS['incorrect']);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED)->assertExactJson([
            'error' => ApiMessages::ERROR_UNAUTHENTICATED,
        ]);
    }

    /**
     * Logout success
     * @return void
     */
    public function testApiLogoutSuccess(): void
    {
        $user = User::where(['email' => config('app.demo_users.client.email')])->first();

        // Checking that user exists
        $this->assertNotNull($user);
        // Creating token for user
        $token = $user->createToken('test')->accessToken;

        // Passing token to header
        $response = $this->postJson(self::LOGOUT_ROUTE, [], ['Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => ApiMessages::MESSAGE_LOGGED_OUT]);
    }
}
