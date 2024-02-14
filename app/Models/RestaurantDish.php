<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * This is model class for table 'restaurants_dishes'
 *
 * Columns in the table 'restaurants_dishes':
 * @property integer $id
 * @property integer $restaurant_id
 * @property integer $dish_id
 * @property Dish $dish
 * @property Restaurant $restaurant
 */
class RestaurantDish extends Model
{
    use HasFactory;

    protected $table = 'restaurants_dishes';
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class, 'dish_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
}
