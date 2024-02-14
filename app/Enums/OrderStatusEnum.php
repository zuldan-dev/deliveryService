<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case pending = 'pending';
    case processed = 'processed';
    case completed = 'completed';
    case canceled = 'canceled';
}
