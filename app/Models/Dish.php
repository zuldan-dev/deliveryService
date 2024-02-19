<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * This is model class for table 'dishes'
 *
 * Columns in the table 'dishes':
 * @property integer $id
 * @property string $name
 * @property float $price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Restaurant[] $restaurants
 */
class Dish extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'dishes';

    protected $fillable = ['name', 'price'];

    /**
     * @return BelongsToMany
     */
    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(
            Restaurant::class,
            RestaurantDish::class,
            'dish_id',
            'restaurant_id',
        );
    }
}
