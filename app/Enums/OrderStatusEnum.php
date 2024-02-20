<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case pending = 'pending';
    case processed = 'processed';
    case completed = 'completed';
    case canceled = 'canceled';

    /**
     * Converts enum to associative array
     * @param array $exceptions
     * @return array
     */
    public static function getArray(array $exceptions = []): array
    {
        $array = [];

        foreach (self::cases() as $case) {
            if (!in_array($case->name, $exceptions)) {
                $array[$case->name] = $case->value;
            }
        }

        return $array;
    }
}
