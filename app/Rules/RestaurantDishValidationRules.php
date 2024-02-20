<?php

namespace App\Rules;

class RestaurantDishValidationRules
{
    /**
     * Display dishes by restaurant rules
     * @return array
     */
    public static function viewDishesByRestaurantRules(): array
    {
        return [
            'restaurant_id' => 'required|numeric|exists:restaurants,id',
        ];
    }
}
