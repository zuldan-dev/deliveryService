<?php

namespace Tests\Feature;

use App\Enums\UserRoleEnum;
use App\Models\User;
use App\Support\CommandMessages;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SuperadminCreateCommandTest extends TestCase
{
    use DatabaseTransactions;

    private const TEST_COMMAND = 'superadmin:create';
    private const TEST_USERS = [
        'correct' => [
            'email' => 'test@test.com',
            'password' => 'test321',
        ],
        'incorrect_email' => [
            'email' => 'test.com',
            'password' => 'test321',
        ],
        'incorrect_password' => [
            'email' => 'test1@test.com',
            'password' => 'test',
        ],
    ];

    /**
     * Successfully superadmin creating
     * @return void
     */
    public function testCreateSuperadminUserSuccess(): void
    {
        $this->artisan(self::TEST_COMMAND, self::TEST_USERS['correct'])
            ->expectsOutput(CommandMessages::SUPERADMIN_CREATED_SUCCESSFULLY)
            ->assertExitCode(0);

        $this->assertTrue(User::where('email', self::TEST_USERS['correct']['email'])->exists());
        $this->assertTrue(
            User::where('email', self::TEST_USERS['correct']['email'])
                ->first()
                ->hasRole(UserRoleEnum::superadmin->value)
        );
    }

    /**
     * Superadmin creating with validation error
     * @return void
     */
    public function testSuperadminUserFail(): void
    {
        $this->artisan(self::TEST_COMMAND, self::TEST_USERS['incorrect_email'])
            ->expectsOutput(trans('validation.email', ['attribute' => 'email']));

        $this->artisan(self::TEST_COMMAND, self::TEST_USERS['incorrect_password'])
            ->expectsOutput(trans('validation.min.string', ['attribute' => 'password', 'min' => '6']));
    }

    /**
     * Creating superadmin with exists email
     * @return void
     */
    public function testSuperadminUserExists(): void
    {
        $this->artisan(self::TEST_COMMAND, [
            'email' => config('app.demo_users.superadmin.email'),
            'password' => config('app.demo_users.superadmin.password'),
        ])->expectsOutput(trans('validation.unique', ['attribute' => 'email']));
    }
}
