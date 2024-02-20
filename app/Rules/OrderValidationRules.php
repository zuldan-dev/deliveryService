<?php

namespace App\Rules;

use App\Enums\OrderStatusEnum;
use Illuminate\Validation\Rule;

class OrderValidationRules
{
    /**
     * @return array
     */
    public static function createRules(): array
    {
        return [
            'dishes' => 'required|array',
            'dishes.*' => 'numeric|exists:dishes,id',
        ];
    }

    /**
     * @return array
     */
    public static function viewStatusRules(): array
    {
        return [
            'id' => 'required|numeric|exists:orders,id',
        ];
    }

    /**
     * @return array
     */
    public static function updateStatusRules(): array
    {
        return [
            'id' => 'required|numeric|exists:orders,id',
            'status' => 'required|string|' . Rule::in(OrderStatusEnum::getArray()),
        ];
    }
}
