<?php

namespace App\Repositories;

use App\Interfaces\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @param int $days
     * @return Collection
     */
    public function averageCostOfOrders(int $days): Collection
    {
        return $this->order->selectRaw('DATE(orders.created_at) as date')
            ->selectRaw('AVG(dishes.price) as average_cost')
            ->join('orders_dishes', 'orders.id', '=', 'orders_dishes.order_id')
            ->join('dishes', 'orders_dishes.dish_id', '=', 'dishes.id')
            ->lastDays($days)
            ->groupBy('date')
            ->get();
    }

    /**
     * @param int $days
     * @return Collection
     */
    public function dailyAmountOfDrivers(int $days): Collection
    {
        return $this->order->selectRaw('DATE(orders.created_at) as date')
            ->selectRaw('drivers.name as driver_name')
            ->selectRaw('SUM(drivers.revenue) as daily_revenue')
            ->join('drivers', 'orders.driver_id', '=', 'drivers.id')
            ->lastDays($days)
            ->groupBy('date', 'driver_name')
            ->get();
    }
}
