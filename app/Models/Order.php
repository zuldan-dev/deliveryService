<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * This is model class for table 'orders'
 *
 * Columns in the table 'orders'
 * @property integer $id
 * @property integer $user_id
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 */
class Order extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = ['user_id', 'status'];
}
