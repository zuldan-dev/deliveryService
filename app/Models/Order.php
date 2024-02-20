<?php

namespace App\Models;

use App\Events\OrderAssigned;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Collection, Model, Builder};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * This is model class for table 'orders'
 *
 * Columns in the table 'orders'
 * @property integer $id
 * @property integer $user_id
 * @property integer $driver_id
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @property Driver $driver
 */
class Order extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = ['user_id', 'status', 'driver_id'];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return BelongsToMany
     */
    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(
            Dish::class,
            'orders_dishes',
            'order_id',
            'dish_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }

    /**
     * @param Builder $query
     * @param int $days
     * @return Builder
     */
    public function scopeLastDays(Builder $query, int $days): Builder
    {
        return $query->where('orders.created_at', '>=', now()->subDays($days));
    }

    /**
     * @param int $days
     * @return Collection
     */
    public static function averageCostOfOrders(int $days): Collection
    {
        return static::lastDays($days)
            ->selectRaw('DATE(orders.created_at) as date')
            ->selectRaw('AVG(dishes.price) as average_cost')
            ->join('orders_dishes', 'orders.id', '=', 'orders_dishes.order_id')
            ->join('dishes', 'orders_dishes.dish_id', '=', 'dishes.id')
            ->groupBy('date')
            ->get();
    }

    /**
     * @param int $days
     * @return Collection
     */
    public static function dailyAmountOfDrivers(int $days): Collection
    {
        return static::lastDays($days)
            ->selectRaw('DATE(orders.created_at) as date')
            ->selectRaw('drivers.name as driver_name')
            ->selectRaw('SUM(drivers.revenue) as daily_revenue')
            ->join('drivers', 'orders.driver_id', '=', 'drivers.id')
            ->groupBy('date', 'driver_name')
            ->get();
    }

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::updated(function ($order) {
            if ($order->isDirty('driver_id')) {
                event(new OrderAssigned($order));
            }
        });
    }
}
