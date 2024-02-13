<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersRolesSeeder extends Seeder
{
    private const SEED_USERS = [
        [
            'name' => 'Admin',
            'email' => 'app.demo_users.superadmin.email',
            'password' => 'app.demo_users.superadmin.password',
            'role' => UserRoleEnum::superadmin->value,
        ],
        [
            'name' => 'Client',
            'email' => 'app.demo_users.client.email',
            'password' => 'app.demo_users.client.password',
            'role' => UserRoleEnum::client->value,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        # Roles creating
        foreach (array_column(UserRoleEnum::cases(), 'value') as $role) {
            Role::findOrCreate(strval($role));
        }

        # Users creating
        foreach (self::SEED_USERS as $user) {
            if (User::where('email', config($user['email']))->first()) {
                continue;
            }

            User::create([
                'name' => $user['name'],
                'email' => config($user['email']),
                'password' => Hash::make(config($user['password'])),
            ])->assignRole(strval($user['role']));
        }
    }
}
