<?php

namespace App\Interfaces\Repositories;

use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    /**
     * @param int $days
     * @return Collection
     */
    public function averageCostOfOrders(int $days): Collection;

    /**
     * @param int $days
     * @return Collection
     */
    public function dailyAmountOfDrivers(int $days): Collection;
}
