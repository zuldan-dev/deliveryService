<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case superadmin = 'superadmin';
    case client = 'client';
}
