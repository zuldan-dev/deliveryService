<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * This is model class for table 'restaurants'
 *
 * Columns in the table 'restaurants':
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Restaurant extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'restaurants';

    protected $fillable = ['name', 'address'];
}
