<?php

namespace App\Models;

use App\Events\OrderAssigned;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, Builder};
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
