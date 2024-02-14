<?php

namespace App\Rules;

class UserValidationRules
{
    /**
     * Create user rules
     * @return array
     */
    public static function createRules(): array
    {
        return [
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }
}
