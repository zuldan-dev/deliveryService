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
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:5|max:255',
        ];
    }

    /**
     * Login user rules
     * @return array
     */
    public static function loginRules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:5|max:255',
        ];
    }
}
