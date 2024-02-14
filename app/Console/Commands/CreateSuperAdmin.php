<?php

namespace App\Console\Commands;

use App\Enums\UserRoleEnum;
use App\Models\User;
use App\Rules\UserValidationRules;
use App\Support\CommandMessages;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as Validation;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superadmin:create {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating user with superadmin role';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $validator = $this->validateInput($email, $password);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return;
        }

        $this->createSuperadminUser($email, $password);
    }

    private function createSuperadminUser(string $email, string $password): void
    {
        try {
            User::create([
                'name' => strstr($email, '@', true),
                'email' => $email,
                'password' => Hash::make($password),
            ])->assignRole(UserRoleEnum::superadmin->value);
        } catch (QueryException $e) {
            $this->error($e->getMessage());
        }

        $this->info(CommandMessages::SUPERADMIN_CREATED_SUCCESSFULLY);
    }

    /**
     * @param string $email
     * @param string $password
     * @return Validation
     */
    private function validateInput(string $email, string $password): Validation
    {
        return Validator::make(
            [
                'email' => $email,
                'password' => $password,
            ],
            UserValidationRules::createRules()
        );
    }
}
