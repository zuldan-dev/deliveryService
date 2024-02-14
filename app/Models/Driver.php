<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * This is model class for table 'drivers'
 *
 * Columns in the table 'drivers'
 * @property integer $id
 * @property string $name
 * @property string $car
 * @property string $contacts
 * @property float $revenue
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Driver extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'drivers';

    protected $fillable = ['name', 'car', 'contacts', 'revenue'];
}
